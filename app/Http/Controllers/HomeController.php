<?php

namespace App\Http\Controllers;


use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\BaseController;
use Modules\WalkIn\Entities\WalkinAppInfo;


class HomeController extends BaseController
{
    public function index()
    {
        if (permission('dashboard-access')) {

            $this->setPageData('Dashboard', 'Dashboard', 'fas fa-technometer');
            $collection        = WalkinAppInfo::select('id', 'paid_amount')->where('paid_amount', '>', 0)->where('status', '!=', 3)->sum('paid_amount');
            $dueInfo           = WalkinAppInfo::select('id', 'visaType_id', 'visa_category')->where('payment_status', '!=', 2)->where('walkin_app_infos.status', '!=', 3)
                ->join('checklists', 'checklists.id', '=', 'walkin_app_infos.visa_category')
                ->sum('checklists.price');
            $dueDetails       = WalkinAppInfo::select('id', 'visaType_id', 'visa_category')->where('payment_status', '!=', 2)->where('walkin_app_infos.status', '!=', 3)
                ->join('walkin_app_details', 'walkin_app_details.walkin_app_info_id', '=', 'walkin_app_infos.id')
                ->join('checklists', 'checklists.id', '=', 'walkin_app_details.c_visa_category')
                ->sum('checklists.price');
            $due              = $dueInfo + $dueDetails;
            $received          = WalkinAppInfo::select('id')->where('status', 1)->where('status', '!=', 3)->count('id');
            $processing          = WalkinAppInfo::select('id')->count('id');
            $missing_Documents  = WalkinAppInfo::select('id')->where('app_missing_documents_date', '!=', NULL)->count('id');
            $submitted_to_embassy = WalkinAppInfo::select('id')->where('app_submitted_embassy_date', '!=', NULL)->count('id');
            $ready_for_delivery  = WalkinAppInfo::select('id')->where('app_ready_for_delivery_date', '!=', NULL)->count('id');
            $delivered          = WalkinAppInfo::select('id')->where('app_delivered_date', '!=', NULL)->count('id');
            $reject             = WalkinAppInfo::select('id')->where('app_reject_date', '!=', NULL)->count('id');
            $application        = WalkinAppInfo::select('id')->where('app_status', '!=', 2)->count('id');
            $customer           = User::select('id')->where('role_id', 3)->count('id');
            $employee           = User::select('id')->where('role_id', '!=', 3)->count('id');

            return view('home', compact(
                'collection',
                'due',
                'received',
                'processing',
                'missing_Documents',
                'reject',
                'application',
                'customer',
                'employee',
                'submitted_to_embassy',
                'ready_for_delivery',
                'delivered'
            ));
        } else {
            return redirect('unauthorized')->with(['status' => 'error', 'message' => 'Unauthorized Access Blocked']);
        }
    }
    public function weeklyReport()
    {
        $seven_days_ago =  date("Y-m-d", strtotime("-7 day"));
        $today          = date('Y-m-d');
        $date           = User::select('id', 'created_at')->first();
        $user_date      = $date->created_at->format('Y-m-d');
        if (permission('dashboard-access')) {

            $this->setPageData('Dashboard', 'Dashboard', 'fas fa-technometer');

            $collection            = WalkinAppInfo::select('id', 'paid_amount', 'status', 'date')->whereBetween('date', [$seven_days_ago, $today])->where('paid_amount', '>', 0)->where('status', '!=', 3)->sum('paid_amount');

            $dueInfo               = WalkinAppInfo::select('id', 'visaType_id', 'visa_category', 'status', 'date')->whereBetween('walkin_app_infos.date', [$seven_days_ago, $today])->where('payment_status', '!=', 2)->where('walkin_app_infos.status', '!=', 3)
                ->join('checklists', 'checklists.id', '=', 'walkin_app_infos.visa_category')
                ->sum('checklists.price');

            $dueDetails           = WalkinAppInfo::select('id', 'visaType_id', 'visa_category')->whereBetween('walkin_app_infos.date', [$seven_days_ago, $today])->where('payment_status', '!=', 2)->where('walkin_app_infos.status', '!=', 3)
                ->join('walkin_app_details', 'walkin_app_details.walkin_app_info_id', '=', 'walkin_app_infos.id')
                ->join('checklists', 'checklists.id', '=', 'walkin_app_details.c_visa_category')
                ->sum('checklists.price');
            $due                  = $dueInfo + $dueDetails;

            $received              = WalkinAppInfo::select('id')->whereBetween('walkin_app_infos.date', [$seven_days_ago, $today])->where('status', 1)->where('status', '!=', 3)->count('id');

            $processing              = WalkinAppInfo::select('id')->whereBetween('walkin_app_infos.app_processing_date', [$seven_days_ago, $today])->count('id');

            $missing_Documents       = WalkinAppInfo::select('id')->whereBetween('walkin_app_infos.app_missing_documents_date', [$seven_days_ago, $today])->count('id');

            $submitted_to_embassy   = WalkinAppInfo::select('id')->whereBetween('walkin_app_infos.app_submitted_embassy_date', [$seven_days_ago, $today])->count('id');
            $ready_for_delivery     = WalkinAppInfo::select('id')->whereBetween('walkin_app_infos.app_ready_for_delivery_date', [$seven_days_ago, $today])->count('id');
            $delivered              = WalkinAppInfo::select('id')->whereBetween('walkin_app_infos.app_delivered_date', [$seven_days_ago, $today])->count('id');

            $reject                 = WalkinAppInfo::select('id')->whereBetween('walkin_app_infos.app_reject_date', [$seven_days_ago, $today])->count('id');

            $application            = WalkinAppInfo::select('id')->whereBetween('walkin_app_infos.date', [$seven_days_ago, $today])->where('app_status', '!=', 2)->count('id');
            $customer               = User::select('id')->where('role_id', 3)->whereBetween('date', [$seven_days_ago, $today])->count('id');
            $employee               = User::select('id')->where('role_id', '!=', 3)->count('id');

            return view('home', compact(
                'collection',
                'due',
                'received',
                'processing',
                'missing_Documents',
                'reject',
                'application',
                'customer',
                'employee',
                'submitted_to_embassy',
                'ready_for_delivery',
                'delivered'
            ));
        } else {
            return redirect('unauthorized')->with(['status' => 'error', 'message' => 'Unauthorized Access Blocked']);
        }
    }
    public function monthlyReport()
    {
        $seven_days_ago =  date("Y-m-d", strtotime("-30 day"));
        $today          = date('Y-m-d');
        $date           = User::select('id', 'created_at')->first();
        $user_date      = $date->created_at->format('Y-m-d');
        if (permission('dashboard-access')) {

            $this->setPageData('Dashboard', 'Dashboard', 'fas fa-technometer');

            $collection            = WalkinAppInfo::select('id', 'paid_amount', 'status', 'date')->whereBetween('date', [$seven_days_ago, $today])->where('paid_amount', '>', 0)->where('status', '!=', 3)->sum('paid_amount');

            $dueInfo               = WalkinAppInfo::select('id', 'visaType_id', 'visa_category', 'status', 'date')->whereBetween('walkin_app_infos.date', [$seven_days_ago, $today])->where('payment_status', '!=', 2)->where('walkin_app_infos.status', '!=', 3)
                ->join('checklists', 'checklists.id', '=', 'walkin_app_infos.visa_category')
                ->sum('checklists.price');

            $dueDetails           = WalkinAppInfo::select('id', 'visaType_id', 'visa_category')->whereBetween('walkin_app_infos.date', [$seven_days_ago, $today])->where('payment_status', '!=', 2)->where('walkin_app_infos.status', '!=', 3)
                ->join('walkin_app_details', 'walkin_app_details.walkin_app_info_id', '=', 'walkin_app_infos.id')
                ->join('checklists', 'checklists.id', '=', 'walkin_app_details.c_visa_category')
                ->sum('checklists.price');
            $due                  = $dueInfo + $dueDetails;

            $received              = WalkinAppInfo::select('id')->whereBetween('walkin_app_infos.date', [$seven_days_ago, $today])->where('status', 1)->where('status', '!=', 3)->count('id');

            $processing              = WalkinAppInfo::select('id')->whereBetween('walkin_app_infos.app_processing_date', [$seven_days_ago, $today])->count('id');

            $missing_documents       = WalkinAppInfo::select('id')->whereBetween('walkin_app_infos.app_missing_documents_date', [$seven_days_ago, $today])->count('id');

            $submitted_to_embassy   = WalkinAppInfo::select('id')->whereBetween('walkin_app_infos.app_submitted_embassy_date', [$seven_days_ago, $today])->count('id');
            $ready_for_delivery     = WalkinAppInfo::select('id')->whereBetween('walkin_app_infos.app_ready_for_delivery_date', [$seven_days_ago, $today])->count('id');
            $delivered              = WalkinAppInfo::select('id')->whereBetween('walkin_app_infos.app_delivered_date', [$seven_days_ago, $today])->count('id');

            $reject                 = WalkinAppInfo::select('id')->whereBetween('walkin_app_infos.app_reject_date', [$seven_days_ago, $today])->count('id');

            $application            = WalkinAppInfo::select('id')->whereBetween('walkin_app_infos.date', [$seven_days_ago, $today])->where('app_status', '!=', 2)->count('id');
            $customer               = User::select('id')->where('role_id', 3)->whereBetween('date', [$seven_days_ago, $today])->count('id');
            $employee               = User::select('id')->where('role_id', '!=', 3)->count('id');

                        return view('home',compact('collection','due','received','processing','missing_documents','reject','application','customer','employee','submitted_to_embassy',
                'ready_for_delivery','delivered'));
        } else {
            return redirect('unauthorized')->with(['status' => 'error', 'message' => 'Unauthorized Access Blocked']);
        }
    }
    public function dailyReport()
    {
        $seven_days_ago =  date("Y-m-d", strtotime("-365 day"));
        $today          = date('Y-m-d');
        $date           = User::select('id', 'created_at')->first();
        $user_date      = $date->created_at->format('Y-m-d');
        if (permission('dashboard-access')) {

            $this->setPageData('Dashboard', 'Dashboard', 'fas fa-technometer');

            $collection            = WalkinAppInfo::select('id', 'paid_amount', 'status', 'date')->where('walkin_app_infos.date', $today)->where('paid_amount', '>', 0)->where('status', '!=', 3)->sum('paid_amount');

            $dueInfo               = WalkinAppInfo::select('id', 'visaType_id', 'visa_category', 'status', 'date')->where('walkin_app_infos.date', $today)->where('payment_status', '!=', 2)->where('walkin_app_infos.status', '!=', 3)
                ->join('checklists', 'checklists.id', '=', 'walkin_app_infos.visa_category')
                ->sum('checklists.price');

            $dueDetails           = WalkinAppInfo::select('id', 'visaType_id', 'visa_category')->where('walkin_app_infos.date', $today)->where('payment_status', '!=', 2)->where('walkin_app_infos.status', '!=', 3)
                ->join('walkin_app_details', 'walkin_app_details.walkin_app_info_id', '=', 'walkin_app_infos.id')
                ->join('checklists', 'checklists.id', '=', 'walkin_app_details.c_visa_category')
                ->sum('checklists.price');
            $due                  = $dueInfo + $dueDetails;

            $received              = WalkinAppInfo::select('id')->where('walkin_app_infos.date', $today)->where('status', 1)->where('status', '!=', 3)->count('id');

            $processing              = WalkinAppInfo::select('id')->where('walkin_app_infos.app_processing_date', $today)->count('id');

            $Missing_Documents       = WalkinAppInfo::select('id')->where('walkin_app_infos.app_missing_documents_date', $today)->count('id');

            $Submitted_to_embassy   = WalkinAppInfo::select('id')->where('walkin_app_infos.app_submitted_embassy_date', $today)->count('id');
            $Ready_for_delivery     = WalkinAppInfo::select('id')->where('walkin_app_infos.app_ready_for_delivery_date', $today)->count('id');
            $Delivered              = WalkinAppInfo::select('id')->where('walkin_app_infos.app_delivered_date', $today)->count('id');

            $reject                 = WalkinAppInfo::select('id')->where('walkin_app_infos.app_reject_date', $today)->count('id');

            $application            = WalkinAppInfo::select('id')->where('walkin_app_infos.date', $today)->where('app_status', '!=', 2)->count('id');
            $customer               = User::select('id')->where('role_id', 3)->where('date', $today)->count('id');
            $employee               = User::select('id')->where('role_id', '!=', 3)->count('id');

            return view('home', compact('collection', 'dueInfo', 'due', 'received', 'processing', 'Missing_Documents', 'Submitted_to_embassy', 'Ready_for_delivery', 'Delivered', 'reject', 'application', 'customer', 'employee'));
        } else {
            return redirect('unauthorized')->with(['status' => 'error', 'message' => 'Unauthorized Access Blocked']);
        }
    }


    public function unauthorized()
    {
        $this->setPageData('Unauthorized', 'Unauthorized', 'fas fa-ban', [['name' => 'Unauthorized']]);
        return view('unauthorized');
    }
}
