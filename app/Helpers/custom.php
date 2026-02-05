<?php
//IMAGE FOLDER PATH
define('LOGO_PATH','logo/');
define('USER_PHOTO_PATH','user/');
define('CUSTOMER_PHOTO_PATH','/');
define('MATERIAL_IMAGE_PATH','material/');
define('PRODUCT_IMAGE_PATH','product/');
define('EMPLOYEE_NID_PHOTO','employee/');
define('EMPLOYEE_IMAGE_PATH','employee/');
define('SALESMEN_AVATAR_PATH','salesmen/');
define('ASM_AVATAR_PATH','asm/');
define('CUSTOMER_AVATAR_PATH','customer/');
define('PURCHASE_DOCUMENT_PATH','purchase-document/');
define('SALE_DOCUMENT_PATH','sale-document/');
define('TRANSFER_DOCUMENT_PATH','transfer-document/');
define('ASM_BASE_PATH','http://kohinoor-asm.test/');

define('BANK_TYPE',[
    '1' => '<span class="label label-success label-pill label-inline" style="min-width:70px !important;">Bank</span>',
    '2' => '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;">Mobile Bank</span>',
    '4' => '<span class="label label-primary label-pill label-inline" style="min-width:70px !important;">Cash</span>',
]);

define('GENDER_LABEL',[
    '1' => '<span class="label label-primary label-pill label-inline" style="min-width:70px !important;">Male</span>',
    '2' => '<span class="label label-info label-pill label-inline" style="min-width:70px !important;">Female</span>',
    '3' => '<span class="label label-warning label-pill label-inline" style="min-width:70px !important;">Other</span>',

]);

define('EMPLOYEE_LABEL',[
    '1' => '<span class="label label-primary label-pill label-inline" style="min-width:70px !important;">Officer</span>',
    '2' => '<span class="label label-info label-pill label-inline" style="min-width:70px !important;">Supervisor</span>',
    '3' => '<span class="label label-warning label-pill label-inline" style="min-width:70px !important;">Executive</span>',
    '4' => '<span class="label label-success label-pill label-inline" style="min-width:70px !important;">Peon</span>',
    '5' => '<span class="label label-success label-pill label-inline" style="min-width:70px !important;">Others</span>',
]);
define('TICKET_LABEL',[
    '0' => '<span class="label label-primary label-pill label-inline" style="min-width:70px !important;">Cancel</span>',
    '1' => '<span class="label label-info label-pill label-inline" style="min-width:70px !important;">Pending</span>',
    '2' => '<span class="label label-warning label-pill label-inline" style="min-width:70px !important;">Not Accept</span>',
    '3' => '<span class="label label-success label-pill label-inline" style="min-width:70px !important;"> Accepted</span>',
]);
define('TYPE_EMPLOYEE',[
    '1' => 'Officer',
    '2' => 'Supervisor',
    '3' => 'Executive',
    '4' => 'Others',
]);
define('TYPE_CUSTOMER',[
    '1' => 'General',
    '2' => 'Travel Agent',
    '3' => 'Corporate',
]);
define('STATUS',['1' => 'Active', '2' => 'Inactive']);
define('MATERIAL_TYPE',['1' => 'Raw', '2' => 'Packaging']);
define('PRODUCT_TYPE',['1' => 'Can', '2' => 'Foil']);
define('APPROVE_STATUS',['1' => 'Approved', '2' => 'Pending','3'=>'Cancelled']);
define('EXPENSE_APPROVE_STATUS',['1' => 'Approved', '2' => 'Rejected','3'=>'Pending']);
define('TAX_METHOD',['1' => 'Exclusive','2' => 'Inclusive']);
define('TYPE',['1'=>'Standard','2'=>'Variant']);
define('VOUCHER_APPROVE_STATUS',[
    '1' => 'Approved',
    '2' => 'Rejected',
    '3' => 'Pending',
]);
define('TYPE_LABEL',[
    '1'=>'<span class="label label-success label-pill label-inline" style="min-width:70px !important;font-size:12px">Standard</span>',
    '2'=>'<span class="label label-primary label-pill label-inline" style="min-width:70px !important;font-size:12px">Variant</span>',
]);
define('APPLICATION_TYPE',[
    '1'=>'<span class="label label-success label-pill label-inline" style="min-width:70px !important;font-size:12px">General</span>',
    '2'=>'<span class="label label-primary label-pill label-inline" style="min-width:70px !important;font-size:10px">Travel Agent</span>',
    '3'=>'<span class="label label-primary label-pill label-inline" style="min-width:70px !important;font-size:12px">Corporates</span>',
]);
define('APPLICATION_STATUS',[
    '1'=>'<span class="label label-success label-pill label-inline" style="min-width:70px !important;font-size:12px;padding: 19px 0px;text-align: center;">Walkin App</span>',
    '2'=>'<span class="label label-danger label-pill label-inline" style="min-width:70px !important;font-size:10px;padding: 19px 0px;text-align: center;">Hold App</span>',
    '3'=>'<span class="label label-danger label-pill label-inline" style="min-width:70px !important;font-size:10px;padding: 19px 0px;text-align: center;">Online App</span>',
    '4'=>'<span class="label label-danger label-pill label-inline" style="min-width:70px !important;font-size:10px;padding: 19px 0px;text-align: center;">Online Hold App</span>',
]);
define('WALKIN_APP_TYPE', [
    '0' => '<span class="label label-default label-pill label-inline" style="min-width:70px !important;font-size:12px;padding: 19px 0px;text-align: center;">Not Specified</span>',
    '1' => '<span class="label label-success label-pill label-inline" style="min-width:70px !important;font-size:12px;padding: 19px 0px;text-align: center;">General</span>',
    '2' => '<span class="label label-primary label-pill label-inline" style="min-width:70px !important;font-size:12px;padding: 19px 0px;text-align: center;">Travel Agent</span>',
    '3' => '<span class="label label-info label-pill label-inline" style="min-width:70px !important;font-size:12px;padding: 19px 0px;text-align: center;">Corporates</span>',
    null => '<span class="label label-default label-pill label-inline" style="min-width:70px !important;font-size:12px;padding: 19px 0px;text-align: center;">Not Specified</span>',
]);

