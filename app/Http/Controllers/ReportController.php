<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\WalkIn\Entities\Passport;
use Modules\WalkIn\Entities\Payment;
use Modules\WalkIn\Entities\WalkinAppInfo;

class ReportController extends BaseController
{
    public function totalCollection(Request $request)
    {
        $setTitle = __('Total Collection');
        $setSubTitle = __('Total Collection Report');
        $this->setPageData($setSubTitle, $setSubTitle, 'fas fa-file-signature', [['name' => $setTitle, 'link' => 'javascript::void();'], ['name' => $setSubTitle]]);

        return view('report.collection');
    }
    public function totalCollectionData(Request $request)
    {
        $setTitle    = __('Total Collection');
        $setSubTitle = __('Total Collection Report');
        $this->setPageData($setSubTitle, $setSubTitle, 'fas fa-file-signature', [['name' => $setTitle, 'link' => 'javascript::void();'], ['name' => $setSubTitle]]);
        $from   = $request->form_date;
        $to     = $request->to_date;
        $report = WalkinAppInfo::with(['payments', 'checklist'])
            ->whereHas('payments', function ($q) use ($from, $to) {
                $q->whereBetween('payment_date', [$from, $to])
                    ->where('paid_amount', '>', 0);
            })
            ->get();
        return view('report.collection', compact('report'));
    }

    public function totalCollectionDetails(Request $request)
    {
        $setTitle = __('Total Collection');
        $setSubTitle = __('Total Collection Report');
        $this->setPageData($setSubTitle, $setSubTitle, 'fas fa-file-signature', [['name' => $setTitle, 'link' => 'javascript::void();'], ['name' => $setSubTitle]]);

        return view('report.collectionDetails');
    }
    public function totalCollectionDetailData(Request $request)
    {
        $setTitle    = __('Total Collection');
        $setSubTitle = __('Total Collection Report');
        $this->setPageData($setSubTitle, $setSubTitle, 'fas fa-file-signature', [['name' => $setTitle, 'link' => 'javascript::void();'], ['name' => $setSubTitle]]);

        // Execute the query and retrieve the data
        $report = WalkinAppInfo::with('payments')->select(
            'walkin_app_infos.id',
            'walkin_app_infos.name',
            'walkin_app_infos.p_name',
            'walkin_app_infos.uniqueKey',
            'walkin_app_infos.visaType_id',
            'walkin_app_infos.visa_category',
            'walkin_app_infos.email',
            'walkin_app_infos.phone',
            'walkin_app_infos.paid_amount',
            'walkin_app_infos.due_amount',
            'walkin_app_infos.discount',
            'walkin_app_infos.group_price'
        )
            ->whereHas('payments', function ($paymentDetails) use ($request) {
                $from   = $request->form_date;
                $to     = $request->to_date;
                $paymentDetails->whereBetween('payment_date', [$from, $to]);
            })
            ->orderBy('walkin_app_infos.id', 'DESC') // Order by a column in the walkin_app_infos table
            ->get();

        return view('report.collectionDetails', compact('report'));
    }

    public function totalDue(Request $request)
    {
        $setTitle    = __('Total Due');
        $setSubTitle = __('Total Due Report');
        $this->setPageData($setSubTitle, $setSubTitle, 'fas fa-file-signature', [['name' => $setTitle, 'link' => 'javascript::void();'], ['name' => $setSubTitle]]);
        return view('report.due');
    }
    public function totalDueData(Request $request)
    {
        $setTitle    = __('Total Due');
        $setSubTitle = __('Total Due Report');
        $this->setPageData($setSubTitle, $setSubTitle, 'fas fa-file-signature', [['name' => $setTitle, 'link' => 'javascript::void();'], ['name' => $setSubTitle]]);
        $from   = $request->form_date;
        $to     = $request->to_date;
        $report = WalkinAppInfo::with('payments')->whereBetween('date', [$from, $to])->where('payment_status', '!=', 2)->where('status', '!=', 3)->get();

        return view('report.due', compact('report'));
    }

