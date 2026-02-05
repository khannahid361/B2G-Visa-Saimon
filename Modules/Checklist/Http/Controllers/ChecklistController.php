<?php

namespace Modules\Checklist\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Models\Country;
use App\Models\Department;
use App\Models\VisaType;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Checklist\Entities\Checklist;
use Modules\Checklist\Entities\ChecklistDetailes;
use Modules\Checklist\Http\Requests\ChecklistFormRequest;
use Pusher\Pusher;

class ChecklistController extends BaseController
{
    public function __construct(Checklist $model)
    {
        $this->model = $model;
    }

    public function index()
    {
        if(permission('checklist-access')){
            $setTitle = __('Check list');
            $this->setPageData($setTitle,$setTitle,'far fa-handshake',[['name'=> $setTitle]]);
            $visa_type = VisaType::all();
            $user = Auth::user()->id;
            return view('checklist::index',compact('visa_type','user'));
        }else{
            return $this->access_blocked();
        }
    }

    public function create(){
        if(permission('checklist-add')){
            $setTitle = __('Check list Add');
            $this->setPageData($setTitle,$setTitle,'far fa-handshake',[['name'=> $setTitle]]);
            $visa_type  = VisaType::all();
            $user       = Auth::user()->id;
            $countries  = Country::where('type',1)->get();
            $toCountries= Country::where('type',2)->get();
            return view('checklist::create',compact('visa_type','user','countries','toCountries'));
        }else{
            return $this->access_blocked();
        }
    }

    public function store_or_update_data(ChecklistFormRequest $request){
        if(permission('checklist-add')){
//            return $request->all();
            DB::beginTransaction();
            try {
                $collection = collect($request->all());
                return $collection;
                $checklist  = $this->model->updateOrCreate(['visaType_id'=>$request->visaType_id],$collection->all());
                $output     = $this->store_message($checklist,$request->update_id);
                DB::commit();
            } catch (Exception $e) {
                DB::rollBack();
                $output = ['status' => 'error','message' => $e->getMessage()];
            }
        }else{
            $output       = $this->unauthorized();
        }
        return redirect()->route('checklist')->with($output);
    }

    public function store(Request $request){
        if(permission('checklist-add')){
//            return $request->all();
            DB::beginTransaction();
            try{
                $checklist = new Checklist();
                $checklist->user_id             = $request->user_id;
                $checklist->date                = $request->date;
                $checklist->appliactionType_id  = $request->appliactionType_id;
                $checklist->visaType_id         = $request->visaType_id;
                $checklist->price               = $request->price + $request->service_charge;
                $checklist->service_charge      = $request->service_charge;
                $checklist->title               = $request->title;
                $checklist->fromCountry_id      = $request->fromCountry_id;
                $checklist->toCountry_id        = $request->toCountry_id;
                $checklist->description         = $request->description;

                if($request->hasFile('image'))
                {
                    $file       = $request->file('image');
                    $extension  = $file->getClientOriginalExtension();
                    $filename   = time().'.'.$extension;
                    $file->move('storage',$filename);
                    $checklist->image = $filename;
                }
              $checklist->save();
              $id = $checklist->id;
//                return $id;
                $checklistDetails = [];
                foreach ($request->checklist_name as $value){
                        $checklistDetails[]   = [
                            'checklist_id'    =>  $id,
                            'checklist_name'  =>  $value,
                        ];
                    }
//                return response()->json($checklistDetails);
                $checklist = ChecklistDetailes::insert($checklistDetails);
                $output     = $this->store_message($checklist);
                DB::commit();
            }catch(Exception $e){
                DB::rollBack();
                $output = ['status' => 'error','message' => $e->getMessage()];
            }
        }else{
            $output = $this->unauthorized();
        }
        return redirect()->route('checklist')->with($output);
    }

