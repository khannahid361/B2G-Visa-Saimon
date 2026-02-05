<?php

namespace Modules\Ticket\Http\Controllers;

use App\Events\NoticeEvent;
use App\Http\Controllers\BaseController;
use App\Models\Department;
use App\Models\User;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Keygen\Keygen;
use Modules\Ticket\Entities\Ticket;
use Modules\Ticket\Http\Requests\TicketFormRequest;
use Pusher\Pusher;
use const http\Client\Curl\AUTH_ANY;

class TicketController extends BaseController
{
    public function __construct(Ticket $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if (permission('ticket-access')) {
            $setTitle = __('Token');
            $this->setPageData($setTitle, $setTitle, 'far fa-handshake', [['name' => $setTitle]]);
            $employee = User::all();
            $department = Department::where([
                ['id', '!=', 4],
                ['id', '!=', 12]
            ])->get();
            $user = Auth::user()->id;
            return view('ticket::index', compact('user', 'department', 'employee'));
        } else {
            return $this->access_blocked();
        }
    }

    public function generateTicketCode()
    {
        $code = Keygen::numeric(8)->generate();
        //Check Material Code ALready Exist or Not
        if (DB::table('tickets')->where('ticket_code', $code)->exists()) {
            $this->generateProductCode();
        } else {
            return response()->json($code);
        }
    }

    public function get_datatable_data(Request $request)
    {
        if ($request->ajax()) {
            if (permission('ticket-access')) {
                if (!empty($request->name)) {
                    $this->model->setName($request->name);
                }
                if (!empty($request->phone)) {
                    $this->model->setPhone($request->phone);
                }
                $this->set_datatable_default_properties($request); //set datatable default properties
                $list = $this->model->getDatatableList(); //get table data

                $data = [];

                $no = $request->input('start');
                foreach ($list as $value) {
                    if (Auth::user()->role_id == 1 or $value->sender_id == Auth::user()->id or $value->receiver_id == Auth::user()->id or $value->ticket_receiver_id == Auth::user()->id) {
                        $no++;
                        $action = '';
                        if (permission('ticket-edit')) {
                            if (empty($value->ticket_receiver_id)) {
                                $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->id . '">' . $this->actionButton('Edit') . '</a>';
                            }
                        }
                        if (permission('edit-for-supervisor')) {
                            if ($value->status != 3) {
                                $action .= ' <a class="dropdown-item edit_data1" data-id="' . $value->id . '">' . $this->actionButton('Edit Executive') . '</a>';
                            }
                        }
                        if (permission('ticket-delete')) {
                            $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->name . '">' . $this->actionButton('Delete') . '</a>';
                        }
                        if (permission('ticket-view')) {
                            $action .= ' <a class="dropdown-item view_data" href="' . route("ticket.ticket.show", $value->id) . '">' . self::ACTION_BUTTON['View'] . '</a>';
                        }
                        $row   = [];
                        $row[] = $no;
                        $row[] = $value->department ? $value->department->department_name : '';
                        $row[] = $value->name;
                        $row[] = $value->ticket_code;
                        $row[] = $value->phone;
                        $row[] = $value->date;
                        $row[] = $value->receiver ? $value->receiver->name : '';
                        $row[] = $value->ticket_recever ? $value->ticket_recever->name : '<span class="label label-warning label-pill label-inline">Not Assigned</span>';
                        $row[] = TICKET_LABEL[$value->status];
                        $row[] = action_button($action); //custom helper function for action button
                        $data[] = $row;
                    }
                }
                return $this->datatable_draw(
                    $request->input('draw'),
                    $this->model->count_all(),
                    $this->model->count_filtered(),
                    $data
                );
            }
        } else {
            return response()->json($this->unauthorized());
        }
    }

    public function store_or_update_data(TicketFormRequest $request)
    {

        if ($request->ajax()) {
            if (permission('ticket-add')) {
                DB::beginTransaction();
                try {
                    $collection = collect($request->all());
                    $ticket     = $this->model->updateOrCreate(['ticket_code' => $request->ticket_code], $collection->all());
                    $output     = $this->store_message($ticket, $request->update_id);
                    $options = array(
                        'cluster' => 'ap2',
                        'useTLS' => true
                    );
                    $pusher = new Pusher(
                        env('PUSHER_APP_KEY'),
                        env('PUSHER_APP_SECRET'),
                        env('PUSHER_APP_ID'),
                        $options
                    );
                    $id = 2;
                    $data = ['from' => $id];
                    $pusher->trigger('symon_visa_management', 'ticketNotification', $data);
                    DB::commit();
                } catch (Exception $e) {
                    DB::rollBack();
                    $output = ['status' => 'error', 'message' => $e->getMessage()];
                }
            } else {
                $output       = $this->unauthorized();
            }
            return response()->json($output);
        } else {
            return response()->json($this->unauthorized());
        }
    }

    public function edit(Request $request)
    {
        if ($request->ajax()) {
            if (permission('ticket-edit')) {
                $data   = $this->model->with('receiver', 'department')->findOrFail($request->id);
                $output = $this->data_message($data); //if data found then it will return data otherwise return error message
                return response()->json($output);
            } else {
                $output  = $this->unauthorized();
            }
            return response()->json($output);
        } else {
            return response()->json($this->unauthorized());
        }
    }
    public function editChild(Request $request)
    {
        if ($request->ajax()) {
            if (permission('edit-for-supervisor')) {
                $data   = $this->model->with('ticket_recever', 'department')->findOrFail($request->id);
                $output = $this->data_message($data); //if data found then it will return data otherwise return error message
                return response()->json($output);
            } else {
                $output  = $this->unauthorized();
            }
            return response()->json($output);
        } else {
            return response()->json($this->unauthorized());
        }
    }
    public function assignEmployss(Request $request)
    {
        try {
            $id = $request->update_id;
            $data = [
                'status' => 2,
                'ticket_receiver_id' => $request->ticket_receiver_id,
            ];
            $data = $this->model->where('id', $id)->update($data);
            $output     = $this->assign_message($data);
        } catch (Exception $e) {
            $output = ['status' => 'error', 'message' => $e->getMessage()];
        }
        return redirect()->back()->with($output);
    }


    public function delete(Request $request)
    {
        if ($request->ajax()) {
            if (permission('ticket-delete')) {
                $checklist = $this->model->find($request->id);
                $result    = $checklist->delete();

                $output   = $this->delete_message($result);
            } else {
                $output   = $this->unauthorized();
            }
            return response()->json($output);
        } else {
            return response()->json($this->unauthorized());
        }
    }
    public function show(Request $request, $id)
    {
        $data = Ticket::find($id);
        $user   = $this->model->findOrFail($request->id);
        return view('ticket::ticketprint', compact('user', 'data'));
    }


    public function approval(Request $request, $id)
    {
        try {
            $data = [
                'ticket_receiver_id'   => $request->receiver_id,
                'status'   => '2',
            ];
            $data = $this->model->where('id', $request->id)->update($data);
            $output     = $this->assign_message($data);
        } catch (Exception $e) {
            $output = ['status' => 'error', 'message' => $e->getMessage()];
        }
        return redirect()->back()->with($output);
    }

    public function approvalAccept(Request $request)
    {
        //      return  $request->all();
        try {
            $data = [
                'status' => '3',
            ];
            $data = $this->model->where('id', $request->exe_id)->update($data);
            $output     = $this->status_message($data);
        } catch (Exception $e) {
            $output = ['status' => 'error', 'message' => $e->getMessage()];
        }
        return redirect()->back()->with($output);
    }
}