    public function received(Request $request)
    {
        $setTitle    = __('Total File Received');
        $setSubTitle = __('Total File Received Report');
        $this->setPageData($setSubTitle, $setSubTitle, 'fas fa-file-signature', [['name' => $setTitle, 'link' => 'javascript::void();'], ['name' => $setSubTitle]]);
        return view('report.received');
    }
    public function receivedData(Request $request)
    {
        $setTitle    = __('Total File Received');
        $setSubTitle = __('Total File Received Report');
        $this->setPageData($setSubTitle, $setSubTitle, 'fas fa-file-signature', [['name' => $setTitle, 'link' => 'javascript::void();'], ['name' => $setSubTitle]]);
        $from   = $request->form_date;
        $to     = $request->to_date;
        $report = WalkinAppInfo::whereBetween('date', [$from, $to])->where('status', 1)->where('status', '!=', 3)->get();
        return view('report.received', compact('report'));
    }
    public function processing(Request $request)
    {
        $setTitle    = __('Application in Process Report');
        $setSubTitle = __('Application in Process Report');
        $this->setPageData($setSubTitle, $setSubTitle, 'fas fa-file-signature', [['name' => $setTitle, 'link' => 'javascript::void();'], ['name' => $setSubTitle]]);
        return view('report.processing');
    }
    public function processingData(Request $request)
    {
        $setTitle    = __('Application in Process Report');
        $setSubTitle = __('Application in Process Report');
        $this->setPageData($setSubTitle, $setSubTitle, 'fas fa-file-signature', [['name' => $setTitle, 'link' => 'javascript::void();'], ['name' => $setSubTitle]]);
        $from   = $request->form_date;
        $to     = $request->to_date;
        $report = WalkinAppInfo::whereBetween('app_processing_date', [$from, $to])->where('status', 2)->get();
        return view('report.processing', compact('report'));
    }
    public function missingDoc(Request $request)
    {
        $setTitle    = __('Required Missing Documents');
        $setSubTitle = __('Required Missing Documents Report');
        $this->setPageData($setSubTitle, $setSubTitle, 'fas fa-file-signature', [['name' => $setTitle, 'link' => 'javascript::void();'], ['name' => $setSubTitle]]);
        return view('report.approved');
    }
    public function missingDocData(Request $request)
    {
        $setTitle    = __('Required Missing Documents');
        $setSubTitle = __('Required Missing Documents Report');
        $this->setPageData($setSubTitle, $setSubTitle, 'fas fa-file-signature', [['name' => $setTitle, 'link' => 'javascript::void();'], ['name' => $setSubTitle]]);
        $from   = $request->form_date;
        $to     = $request->to_date;
        $report = WalkinAppInfo::whereBetween('app_missing_documents_date', [$from, $to])->where('status', 3)->get();
        return view('report.approved', compact('report'));
    }
    public function submittedEmbassy(Request $request)
    {
        $setTitle    = __('Submitted to embassy');
        $setSubTitle = __('Submitted to embassy Report');
        $this->setPageData($setSubTitle, $setSubTitle, 'fas fa-file-signature', [['name' => $setTitle, 'link' => 'javascript::void();'], ['name' => $setSubTitle]]);
        return view('report.embassy-submission');
    }
    public function submittedEmbassyData(Request $request)
    {
        $setTitle    = __('Submitted to embassy');
        $setSubTitle = __('Submitted to embassy Report');
        $this->setPageData($setSubTitle, $setSubTitle, 'fas fa-file-signature', [['name' => $setTitle, 'link' => 'javascript::void();'], ['name' => $setSubTitle]]);
        $from   = $request->form_date;
        $to     = $request->to_date;
        $report = WalkinAppInfo::whereBetween('app_submitted_embassy_date', [$from, $to])->where('status', 4)->get();
        return view('report.embassy-submission', compact('report'));
    }

    public function delivery(Request $request)
    {
        $setTitle    = __('Ready for delivery');
        $setSubTitle = __('Ready for delivery Report');
        $this->setPageData($setSubTitle, $setSubTitle, 'fas fa-file-signature', [['name' => $setTitle, 'link' => 'javascript::void();'], ['name' => $setSubTitle]]);
        return view('report.delivery');
    }
    public function deliveryData(Request $request)
    {
        $setTitle    = __('Ready for delivery');
        $setSubTitle = __('Ready for delivery Report');
        $this->setPageData($setSubTitle, $setSubTitle, 'fas fa-file-signature', [['name' => $setTitle, 'link' => 'javascript::void();'], ['name' => $setSubTitle]]);
        $from   = $request->form_date;
        $to     = $request->to_date;
        $report = WalkinAppInfo::whereBetween('app_ready_for_delivery_date', [$from, $to])->where('status', 5)->get();
        return view('report.delivery', compact('report'));
    }