    public function get_datatable_data(Request $request){
        if($request->ajax()){
            if(permission('checklist-access')){
                if (!empty($request->name)) {
                    $this->model->setName($request->name);
                }
                $this->set_datatable_default_properties($request);//set datatable default properties
                 $list = $this->model->getDatatableList();//get table data
                $data = [];

                $no = $request->input('start');
                foreach ($list as $value) {
                    $no++;
                    $action = '';
                    if(permission('checklist-edit')){
                        $action .= ' <a class="dropdown-item" href="'.route("checklist.edit",$value->id).'">'.$this->actionButton('Edit').'</a>';
                    }
                    if(permission('checklist-edit')){
                        $action .= ' <a class="dropdown-item" href="'.route("checklist.duplicate",$value->id).'">'.$this->actionButton('Duplicate').'</a>';
                    }
                    if(permission('checklist-view')){
                        $action .= ' <a class="dropdown-item" href="'.url("checklist/view/".$value->id).'">'.$this->actionButton('View').'</a>';
                    }
                    if (permission('checklist-view')) {
                        $action .= ' <a class="dropdown-item change_status"  data-id="'.$value->id.'"  data-status="'.$value->status.'" ><i class="fas fa-check-circle text-success mr-2"></i> Change Status</a>';
                    }
//                    if(permission('checklist-delete')){
//                        $action .= ' <a class="dropdown-item delete_data"  data-id="' . $value->id . '" data-name="' . $value->price . '">'.$this->actionButton('Delete').'</a>';
//                    }

                    $row = [];
                    $row[] = $no;
                    $row[] = $value->created_at->format('Y-m-d');
                    $row[] =  APPLICATION_TYPE[$value->appliactionType_id];
                    $row[] = $value->visaType ? $value->visaType->visa_type : '';
                    $row[] = $value->formCountry ? $value->formCountry->country_name : '';
                    $row[] = $value->toCountry ? $value->toCountry->country_name : '';
                    $row[] = $value->title;
                    $row[] = $value->price - $value->service_charge. ' ' .'/TK';
                    $row[] = $value->service_charge. ' ' .'/TK';
                    $row[] = $value->price. ' ' .'/TK';
                    $row[] = substr(strip_tags($value->description), 0, 150);
                    $row[] = CHICKLIST_STATUS_VALUE[$value->status];
                    $row[] = action_button($action);//custom helper function for action button
                    $data[]= $row;
                }
                return $this->datatable_draw($request->input('draw'),$this->model->count_all(),
                    $this->model->count_filtered(), $data);
            }
        }else{
            return response()->json($this->unauthorized());
        }
    }

    public function show(int $id){
        if(permission('checklist-view')){
            $setTitle = __('checklist');
            $setSubTitle = __('checklist Details');
            $this->setPageData($setSubTitle,$setSubTitle,'fas fa-paste',[['name'=>$setTitle,'link'=> route('checklist')],['name' => $setSubTitle]]);
            $chicklist = $this->model->with('visaType')->findOrFail($id);
            return view('checklist::details',compact('chicklist'));
        }else{
            return $this->access_blocked();
        }
    }

    public function edit(int $id){
        if(permission('checklist-edit')){
            $setTitle = __('checklist');
            $setSubTitle = __('Edit checklist');
            $this->setPageData($setSubTitle,$setSubTitle,'fab fa-pencil',[['name'=>$setTitle,'link'=> route('checklist')],['name' => $setSubTitle]]);
            $data = [
                'checklist'             => $this->model->findOrFail($id),
                'visa_type'             => VisaType::all(),
                'countries'             => Country::where('type',1)->get(),
                'toCountries'           => Country::where('type',2)->get(),
                'user'                  => Auth::user()->id,
                'checklist_detailes'    => ChecklistDetailes::where('checklist_id',$id)->get()
            ];
            return view('checklist::edit',$data);
        }else{
            return $this->access_blocked();
        }
    }

    public function duplicate(int $id){
        if(permission('checklist-edit')){
            $setTitle = __('checklist');
            $setSubTitle = __('Duplicate checklist');
            $this->setPageData($setSubTitle,$setSubTitle,'fab fa-pencil',[['name'=>$setTitle,'link'=> route('checklist')],['name' => $setSubTitle]]);
            $data = [
                'checklist'             => $this->model->findOrFail($id),
                'visa_type'             => VisaType::all(),
                'countries'             => Country::where('type',1)->get(),
                'toCountries'           => Country::where('type',2)->get(),
                'user'                  => Auth::user()->id,
                'checklist_detailes'    => ChecklistDetailes::where('checklist_id',$id)->get()
            ];
            return view('checklist::duplicate',$data);
        }else{
            return $this->access_blocked();
        }
    }