define('WALKIN_APP_STATUS',[
    '1'=>'<span class="label label-primary label-pill label-inline" style="width:100% !important;font-size:12px;padding: 19px 0px;text-align: center;">Application Received</span>',
    '2'=>'<span class="label label-info label-pill label-inline" style="min-width:70px !important;font-size:12px;padding: 19px 0px;text-align: center;">Application in Process</span>',
    '3'=> '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;font-size:12pxfont-size:12px;padding: 19px 0px;text-align: center;">Required Missing Documents</span>',
    '4'=> '<span class="label label-success label-pill label-inline" style="min-width:70px !important;font-size:12pxfont-size:12px;padding: 19px 0px;text-align: center;">Submitted to embassy</span>',
    '5'=> '<span class="label label-success label-pill label-inline" style="min-width:70px !important;font-size:12pxfont-size:12px;padding: 19px 0px;text-align: center;">Ready for delivery</span>',
    '6'=> '<span class="label label-success label-pill label-inline" style="min-width:70px !important;font-size:12pxfont-size:12px;padding: 19px 0px;text-align: center;">Delivered</span>',
    '7'=> '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;font-size:12pxfont-size:12px;padding: 19px 0px;text-align: center;">Cancel</span>',
]);
define('WALKIN_APP_STATUS_VALUE',[
    '1'=>'Application Received',
    '2'=>'Application on Process',
    '3'=> 'Required Missing Documents',
    '4'=> 'Submitted to embassy',
    '5'=> 'Ready for delivery',
    '6'=> 'Delivered',
    '7'=> 'Cancel',
]);
define('WALKIN_APP_PP_RFD_VALUE',[
    '1'=>'Ready for Delivery Passport No',
    '0'=>'Not Ready For Delivery',
]);
define('WALKIN_APP_LOG_STATUS_VALUE',[
    '1'=>'Save Application',
    '2'=>'Hold Application',
]);
define('WALKIN_APP_PP_D_VALUE',[
    '1'=>'Delivered Passport No',
]);
define('CUSTOMER_STATUS_VALUE',[
    '1'=>'<span class="label label-success label-pill label-inline" style="min-width:70px !important;font-size:12px">Active</span>',
    '2'=>'<span class="label label-danger label-pill label-inline" style="min-width:70px !important;font-size:12px">Inactive</span>',
]);
define('CHICKLIST_STATUS_VALUE',[
    '1'=>'<span class="label label-success label-pill label-inline" style="min-width:70px !important;font-size:12px">Active</span>',
    '2'=>'<span class="label label-danger label-pill label-inline" style="min-width:70px !important;font-size:12px">Inactive</span>',
]);
define('CUSTOMER_STATUS_VALUES',[
    '1'=>'Active',
    '2'=>'Inactive',
]);
define('WALKIN_APP_PAYMENT',[
    '3'=>'<span class="label label-primary label-pill label-inline" style="min-width:70px !important;font-size:12px">Partial</span>',
    '2'=>'<span class="label label-success label-pill label-inline" style="min-width:70px !important;font-size:12px">Full</span>',
    '1'=>'<span class="label label-danger label-pill label-inline" style="min-width:70px !important;font-size:12px">Due</span>',
]);
define('WALKIN_APP_PAYMENT_STATUS',[
    '3'=>'Partial',
//    '2'=>'Paid',
    '2'=>'Received',
    '1'=>'Due',
]);

