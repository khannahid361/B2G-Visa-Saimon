<?php

namespace Modules\Bank\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Modules\Bank\Entities\Bank;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\BaseController;
use Modules\Account\Entities\Transaction;
use Modules\Account\Entities\ChartOfAccount;
use Modules\Bank\Entities\ManageBank;
use Modules\Bank\Http\Requests\BankFormRequest;
use Modules\Bank\Http\Requests\GiftCardTypeFormRequest;
use Modules\Setting\Entities\Showroom;

class BankController extends BaseController
{
    private const CASH_AT_BANK = 'Cash At Bank';
    public function __construct(Bank $model){
        $this->model = $model;
    }

    public function index(){
        if(permission('bank-access')){
            $this->setPageData('Bank List','Bank List','fas fa-university',[['name' => 'Bank List']]);
            $banks      = ManageBank::where('bank_type',1)->where('status',1)->get();
            $employees  = User::where('role_id','!=',3)->select('id', 'name', 'phone')->get();
            return view('bank::index',compact('banks','employees'));
        }else{
            return $this->access_blocked();
        }
    }

    public function get_datatable_data(Request $request){
        if($request->ajax()){
            if(permission('bank-access')){

                if (!empty($request->bank_name)) {
                    $this->model->setBankName($request->bank_name);
                }
                if (!empty($request->account_name)) {
                    $this->model->setAccountName($request->account_name);
                }
                if (!empty($request->account_number)) {
                    $this->model->setAccountNumber($request->account_number);
                }

                $this->set_datatable_default_properties($request);//set datatable default properties
                $list = $this->model->getDatatableList();//get table data
                $data = [];
                $no = $request->input('start');
                foreach ($list as $value) {
                    $no++;
                    $action = '';
//                    if(permission('bank-edit')){
//                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->id . '">'.self::ACTION_BUTTON['Edit'].'</a>';
//                    }
                    // if(permission('bank-delete')){
                    //     $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->bank_name . '">'.self::ACTION_BUTTON['Delete'].'</a>';
                    // }
                    $row = [];
                    $row[] = $no;
                    $row[] = $value->bank_name;
                    $row[] = $value->account_name;
                    $row[] = $value->user->name;
                    $row[] = $value->account_number;
                    $row[] = $value->balance ? $value->balance . ' /-TK' : 0 . ' /-TK';
                    $row[] = permission('bank-edit') ? change_status($value->id,$value->status, $value->bank_name) : STATUS_LABEL[$value->status];
                    $row[] = action_button($action);//custom helper function for action button
                    $data[] = $row;
                }
                return $this->datatable_draw($request->input('draw'),$this->model->count_all(),
                $this->model->count_filtered(), $data);
            }
        }else{
            return response()->json($this->unauthorized());
        }
    }

    public function store_or_update_data(BankFormRequest $request){
        if($request->ajax()){
            if(permission('bank-add') || permission('bank-edit')){
                DB::beginTransaction();
                try {
                    $bank_d     = ManageBank::where('id',$request->bank_name)->first();
                    $collection = collect($request->all())->merge([
                        'bank_name' => $bank_d->bank_name, 'account_name' => $bank_d->account_name,'account_number' => $bank_d->account_number,'account_id' => $bank_d->id
                    ]);
                    $collection = $this->track_data($collection,$request->update_id);
                    $result     = $this->model->updateOrCreate(['id'=>$request->update_id],$collection->all());
                    $output       = $this->store_message($result, $request->update_id);
                    DB::commit();

                } catch (Exception $e) {
                    DB::rollback();
                    $output = ['status' => 'error','message' => $e->getMessage()];
                }
            }else{
                $output       = $this->unauthorized();
            }
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }
    }

    public function edit(Request $request){
        if($request->ajax()){
            if(permission('bank-edit')){
                $data   = $this->model->findOrFail($request->id);
                $output = $this->data_message($data); //if data found then it will return data otherwise return error message
            }else{
                $output       = $this->unauthorized();
            }
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }
    }

    public function delete(Request $request){
        if($request->ajax()){
            if(permission('bank-delete')){
                DB::beginTransaction();
                try {
                    $result = false;
                    $bank   = $this->model->find($request->id);
                    if($bank)
                    {
                        $result = $bank->delete();
                    }
                    $output   = $result ? ['status' => 'success','message' => 'Data has been deleted successfully']
                    : ['status' => 'error','message' => 'Failed to delete data'];
                    $this->model->flushCache();
                    DB::commit();
                } catch (Exception $e) {
                    DB::rollback();
                    $output = ['status' => 'error','message' => $e->getMessage()];
                }
            }else{
                $output       = $this->unauthorized();
            }
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }
    }

    public function change_status(Request $request){
        if($request->ajax()){
            if(permission('bank-edit')){
                $result   = $this->model->find($request->id)->update(['status' => $request->status]);
                $output   = $result ? ['status' => 'success','message' => 'Status Has Been Changed Successfully']
                : ['status' => 'error','message' => 'Failed To Change Status'];
                $this->model->flushCache();
            }else{
                $output       = $this->unauthorized();
            }
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }
    }


}