    public function update(Request $request,$id){
//            return $request->File('image');
            $checklist                      = Checklist::find($id);
            $checklist->service_charge      = $request->service_charge;
            $checklist->price               = $request->price + $request->service_charge;
            $checklist->title               = $request->title;
            $checklist->description         = $request->description;
            if($request->File('image'))
            {
                $file = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $filename = time().'.'.$extension;
                $file->move('storage',$filename);
                $checklist->image = $filename;
            }else{
                $checklist->image = $checklist->image;
            }
            $checklist->update();
        return redirect()->back();
    }

    public function checklistUpdate(Request $request){
        if(permission('checklist-edit')){
            DB::beginTransaction();
            try{

                $collection  = collect($request->all())->except('_token');
                $checklists  = ChecklistDetailes::where('id',$request->id);
                $checklists->update($collection->all());
                $outputs     = $this->store_message($checklists);
                DB::commit();
            }catch(Exception $e){
                DB::rollBack();
                $outputs = ['status' => 'error','message' => $e->getMessage()];
            }
        }else{
            $outputs = $this->unauthorized();
        }
        return redirect()->back();
    }

    public function checklistStore(Request $request){
        if(permission('checklist-add')){
//            return $request->all();
            DB::beginTransaction();
            try{
                $checklist = new Checklist();
                $checklist->user_id             = $request->user_id;
                $checklist->date                = $request->date;
                $checklist->appliactionType_id  = $request->appliactionType_id;
                $checklist->visaType_id         = $request->visaType_id;
                $checklist->service_charge      = $request->service_charge;
                $checklist->price               = $request->price + $request->service_charge;
                $checklist->title               = $request->title;
                $checklist->fromCountry_id      = $request->fromCountry_id;
                $checklist->toCountry_id        = $request->toCountry_id;
                $checklist->description         = $request->description;

                if($request->hasFile('image'))
                {
                    $file       = $request->file('image');
                    $extension  = $file->getClientOriginalExtension();
                    $filename   = time().'.'.$extension;
                    $file->move('storage',$filename);
                    $checklist->image = $filename;
                }
                $checklist->save();
                $id = $checklist->id;
//                return $id;
                $checklistDetails = [];
                foreach ($request->checklist_name as $value){
                    $checklistDetails[]   = [
                        'checklist_id'    =>  $id,
                        'checklist_name'  =>  $value,
                    ];
                }
//                return response()->json($checklistDetails);
                $checklist = ChecklistDetailes::insert($checklistDetails);
                $output     = $this->store_message($checklist);
                DB::commit();
            }catch(Exception $e){
                DB::rollBack();
                $output = ['status' => 'error','message' => $e->getMessage()];
            }
        }else{
            $output = $this->unauthorized();
        }
        return redirect()->route('checklist')->with($output);
    }

    public function delete(Request $request){
        if($request->ajax()){
            if(permission('checklist-delete')){
                ChecklistDetailes::where('checklist_id',$request->id)->delete();
                $result  = $this->model->where('id',$request->id)->delete();
                $output  = $this->delete_message($result);
            }else{
                $output  = $this->unauthorized();
            }
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }
    }

    public function bulk_delete(Request $request){
        if($request->ajax()){
            if(permission('checklist-bulk-delete')){
                $result   = $this->model->destroy($request->ids);
                $output   = $this->bulk_delete_message($result);
            }else{
                $output   = $this->unauthorized();
            }
            return response()->json($output);
        }else{
            return response()->json($this->unauthorized());
        }
    }

    public function change_status(Request $request)
    {
        if ($request->ajax()) {
//            return response()->json($request->all());
                $data = [
                    'status' => $request->status,
                ];
            $result   = $this->model->find($request->status_id)->update($data);

            $output = $this->status_message($result, $request->status_id);
            return response()->json($output);
        }
    }


}