define('MATERIAL_TYPE_LABEL',[
    '1'=>'<span class="label label-success label-pill label-inline" style="min-width:70px !important;">Raw</span>',
    '2'=>'<span class="label label-primary label-pill label-inline" style="min-width:70px !important;">Packaging</span>',
]);
define('PRODUCT_TYPE_LABEL',[
    '1'=>'<span class="label label-success label-pill label-inline" style="min-width:70px !important;">Can</span>',
    '2'=>'<span class="label label-primary label-pill label-inline" style="min-width:70px !important;">Foil</span>',
]);
define('STATUS_LABEL',[
    '1' => '<span class="label label-success label-pill label-inline" style="min-width:70px !important;">Active</span>',
    '2' => '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;">Inactive</span>',
]);
define('PRE_POST_LABEL',[
    '1' => '<span class="label label-success label-pill label-inline" style="min-width:70px !important;">Pre</span>',
    '2' => '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;">Post</span>',
]);
define('PAID_UNPAID_LABEL',[
    '1' => '<span class="label label-success label-pill label-inline" style="min-width:70px !important;">Unpaid</span>',
    '2' => '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;">Paid</span>',
]);
define('ALLOWANCE_DEDUCTION_LABEL',[
    '1' => '<span class="label label-success label-pill label-inline" style="min-width:70px !important;">Allowance</span>',
    '2' => '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;">Deduction</span>',
    '3' => '<span class="label label-info label-pill label-inline" style="min-width:70px !important;">Others</span>',
]);
define('EXPENSE_APPROVE_STATUS_LABEL',[
    '1' => '<span class="label label-success label-pill label-inline" style="min-width:70px !important;">Approved</span>',
    '2' => '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;">Rejected</span>',
    '3' => '<span class="label label-info label-pill label-inline" style="min-width:70px !important;">Pending</span>',
]);
define('VOUCHER_APPROVE_STATUS_LABEL',[
    '1' => '<span class="label label-success label-pill label-inline" style="min-width:70px !important;">Approved</span>',
    '2' => '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;">Rejected</span>',
    '3' => '<span class="label label-info label-pill label-inline" style="min-width:70px !important;">Pending</span>',
]);
define('DAY_NIGHT_LABEL',[
    '1' => '<span class="label label-success label-pill label-inline" style="min-width:70px !important;">Day</span>',
    '2' => '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;">Night</span>',
]);
define('SUPPLIER_TYPE_LABEL',[
    '1' => '<span class="label label-primary label-pill label-inline" style="min-width:70px !important;">Material</span>',
    '2' => '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;">Machine</span>',
]);

define('APPROVE_STATUS_LABEL',[
    '1' => '<span class="label label-success label-pill label-inline" style="min-width:70px !important;">Approved</span>',
    '2' => '<span class="label label-info label-pill label-inline" style="min-width:70px !important;">Pending</span>',
    '3' => '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;">Cancelled</span>',
]);
define('STOCK_STATUS_LABEL',[
    '1' => '<span class="label label-success label-pill label-inline" style="min-width:70px !important;">Available</span>',
    '2' => '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;">Out of Stock</span>',
]);
define('DELETABLE_LABEL',[
    '1' => '<span class="label label-danger label-pill label-inline" style="min-width:70px !important;">No</span>',
    '2' => '<span class="label label-success label-pill label-inline" style="min-width:70px !important;">Yes</span>',
]);
define('BARCODE_SYMBOL',([
    "C128"  => "Code 128",
    "C39"   => "Code 39",
    "UPCA"  => "UPC-A",
    "UPCE"  => "UPC-E",
    "EAN8"  => "EAN-8",
    "EAN13" => "EAN-13"
]));
define('ASSET_STATUS',([
    "1" => "Good",
    "2" => "Broken",
    "3" => "Recycled",
    "4" => "On Service",
    "5" => "Archived",
]));