    public function delivered(Request $request)
    {
        $setTitle    = __('Delivered');
        $setSubTitle = __('Delivered Report');
        $this->setPageData($setSubTitle, $setSubTitle, 'fas fa-file-signature', [['name' => $setTitle, 'link' => 'javascript::void();'], ['name' => $setSubTitle]]);
        return view('report.delivered');
    }
    public function deliveredData(Request $request)
    {
        $setTitle    = __('Delivered');
        $setSubTitle = __('Delivered Report');
        $this->setPageData($setSubTitle, $setSubTitle, 'fas fa-file-signature', [['name' => $setTitle, 'link' => 'javascript::void();'], ['name' => $setSubTitle]]);
        $from   = $request->form_date;
        $to     = $request->to_date;
        $report = WalkinAppInfo::whereBetween('app_delivered_date', [$from, $to])->where('status', 6)->get();
        return view('report.delivered', compact('report'));
    }


    public function fileProcess(Request $request)
    {
        $setTitle    = __('Total File Process By Employee');
        $setSubTitle = __('Total File Process By Employee Report');
        $this->setPageData($setSubTitle, $setSubTitle, 'fas fa-file-signature', [['name' => $setTitle, 'link' => 'javascript::void();'], ['name' => $setSubTitle]]);
        return view('report.fileProcess');
    }
    public function fileProcessData(Request $request)
    {
        $setTitle    = __('Total File Process By Employee');
        $setSubTitle = __('Total File Process By Employee Report');
        $this->setPageData($setSubTitle, $setSubTitle, 'fas fa-file-signature', [['name' => $setTitle, 'link' => 'javascript::void();'], ['name' => $setSubTitle]]);
        $from   = $request->form_date;
        $to     = $request->to_date;
        $report = WalkinAppInfo::whereBetween('date', [$from, $to])->where('user_id', Auth::id())->where('status', '!=', 3)->get();
        return view('report.fileProcess', compact('report'));
    }

    public function serviceChargeReport(Request $request)
    {
        $setTitle    = __('Service Charge Report');
        $setSubTitle = __('Service Charge Report');
        $this->setPageData($setSubTitle, $setSubTitle, 'fas fa-file-signature', [['name' => $setTitle, 'link' => 'javascript::void();'], ['name' => $setSubTitle]]);
        return view('report.service_charge_payment_report');
    }

    public function serviceChargeReportData(Request $request)
    {
        $setTitle    = __('Service Charge');
        $setSubTitle = __('Service Charge Report');
        $this->setPageData($setSubTitle, $setSubTitle, 'fas fa-file-signature', [['name' => $setTitle, 'link' => 'javascript::void();'], ['name' => $setSubTitle]]);
        $from   = $request->form_date;
        $to     = $request->to_date;
        $report = WalkinAppInfo::with(['payments', 'checklist'])
            ->whereHas('payments', function ($q) use ($from, $to) {
                $q->whereBetween('payment_date', [$from, $to])
                    ->where('service_charge', '>', 0);
            })
            ->get();
        return view('report.service_charge_payment_report', compact('report'));
    }

    public function paymentAndChecklist()
    {
        $datas = Payment::leftJoin('walkin_app_infos', 'payments.walkin_app_info_id', '=', 'walkin_app_infos.id')
            ->leftJoin('checklists', 'walkin_app_infos.visa_category', '=', 'checklists.id')
            ->where('payments.payment_status', 2)
            ->where('payments.payment', 0)
            ->whereBetween('payments.payment_date', ['2026-01-01', '2026-02-02'])
            ->select([
                'payments.id',
                'payments.walkin_app_info_id',
                'payments.payment',
                'payments.paid_amount',
                'payments.due_amount',
                'payments.discount',
                'payments.service_charge',
                'payments.visa_fee',
                'walkin_app_infos.visa_category as visa_category',
                'checklists.title as checklist_title',
                'checklists.price as checklist_price',
                'checklists.service_charge as checklist_service_charge',
            ])
            ->get();
        return view('report.kamrul', compact('datas'));
    }

    public function appPaymentUpdate()
    {
        $affectedRows = Payment::whereHas('appInfo', function ($q) {
            $q->where('visa_category', 616);
        })
            ->where('payment_status', 2)
            ->where('payment', 0)
            ->where('discount', 0)
            ->where('paid_amount', 6005)
            ->update([
                'service_charge' => 700,
                'visa_fee'       => 5305,
                'payment'        => 6005,
            ]);



        return "Application payment information updated successfully.";
    }
}
