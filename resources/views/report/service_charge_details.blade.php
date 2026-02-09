@extends('layouts.app')
@section('title', $page_title)
@push('styles')
<link href="{{asset('css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet" type="text/css" />
@endpush
@section('content')
<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <div class="card card-custom gutter-b">
            <div class="card-header flex-wrap py-5">
                <div class="card-title">
                    <h3 class="card-label">
                        <i class="{{ $page_icon }} text-primary"></i> {{ $sub_title }}
                    </h3>
                </div>
                <div class="card-toolbar">
                    <button type="button" class="btn btn-primary btn-sm mr-3" id="print-labor-bill">
                        <i class="fas fa-print"></i> {{__('Print')}}
                    </button>
                    <button type="button" class="btn btn-primary btn-sm mr-3" id="btnExport"
                        onclick="tableToExcel('testTable', 'Sheet1')"><i class="fas fa-download"></i> {{__('Download
                        Excel')}}</button>

                    <a href="{{ route('dashboard') }}" class="btn btn-warning btn-sm font-weight-bolder">
                        <i class="fas fa-arrow-left"></i> {{__('Back')}}
                    </a>
                </div>
            </div>
        </div>
        <form action="{{route('service.charge.details.report.data')}}" method="GET">
            @csrf
            <div class="card card-custom gutter-b">
                <div class="card-header flex-wrap py-5">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="date" class="form-control" name="form_date">
                            </div>
                            <div class="col-md-4">
                                <input type="date" class="form-control" name="to_date">
                            </div>
                            <div class="col-md-2">
                                <button type="submit"
                                    class="btn btn-primary btn-sm btn-elevate btn-icon mr-2 float-right"
                                    data-toggle="tooltip" data-theme="dark" title="{{__('Search')}}"><i
                                        class="fas fa-search"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4"></div>
                </div>
            </div>
        </form>
        <div class="card card-custom">
            <div class="card-body" style="    padding: 2px 0px;">
                <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                    <div class="row">
                        <div id="invoice" class="col-md-12 labor-bill">
                            <style>
                                body,
                                html {
                                    background: #fff !important;
                                    -webkit-print-color-adjust: exact !important;
                                }

                                .invoice {
                                    /* position: relative; */
                                    background: #fff !important;
                                    /* min-height: 680px; */
                                }

                                #print-labor-bill:last-child {
                                    page-break-after: auto !important;
                                }

                                .invoice header {
                                    padding: 10px 0;
                                    margin-bottom: 20px;
                                    border-bottom: 1px solid #036;
                                }

                                .invoice .company-details {
                                    text-align: right
                                }

                                .invoice .company-details .name {
                                    margin-top: 0;
                                    margin-bottom: 0;
                                }

                                .invoice .contacts {
                                    margin-bottom: 20px;
                                }

                                .invoice .invoice-to {
                                    text-align: left;
                                }

                                .invoice .invoice-to .to {
                                    margin-top: 0;
                                    margin-bottom: 0;
                                }

                                .invoice .invoice-details {
                                    text-align: right;
                                }

                                .invoice .invoice-details .invoice-id {
                                    margin-top: 0;
                                    color: #036;
                                }

                                .invoice main {
                                    padding-bottom: 50px
                                }

                                .invoice main .thanks {
                                    margin-top: -100px;
                                    font-size: 2em;
                                    margin-bottom: 50px;
                                }

                                .invoice main .notices {
                                    padding-left: 6px;
                                    border-left: 6px solid #036;
                                }

                                .invoice table {
                                    width: 100%;
                                    border-collapse: collapse;
                                    border-spacing: 0;
                                    margin-bottom: 20px;
                                }

                                .invoice table th {
                                    background: #036;
                                    color: #fff;
                                    padding: 15px;
                                    border-bottom: 1px solid #fff
                                }

                                .invoice table td {
                                    padding: 10px;
                                    border-bottom: 1px solid #fff
                                }

                                .invoice table th {
                                    white-space: nowrap;
                                }

                                .invoice table td h3 {
                                    margin: 0;
                                    color: #036;
                                }

                                .invoice table .qty {
                                    text-align: center;
                                }

                                .invoice table .price,
                                .invoice table .discount,
                                .invoice table .tax,
                                .invoice table .total {
                                    text-align: right;
                                }

                                .invoice table .no {
                                    color: #fff;
                                    background: #036
                                }

                                .invoice table .total {
                                    background: #036;
                                    color: #fff
                                }

                                .invoice table tbody tr:last-child td {
                                    border: none
                                }

                                .invoice table tfoot td {
                                    background: 0 0;
                                    border-bottom: none;
                                    white-space: nowrap;
                                    text-align: right;
                                    padding: 10px 20px;
                                    border-top: 1px solid #aaa;
                                    font-weight: bold;
                                }

                                .invoice table tfoot tr:first-child td {
                                    border-top: none
                                }

                                /* .invoice table tfoot tr:last-child td {
                                                            color: #036;
                                                            border-top: 1px solid #036
                                                        } */
                                .invoice table tfoot tr td:first-child {
                                    border: none
                                }

                                .invoice footer {
                                    width: 100%;
                                    text-align: center;
                                    color: #777;
                                    border-top: 1px solid #aaa;
                                    padding: 8px 0
                                }

                                .invoice a {
                                    content: none !important;
                                    text-decoration: none !important;
                                    color: #036 !important;
                                }

                                .page-header,
                                .page-header-space {
                                    height: 100px;
                                }

                                .page-footer,
                                .page-footer-space {
                                    height: 20px;
                                }

                                .page-footer {
                                    position: fixed;
                                    bottom: 0;
                                    width: 100%;
                                    text-align: center;
                                    color: #777;
                                    border-top: 1px solid #aaa;
                                    padding: 8px 0
                                }

                                .page-header {
                                    position: fixed;
                                    top: 0mm;
                                    width: 100%;
                                    border-bottom: 1px solid black;
                                }

                                .page {
                                    page-break-after: always;
                                }

                                .dashed-border {
                                    width: 180px;
                                    height: 2px;
                                    margin: 0 auto;
                                    padding: 0;
                                    border-top: 1px dashed #454d55 !important;
                                }

                                @media screen {
                                    .no_screen {
                                        display: none;
                                    }

                                    .no_print {
                                        display: block;
                                    }

                                    thead {
                                        display: table-header-group;
                                    }

                                    tfoot {
                                        display: table-footer-group;
                                    }

                                    button {
                                        display: none;
                                    }

                                    body {
                                        margin: 0;
                                    }
                                }

                                @media print {

                                    body,
                                    html {
                                        -webkit-print-color-adjust: exact !important;
                                        font-family: sans-serif;
                                        margin-bottom: 100px !important;
                                    }

                                    .m-0 {
                                        margin: 0 !important;
                                    }

                                    h1,
                                    h2,
                                    h3,
                                    h4,
                                    h5,
                                    h6 {
                                        margin: 0 !important;
                                    }

                                    .no_screen {
                                        display: block !important;
                                    }

                                    .no_print {
                                        display: none;
                                    }

                                    a {
                                        content: none !important;
                                        text-decoration: none !important;
                                        color: #036 !important;
                                    }

                                    .text-center {
                                        text-align: center !important;
                                    }

                                    .text-left {
                                        text-align: left !important;
                                    }

                                    .text-right {
                                        text-align: right !important;
                                    }

                                    .float-left {
                                        float: left !important;
                                    }

                                    .float-right {
                                        float: right !important;
                                    }

                                    .text-bold {
                                        font-weight: bold !important;
                                    }

                                    .invoice {
                                        overflow: hidden !important;
                                        background: #fff !important;
                                        margin-bottom: 100px !important;
                                    }

                                    .invoice footer {
                                        position: absolute;
                                        bottom: 0;
                                        left: 0;
                                    }

                                    .hidden-print {
                                        display: none !important;
                                    }

                                    .double-border {
                                        border-top: double black !important;
                                    }

                                    .dashed-border {
                                        width: 180px;
                                        height: 2px;
                                        margin: 0 auto;
                                        padding: 0;
                                        border-top: 1px dashed #454d55 !important;
                                    }
                                }

                                @page {
                                    /* size: auto; */
                                    margin: 5mm 5mm;
                                }
                            </style>
                            <div class="invoice overflow-auto">
                                <div class="col-md-12 py-5">
                                    <div style="text-align: center; margin-top: 10px;">
                                        <h2 class="name m-0" style="text-transform: uppercase; margin: 0;">
                                            <b>{{ config('settings.title') ? config('settings.title') : env('APP_NAME')
                                                }}</b>
                                        </h2>

                                        @if(config('settings.address'))
                                        <h3 style="font-weight: normal; margin: 0;">
                                            {{ config('settings.address') }}
                                        </h3>
                                        @endif

                                        <div
                                            style="width: 250px; background: #036; color: white; font-weight: bolder; margin: 5px auto 0; padding: 5px 0; border-radius: 15px;">
                                            {{ $page_title }}
                                        </div>

                                        @if(request()->get('form_date') && request()->get('to_date'))
                                        <h3 style="font-weight: normal; margin: 5px 0 0 0;">
                                            {{ __('From') }} {{ request()->get('form_date') }} {{ __('To') }} {{
                                            request()->get('to_date') }}
                                        </h3>
                                        @php
                                        $fromDate = request()->get('form_date');
                                        $toDate = request()->get('to_date');
                                        @endphp
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <!--begin: Datatable-->
                                <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="table-responsive">
                                                <table id="testTable" class="table table-bordered table-hover">
                                                    <thead class="bg-primary text-white">
                                                        <tr>
                                                            <td class="text-center">SL</td>
                                                            <td class="text-center">Date</td>
                                                            <td class="text-center">Agent / Company Name</td>
                                                            <td class="text-center">Application ID</td>
                                                            <td class="text-center">Destination</td>
                                                            <td class="text-center">Information</td>
                                                            <td class="text-center">Passport No</td>
                                                            <td class="text-right">Partial Payment</td>
                                                            <td class="text-right">Discount</td>
                                                            <td class="text-right">Service Charge</td>
                                                            <td class="text-right">Card Payment</td>
                                                            <td class="text-right">Cash Payment</td>
                                                            <td class="text-right">Paid Amount</td>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php
                                                        $total_partial = 0;
                                                        $total_discount = 0;
                                                        $total_card = 0;
                                                        $total_cash = 0;
                                                        $total_paid_service_charge = 0;
                                                        $total_visa_fee = 0;
                                                        $total_service_charge = 0;
                                                        $total_due = 0;
                                                        $total_received = 0;
                                                        $total_fee = 0;
                                                        $filteredFee = 0;
                                                        @endphp
                                                        @if(!empty($report))
                                                        @foreach($report as $key=>$row)
                                                        @php
                                                        $filteredPayments = $row->payments->where('payment_date', '>=',
                                                        $fromDate)->
                                                        where('payment_date', '<=', $toDate); $thisRowPaidAmount=0;
                                                            @endphp <tr>
                                                            <td class="text-center">{{$key+1}}</td>
                                                            <td class="text-right table-bordered">
                                                                <table>
                                                                    @foreach($filteredPayments as $pay)
                                                                    @if($pay->service_charge > 0)
                                                                    <tr style="background: lightyellow;">
                                                                        <td>{{$pay->payment_date}}</td>
                                                                    </tr>
                                                                    @endif
                                                                    @endforeach

                                                                </table>
                                                            </td>
                                                            <td class="text-center">{{$row->name ?? ''}}</td>
                                                            <td class="text-center">{{$row->uniqueKey ?? ''}}</td>
                                                            <td class="text-center">{{$row->visaType ?
                                                                $row->visaType->visa_type : ''}}</td>
                                                            <td class="text-center">
                                                                {{$row->p_name}}<br>{{$row->email}}<br>{{$row->phone}}
                                                            </td>
                                                            <td class="text-center">
                                                                @foreach($row->passports as $p)
                                                                <span>{{$p->passport_no}}</span><br>
                                                                @endforeach
                                                            </td>
                                                            <td class="text-right">
                                                                @php
                                                                $paidPartialPayment =
                                                                $row->payments->where('payment_status',
                                                                3)->sum('service_charge');
                                                                $total_partial += $paidPartialPayment;
                                                                echo $paidPartialPayment;
                                                                @endphp
                                                            </td>
                                                            <td class="text-center">{{$row->discount}}
                                                                @php
                                                                $total_discount += $row->discount;
                                                                @endphp
                                                            </td>
                                                            <td class="text-right">
                                                                @php
                                                                $paidServiceCharge =
                                                                $row->payments->sum('service_charge');
                                                                $total_paid_service_charge +=
                                                                $paidServiceCharge;
                                                                echo $paidServiceCharge;
                                                                @endphp
                                                            </td>
                                                            <td class="text-right">
                                                                @php
                                                                $cardPayment = $row->payments->where('account_type',
                                                                '!=', 4)->sum('service_charge');
                                                                $total_card += $cardPayment;
                                                                echo $cardPayment;
                                                                @endphp
                                                            </td>
                                                            <td class="text-right">
                                                                @php
                                                                $cashPayment = $row->payments->where('account_type',
                                                                4)->sum('service_charge');
                                                                $total_cash += $cashPayment;
                                                                echo $cashPayment;
                                                                @endphp
                                                            </td>
                                                            <td class="text-right">
                                                                <table class="table-bordered">
                                                                    @foreach($filteredPayments as $pay)
                                                                    @if($pay->service_charge > 0)
                                                                    <tr style="background: lightyellow;">
                                                                        <td width="100%">{{$pay->service_charge}} /-Tk
                                                                        </td>
                                                                    </tr>
                                                                    @php
                                                                    $filteredFee += $pay->service_charge;
                                                                    @endphp
                                                                    @endif
                                                                    @endforeach
                                                                </table>
                                                            </td>

                                                            </tr>

                                                            @endforeach
                                                            @endif
                                                    </tbody>
                                                    <tfoot>
                                                        <tr class="text-white bg-primary"
                                                            style="font-weight: 600;font-size: 1rem;border-bottom-width: 1px;">
                                                            <td class="text-center" colspan="7"></td>
                                                            <td class="text-right">Partial Payment</td>
                                                            <td class="text-right">Discount</td>
                                                            <td class="text-center">Service Charge</td>
                                                            <td class="text-right">Card Payment</td>
                                                            <td class="text-right">Cash Payment</td>
                                                            <td class="text-right">Paid Amount</td>
                                                        </tr>
                                                        <tr class="text-black">
                                                            <td style="text-align: right !important;font-weight:bold;"
                                                                colspan="7">{{__('Total ')}}</td>
                                                            <td class="text-right"
                                                                style="text-align: right !important;font-weight:bold;">
                                                                {{ $total_partial }}/ TK</td>
                                                            <td class="text-right"
                                                                style="text-align: right !important;font-weight:bold;">
                                                                {{ $total_discount }}/ TK</td>
                                                            <td class="text-right"
                                                                style="text-align: right !important;font-weight:bold;">
                                                                {{$total_paid_service_charge}}/ TK</td>
                                                            <td class="text-right"
                                                                style="text-align: right !important;font-weight:bold;">
                                                                {{ $total_card }}/ TK</td>
                                                            <td class="text-right"
                                                                style="text-align: right !important;font-weight:bold;">
                                                                {{$total_cash}}/ TK</td>
                                                            <td class="text-right"
                                                                style="text-align: right !important;font-weight:bold;">
                                                                {{$filteredFee}}/ TK</td>
                                                            
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!--end: Datatable-->
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@push('scripts')
<script src="{{asset('js/jquery.printarea.js')}}"></script>
<script>
    $(document).ready(function () {
            $(document).on('click','#print-labor-bill',function(){
                var mode = 'iframe'; // popup
                var close = mode == "popup";
                var options = {
                    mode: mode,
                    popClose: close
                };
                $(".labor-bill").printArea(options);
            });

        });
</script>
<script>
    function tableToExcel(tableid) {
            var tab_text = "<table border='2px'><tr bgcolor='#87AFC6'>";
            var textRange; var j = 0;
            tab = document.getElementById(tableid);//.getElementsByTagName('table'); // id of table
            if (tab==null) {
                return false;
            }
            if (tab.rows.length == 0) {
                return false;
            }

            for (j = 0 ; j < tab.rows.length ; j++) {
                tab_text = tab_text + tab.rows[j].innerHTML + "</tr>";
                //tab_text=tab_text+"</tr>";
            }

            tab_text = tab_text + "</table>";
            tab_text = tab_text.replace(/<A[^>]*>|<\/A>/g, "");//remove if u want links in your table
            tab_text = tab_text.replace(/<img[^>]*>/gi, ""); // remove if u want images in your table
            tab_text = tab_text.replace(/<input[^>]*>|<\/input>/gi, ""); // reomves input params

            var ua = window.navigator.userAgent;
            var msie = ua.indexOf("MSIE ");

            if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./))      // If Internet Explorer
            {
                txtArea1.document.open("txt/html", "replace");
                txtArea1.document.write(tab_text);
                txtArea1.document.close();
                txtArea1.focus();
                sa = txtArea1.document.execCommand("SaveAs", true, "download.xls");
            }
            else                 //other browser not tested on IE 11
                //sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));
                try {
                    var blob = new Blob([tab_text], { type: "application/vnd.ms-excel" });
                    window.URL = window.URL || window.webkitURL;
                    link = window.URL.createObjectURL(blob);
                    a = document.createElement("a");
                    if (document.getElementById("caption")!=null) {
                        a.download=document.getElementById("caption").innerText;
                    }
                    else
                    {
                        a.download = 'download';
                    }

                    a.href = link;

                    document.body.appendChild(a);

                    a.click();

                    document.body.removeChild(a);
                } catch (e) {
                }


            return false;
            //return (sa);
        }

</script>
@endpush