define('PRODUCTION_STATUS',['1'=>'Pending', '2'=>'Processing','3'=>'Finished']);
define('TRANSFER_STATUS',['1'=>'Pending', '2'=>'Complete']);
define('PRODUCTION_STATUS_LABEL',[
    '1'=>'<span class="label label-danger label-pill label-inline" style="min-width:70px !important;">Pending</span>',
    '2'=>'<span class="label label-primary label-pill label-inline" style="min-width:70px !important;">Processing</span>',
    '3'=>'<span class="label label-success label-pill label-inline" style="min-width:70px !important;">Finished</span>',
]);
define('TRANSFER_STATUS_LABEL',[
    '1'=>'<span class="label label-danger label-pill label-inline" style="min-width:70px !important;">Pending</span>',
    '2'=>'<span class="label label-success label-pill label-inline" style="min-width:70px !important;">Complete</span>',
]);


define('DAYS',['1'=>'Saturday','2'=>'Sunday','3'=>'Monday','4'=>'Tuesday','5'=>'Wednesday','6'=>'Thursday']);

define('PURCHASE_STATUS',['1'=>'Received','2'=>'Partial','3'=>'Pending','4'=>'Ordered']);
define('PURCHASE_STATUS_LABEL',[
    '1'=>'<span class="label label-success label-pill label-inline" style="min-width:70px !important;">Received</span>',
    '2'=>'<span class="label label-warning label-pill label-inline" style="min-width:70px !important;">Partial</span>',
    '3'=>'<span class="label label-danger label-pill label-inline" style="min-width:70px !important;">Pending</span>',
    '4'=>'<span class="label label-info label-pill label-inline" style="min-width:70px !important;">Ordered</span>',
]);

define('PAYMENT_STATUS',['1'=>'Paid','2'=>'Partial','3'=>'Due']);
define('PAYMENT_STATUS_LABEL',[
    '1'=>'<span class="label label-success label-pill label-inline" style="min-width:70px !important;">Paid</span>',
    '2'=>'<span class="label label-info label-pill label-inline" style="min-width:70px !important;">Partial</span>',
    '3'=>'<span class="label label-danger label-pill label-inline" style="min-width:70px !important;">Due</span>',
]);
define('PERSONAL_LOAN_TYPE',['1'=>'Short Term','2'=>'Long Term']);
define('PAYMENT_METHOD',['1'=>'Cash','2'=>'Cheque','3'=>'Mobile Bank']);
define('SALE_PAYMENT_METHOD',['1'=>'Cash','2'=>'Bank','3'=>'Mobile Bank']);
define('DELIVERY_STATUS',['1'=>'Pending','2'=>'Delivered']);
define('MAIL_MAILER',(['smtp','sendmail','mail']));
define('MAIL_ENCRYPTION',(['none' => 'null','tls' => 'tls','ssl' => 'ssl']));

//Employee Form Constant
define('JOB_STATUS',['1'=>'Permanent','2'=>'Probation','3'=>'Resigned','4'=>'Suspended']);
define('DUTY_TYPE',['1'=>'Full Time','2'=>'Part Time','3'=>'Contractual','4'=>'Other']);
define('RATE_TYPE',['1'=>'Hourly','2'=>'Salary']);
define('PAY_FREQUENCY',['1'=>'Weekly','2'=>'Biweekly','3'=>'Monthly','4'=>'Annual']);
define('GENDER',['1'=>'Male','2'=>'Female','3'=>'Other']);
define('MARITAL_STATUS',['1'=>'Single','2'=>'Married','3'=>'Divorced','4'=>'Widowed','5'=>'Other']);
define('BLOOD_GROUP',['1'=>'A+','2'=>'B+','3'=>'A-','4'=>'B-','5'=>'AB+','6'=>'AB-','7'=>'O+','8'=>'O-']);
define('IS_SUPERVISOR',['1'=>'Yes','2'=>'No']);
define('OVERTIME',['1'=>'Allowed','2'=>'Not Allowed']);
define('RESIDENTIAL_STATUS',['1'=>'Resident','2'=>'Non Resident']);

if (!function_exists('permission')) {

    function permission(string $value){
        if (collect(\Illuminate\Support\Facades\Session::get('user_permission'))->contains($value)) {
            return true;
        }
        return false;
    }
}

if (!function_exists('change_status')) {

    function change_status(int $id,int $status,string $name){
        if($id && $status && $name){
            return $status == 1 ? '<span class="label label-success label-pill label-inline change_status" data-id="' . $id . '" data-name="' . $name . '" data-status="2" style="min-width:70px !important;cursor:pointer;">Active</span>'
            : '<span class="label label-danger label-pill label-inline change_status" data-id="' . $id . '" data-name="' . $name . '" data-status="1"  style="min-width:70px !important;cursor:pointer;">Inactive</span>';
        }
    }
}

