<?php

namespace Modules\Bank\Http\Controllers;

use App\Http\Controllers\BaseController;
use Exception;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Modules\Account\Entities\ChartOfAccount;
use Modules\Bank\Entities\Bank;
use Modules\Bank\Entities\ManageBank;
use Modules\Bank\Http\Requests\AccountFormRequest;
use Modules\Bank\Http\Requests\GiftCardTypeFormRequest;
use Modules\CashInHand\Entities\CashInHand;
use Modules\MobileBank\Entities\MobileBank;
use Modules\Setting\Entities\Showroom;

class ManageBankController extends BaseController{
    public function __construct(ManageBank $model){
        $this->model = $model;
    }
    private const CASH_AT_CONDITION = 'Cash At Condition';
    public function allBank(){
        if(permission('bank-access')){
            $this->setPageData('Manage All Account List','Manage All Account List','fas fa-university',[['name' => 'Manage All Account List']]);
            return view('bank::all-banks.index');
        }else{
            return $this->access_blocked();
        }
    }

    public function store_or_update_data(AccountFormRequest $request){
        if($request->ajax()){
            if(permission('bank-add') || permission('bank-edit')){
                DB::beginTransaction();
                try {
                        $collection = collect($request->all());
                        $collection = $this->track_data($collection,$request->update_id);
                        $result     = $this->model->updateOrCreate(['id'=>$request->update_id],$collection->all());
                        $output     = $this->store_message($result, $request->update_id);
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
                    $bankData = Bank::selectRaw('SUM(balance) as amount')
                        ->where('account_id', $value->id)
                        ->groupBy('account_id')
                        ->first();
                    $manageBankData = MobileBank::selectRaw('SUM(balance) as amount')
                        ->where('account_id', $value->id)
                        ->groupBy('account_id')
                        ->first();
                    $cashInHandData = CashInHand::selectRaw('SUM(balance) as amount')
                        ->where('account_id', $value->id)
                        ->groupBy('account_id')
                        ->first();
                    if (!empty($bankData)){
                        $bank_amount = $bankData->amount;
                    }elseif (!empty($manageBankData)){
                        $bank_amount = $manageBankData->amount;
                    }elseif (!empty($cashInHandData)){
                        $bank_amount = $cashInHandData->amount;
                    }else{
                        $bank_amount = 0;
                    }


                    $no++;
                    $action = '';
                    if(permission('bank-edit')){
                        $action .= ' <a class="dropdown-item edit_data" data-id="' . $value->id . '">'.self::ACTION_BUTTON['Edit'].'</a>';
                    }

                    $row = [];
                    $row[] = $no;
                    $row[] = BANK_TYPE[$value->bank_type];
                    $row[] = $value->bank_name;
                    $row[] = $value->account_name;
                    $row[] = $value->account_number;
                    $row[] = $bank_amount;
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

    public function manageEdit(Request $request){
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


}
