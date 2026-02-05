<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

class BaseController extends Controller
{
    protected $model;
    protected const DELETABLE = ['1'=>'No','2'=>'Yes'];
    protected const ACTION_BUTTON = [
        'Edit'                           => '<i class="fas fa-edit text-primary mr-2"></i> Edit',
        'View'                           => '<i class="fas fa-eye text-warning mr-2"></i> View',
        'Delete'                         => '<i class="fas fa-trash text-danger mr-2"></i> Delete'
    ];
    public function actionButton($key){
        $button = [
            'Generate Barcode'              => '<i class="fas fa-barcode text-primary mr-2"></i>'.__('Generate Barcode'),
            'Assign Collector'              => '<i class="fas fa-edit text-primary mr-2"></i>'.__('Assign Collector'),
            'Edit'                          => '<i class="fas fa-edit text-primary mr-2"></i>'.__('Edit'),
            'Duplicate'                          => '<i class="fas fa-edit text-primary mr-2"></i>'.__('Duplicate'),
            'Ready for delivery'            => '<i class="fas fa-edit text-info mr-2"></i>'.__('Ready for delivery'),
            'Edit Executive'                => '<i class="fas fa-edit text-primary mr-2"></i>'.__('Assign Executive'),
            'View'                          => '<i class="fas fa-eye text-warning mr-2"></i>'.__('View'),
            'Barcode Show'                  => '<i class="fas fa-eye text-warning mr-2"></i>'.__('Barcode Show'),
            'Details'                       => '<i class="fas fa-newspaper text-primary mr-2"></i>'.__('Details'),
            'Delete'                        => '<i class="fas fa-trash text-danger mr-2"></i>'.__('Delete'),
            'Change Status'                 => '<i class="fas fa-check-circle text-success mr-2"></i>'.__('Change Status'),
            'Received'                      => '<i class="fas fa-check-circle text-success mr-2"></i>'.__('file.Received'),
            'Update Payment'                => '<i class="fas fa-plus-square text-info mr-2"></i>'.__('Update Payment'),
            'Add Payment'                   => '<i class="fas fa-plus-square text-info mr-2"></i>'.__('Add Payment'),
            'Money Receipt'                 => '<i class="fas fa-file-invoice-dollar text-dark mr-2"></i>'.__('Print Money Receipt'),
            'Po Invoice'                    => '<i class="fas fa-file-invoice-dollar text-dark mr-2"></i>'.__('P.O Invoice Upload'),
            'Finish Good'                   => '<i class="fas fa-boxes text-info mr-2"></i>'.__('Accept'),
            'Production Product'            => '<i class="fas fa-boxes text-info mr-2"></i>'.__('file.Production Product'),
            'Update Delivery'               => '<i class="fas fa-truck text-info mr-2"></i>'.__('file.Update Delivery'),
            'Report'                        => '<i class="fas fa-file-invoice text-info mr-2"></i>'.__('file.Report'),
            'Add Delivery'                  => '<i class="fas fa-truck text-info mr-2"></i>'.__('file.Add Delivery'),
            'Purchase Invoice'              => '<i class="fas fa-file-invoice text-info mr-2"></i>'.__('file.Purchase Invoice'),
            'Received Invoice'              => '<i class="fas fa-receipt text-info mr-2"></i>'.__('file.Received Invoice'),
            'Save'                          => __('file.Save'),
            'Generate Slip'                 => '<i class="fas fa-file-invoice-dollar text-dark mr-2"></i>'.__('file.Generate Slip'),
            'Builder'                       => '<i class="fas fa-th-list text-success mr-2"></i>'.__('file.Builder').__('file.Builder'),
            'Summary'                       => '<i class="fas fa-newspaper text-primary mr-2"></i>'.__('file.Summary'),
        ];
        return $button[$key];
    }
    public function responseMessage($response){
        $message = [
            'Barcode Create'                => __('Barcode Has Been Generated Successfully'),
            'Request Accept'                => __('Request Has Been Accepted Successfully'),
            'Request Assign'                => __('Assign Executive Successfully'),
            'Collector Assign'              => __('Assign Collector Successfully'),
            'Data Saved'                    => __('Data Has Been Saved Successfully'),
            'Data Update'                   => __('Data Has Been Updated Successfully'),
            'Failed Save'                   => __('Failed To Save Data'),
            'Failed Update'                 => __('Failed To Update Data'),
            'Data Delete'                   => __('Data Has Been Delete Successfully'),
            'Data Delete Failed'            => __('Failed To Delete Data'),
            'Select Data Delete'            => __('Selected Data Has Been Delete Successfully'),
            'Select Data Delete Failed'     => __('Failed To Delete Selected Data'),
            'Unauthorized Blocked'          => __('Unauthorized Access Blocked!'),
            'No Data'                       => __('No data found'),
            'Status Changed'                => __('Status Has Been Changed Successfully'),
            'Status Changed Failed'         => __('Failed To Change Status'),
            'Hold'                          => __('Data Hold Successfully'),
            'Hold Failed'                   => __('Failed to Hold Purchase Data'),
            'Select Status'                 => __('Please select status'),
            'Approval Status'               => __('Approval Status Changed Successfully'),
            'Approval Status Failed'        => __('Failed To Change Approval Status'),
            'Unauthorized'                  => __('Unauthorized Access Blocked!'),
            'Related Data'                  => __('This data cannot delete because it is related with others data.'),
            'Associated Data'               => __('can\'t delete because they are associated with others data.'),
            'Expected Menu'                 => __('Except these menus'),
            'Associated Other Data'         => __('because they are associated with others data.'),
            'Customer'                      => __('These customers'),
            'Payment Data'                  => __('Payment Data Saved Successfully'),
            'Payment Data Delete'           => __('Failed to Save Payment Data'),
            'Account Deleted Transaction'   => __('This account cannot delete because it is related with many transactions.'),
            'Selected Data Delete'          => __('Selected Data Has Been Deleted Successfully.'),
            'Expected Role'                 => __('Except these roles'),
            'Roles'                         => __('These roles'),
            'Except'                        => __('Except these'),
            'Current Password'              => __('Current password does not match!'),
            'Changed Password'              => __('Password changed successfully'),
            'Failed Password'               => __('Failed to change password. Try Again!'),
            'Warehouse Choose'              => __('Please Choose An Warehouse')
        ];
        return $message[$response];
    }
    protected function setPageData(string $page_title, string $sub_title=null, string $page_icon=null, $breadcrumb=null){
        view()->share(['page_title' => $page_title, 'sub_title' => $sub_title ?? $page_title, 'page_icon' => $page_icon, 'breadcrumb' => $breadcrumb]);
    }
    protected function table_image($path,$image,$alt_text,$gender=null){
        if(!empty($path) && !empty($image) && !empty($alt_text))
        {
            return "<img src='".asset("storage/".$path.$image)."' alt='".$alt_text."' style='width:50px;'/>";
        }else{
            if($gender){
                return "<img src='".asset("images/".($gender == 1 ? 'male' : 'female').".svg")."' alt='Default Image' style='width:50px;'/>";
            }else{
                return "<img src='".asset("images/default.svg")."' alt='Default Image' style='width:50px;'/>";
            }

        }
    }
    protected function set_datatable_default_properties(Request $request){
        $this->model->setOrderValue($request->input('order.0.column'));
        $this->model->setDirValue($request->input('order.0.dir'));
        $this->model->setLengthValue($request->input('length'));
        $this->model->setStartValue($request->input('start'));
    }
    protected function showErrorPage($errorCode = 404, $message = null){
        $data['message'] = $message;
        return response()->view('errors.'.$errorCode, $data, $errorCode);
    }
    protected function response_json($status='success',$message=null,$data=null,$response_code=200){
        return response()->json([
            'status'        => $status,
            'message'       => $message,
            'data'          => $data,
            'response_code' => $response_code,
        ]);
    }
    protected function datatable_draw($draw, $recordsTotal, $recordsFiltered, $data){
        return array(
            "draw" => $draw,
            "recordsTotal" => $recordsTotal,
            "recordsFiltered" => $recordsFiltered,
            "data" => $data,
        );
    }
    protected function store_message($result, $update_id = null){
        return $result ? ['status' => 'success','message' => !empty($update_id) ? $this->responseMessage('Data Update') : $this->responseMessage('Data Saved')]
            : ['status' => 'error','message' => !empty($update_id) ? $this->responseMessage('Failed Update') : $this->responseMessage('Failed Save')];
    }
    protected function barcode_message($result, $update_id = null){
        return $result ? ['status' => 'success','message' => !empty($update_id) ? $this->responseMessage('Barcode Generated') : $this->responseMessage('Barcode Create')]
            : ['status' => 'error','message' => !empty($update_id) ? $this->responseMessage('Failed Accept') : $this->responseMessage('Failed Save')];
    }
    protected function status_message($result, $update_id = null){
        return $result ? ['status' => 'success','message' => !empty($update_id) ? $this->responseMessage('Data Update') : $this->responseMessage('Status Changed')]
            : ['status' => 'error','message' => !empty($update_id) ? $this->responseMessage('Failed Accept') : $this->responseMessage('Status Changed Failed')];
    }
    protected function assign_message($result, $update_id = null){
        return $result ? ['status' => 'success','message' => !empty($update_id) ? $this->responseMessage('Data Update') : $this->responseMessage('Request Assign')]
            : ['status' => 'error','message' => !empty($update_id) ? $this->responseMessage('Failed Accept') : $this->responseMessage('Failed Save')];
    }
    protected function assign_collector_message($result, $update_id = null){
        return $result ? ['status' => 'success','message' => !empty($update_id) ? $this->responseMessage('Data Update') : $this->responseMessage('Collector Assign')]
            : ['status' => 'error','message' => !empty($update_id) ? $this->responseMessage('Failed Accept') : $this->responseMessage('Failed Save')];
    }
    protected function Fstore_message($result, $update_id = null){
        return $result ? ['status' => 'success','message' => !empty($update_id) ? $this->responseMessage('Data Update') : $this->responseMessage('Data Saved')]
            : ['status' => 'error','message' => !empty($update_id) ? $this->responseMessage('Failed Update') : $this->responseMessage('Failed Save')];
    }
    protected function delete_message($result){
        return $result ? ['status' => 'success','message' => $this->responseMessage('Data Delete')]
            : ['status' => 'error','message' => $this->responseMessage('Data Delete Failed')];
    }
    protected function bulk_delete_message($result){
        return $result ? ['status' => 'success','message' => $this->responseMessage('Select Data Delete')]
            : ['status' => 'error','message' => $this->responseMessage('Select Data Delete Failed')];
    }
    protected function unauthorized(){
        return ['status'=>'error','message' => $this->responseMessage('Unauthorized Blocked')];
    }
    protected function data_message($data){
        return $data ? $data : ['status' => 'error','message' => $this->responseMessage('No Data')];
    }
    protected function access_blocked(){
        return redirect('unauthorized')->with(['status'=>'error','message' => $this->responseMessage('Unauthorized Blocked')]);
    }
    protected function track_data($collection, $update_id=null){
        $created_by   = $modified_by = auth()->user()->name;
        $created_at   = $updated_at  = Carbon::now();
        return $update_id ? $collection->merge(compact('modified_by','updated_at')) : $collection->merge(compact('created_by','created_at'));
    }
    protected function coa_head_code(string $head_name){
        switch (strtolower(str_replace('-','_',$head_name))) {
            case 'assets':
                return 1;
                break;
            case 'non_current_asset':
                return 1001;
                break;
            case 'inventory':
                return 100101;
                break;
            case 'current_asset':
                return 1002;
                break;
            case 'cash_&_cash_equivalent':
                return 100201;
                break;
            case 'cash_in_hand':
                return 10020101;
                break;
            case 'cash_at_bank':
                return 10020102;
                break;
            case 'cash_at_mobile_bank':
                return 10020103;
                break;
            case 'account_receivable':
                return 100202;
                break;
            case 'customer_receivable':
                return 100202010001;
                break;
            case '1_walking_customer':
                return 100202010001;
                break;
            case 'loan_receivable':
                return 10020202;
                break;
            case 'equity':
                return 2;
                break;
            case 'income':
                return 3;
                break;
            case 'product_sale':
                return 3001;
                break;
            case 'service_income':
                return 3002;
                break;
            case 'expense':
                return 4;
                break;
            case 'default_expense':
                return 4001;
                break;
            case 'material_purchase':
                return 4002;
                break;
            case 'employee_salary':
                return 4003;
                break;
            case 'machine_purchase':
                return 4004;
                break;
            case 'maintenance_service':
                return 4005;
                break;
            case 'liabilities':
                return 5;
                break;
            case 'non_current_liabilities':
                return 5001;
                break;
            case 'current_liabilities':
                return 5002;
                break;
            case 'account_payable':
                return 500201;
                break;
            case 'default_supplier':
                return 50020101;
                break;
            case 'employee_ledger':
                return 500202;
                break;
            case 'tax':
                return 500203;
                break;
        }
    }
}