if (!function_exists('action_button')) {

    function action_button($action){
        return '<div class="dropdown">
                    <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-th-list text-white"></i>
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                    ' . $action . '
                    </div>
                </div>';
    }
}

if (!function_exists('row_checkbox')) {

    function row_checkbox($id){
        return '<div class="custom-control custom-checkbox">
                    <input type="checkbox" value="'.$id.'"
                    class="custom-control-input select_data" onchange="select_single_item()" id="checkbox'.$id.'">
                    <label class="custom-control-label" for="checkbox'.$id.'"></label>
                </div>';
    }
}

if (!function_exists('read_more')) {

    function read_more($text, $limit = 400){
        $text = $text." ";
        $text = substr($text, 0, $limit);
        $text = substr($text, 0, strrpos($text, ' '));
        $text = $text."...";
        return $text;
    }
}

if(!function_exists('generator'))
{
    function generator($lenth)
    {
        $number=array("A","B","C","D","E","F","G","H","I","J","K","L","N","M","O","P","Q","R","S","U","V","T","W","X","Y","Z","1","2","3","4","5","6","7","8","9","0");

        for($i=0; $i<$lenth; $i++)
        {
            $rand_value=rand(0,34);
            $rand_number=$number["$rand_value"];

            if(empty($con))
            {
            $con=$rand_number;
            }
            else
            {
            $con="$con"."$rand_number";}
        }
        return $con;
    }
}
if (!function_exists('numberTowords')) {
    function numberTowords($num)
    {

        $ones = array(
            0 => "Zero",
            1 => "One",
            2 => "Two",
            3 => "Three",
            4 => "Four",
            5 => "Five",
            6 => "Six",
            7 => "Seven",
            8 => "Eight",
            9 => "Nine",
            10 => "Ten",
            11 => "Eleven",
            12 => "Twelve",
            13 => "Thirteen",
            14 => "Fourteen",
            15 => "Fifteen",
            16 => "Sixteen",
            17 => "Seventeen",
            18 => "Eighteen",
            19 => "Nineteen",
        );
        $tens = array(
            0 => "Zero",
            1 => "Ten",
            2 => "Twenty",
            3 => "Thirty",
            4 => "Forty",
            5 => "Fifty",
            6 => "Sixty",
            7 => "Seventy",
            8 => "Eighty",
            9 => "Ninety",
        );
        $hundreds = array(
            "Hundred",
            "Thousand",
            "Million",
            "Billion",
            "Trillion",
            "Quardrillion",
        ); /*limit t quadrillion */
        $num = number_format($num, 2, ".", ",");
        $num_arr = explode(".", $num);
        $wholenum = $num_arr[0];
        $decnum = $num_arr[1];
        $whole_arr = array_reverse(explode(",", $wholenum));
        krsort($whole_arr, 1);
        $rettxt = "";
        foreach ($whole_arr as $key => $i) {

            while (substr($i, 0, 1) == "0") {
                $i = substr($i, 1, 5);
            }

            if ($i < 20) {
                if(array_key_exists($i,$ones)){
                $rettxt .= $ones[$i];
                }
            } elseif ($i < 100) {
                if (substr($i, 0, 1) != "0") {
                    $rettxt .= $tens[substr($i, 0, 1)];
                }

                if (substr($i, 1, 1) != "0") {
                    $rettxt .= " " . $ones[substr($i, 1, 1)];
                }

            } else {
                if (substr($i, 0, 1) != "0") {
                    $rettxt .= $ones[substr($i, 0, 1)] . " " . $hundreds[0];
                }

                if (substr($i, 1, 1) != "0") {
                    $rettxt .= " " . $tens[substr($i, 1, 1)];
                }

                if (substr($i, 2, 1) != "0") {
                    $rettxt .= " " . $ones[substr($i, 2, 1)];
                }

            }
            if ($key > 0) {
                $rettxt .= " " . $hundreds[$key] . " ";
            }

        }

        if ($decnum > 0) {
            $rettxt .= " AND ";
            if ($decnum < 20) {
                $rettxt .= $ones[$decnum];
            } elseif ($decnum < 100) {
                $rettxt .= $tens[substr($decnum, 0, 1)];
                $rettxt .= " " . $ones[substr($decnum, 1, 1)];
            }
        }
        return $rettxt;
    }

}


