@extends('layouts.app')
@section('title', $page_title)
@push('styles')
    <link href="{{asset('css/bootstrap-datetimepicker.min.css')}}" rel="stylesheet" type="text/css"/>
    <style>
        .my-checkbox {
            transform: scale(2);
            margin: 11px 20px;

        }

        input[type=checkbox], input[type=radio] {
            margin: 10px;
            margin-top: 1px \9;
            /* line-height: normal; */
        }

        .md-stepper-horizontal {
            display: table;
            width: 100%;
            margin: 0px 0px 25px 0px;
            background-color: #ffffff;
            box-shadow: 0 3px 8px -6px rgb(0 0 0 / 50%);
        }

        .md-stepper-horizontal .md-step {
            display: table-cell;
            position: relative;
            padding: 0px 0px 16px 0px;
        }

        .md-stepper-horizontal .md-step:hover,
        .md-stepper-horizontal .md-step:active {
            background-color: rgba(0, 0, 0, 0.04);
        }

        .md-stepper-horizontal .md-step:active {
            border-radius: 15% / 75%;
        }

        .md-stepper-horizontal .md-step:first-child:active {
            border-top-left-radius: 0;
            border-bottom-left-radius: 0;
        }

        .md-stepper-horizontal .md-step:last-child:active {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }

        .md-stepper-horizontal .md-step:hover .md-step-circle {
            background-color: #757575;
        }

        .md-stepper-horizontal .md-step:first-child .md-step-bar-left,
        .md-stepper-horizontal .md-step:last-child .md-step-bar-right {
            display: none;
        }

        .md-stepper-horizontal .md-step .md-step-circle {
            width: 65px;
            height: 65px;
            margin: 0 auto;
            background-color: #999999;
            border-radius: 50%;
            text-align: center;
            line-height: 65px;
            font-size: 16px;
            font-weight: 600;
            color: #ffffff;
        }

        .md-stepper-horizontal.green .md-step.active .md-step-circle {
            background-color: #00ae4d;
        }

        .md-stepper-horizontal.orange .md-step.active .md-step-circle {
            background-color: #f96302;
        }

        .md-stepper-horizontal .md-step.active .md-step-circle {
            background-color: rgb(33, 150, 243);
        }

        .md-stepper-horizontal .md-step.done .md-step-circle:before {
            font-family: "FontAwesome";
            font-weight: 100;
            /*content: "\f00c";*/
        }

        .md-stepper-horizontal .md-step.done .md-step-circle *,
        .md-stepper-horizontal .md-step.editable .md-step-circle * {
            display: none;
        }

        .md-stepper-horizontal .md-step.editable .md-step-circle {
            -moz-transform: scaleX(-1);
            -o-transform: scaleX(-1);
            -webkit-transform: scaleX(-1);
            transform: scaleX(-1);
        }

        .md-stepper-horizontal .md-step.editable .md-step-circle:before {
            font-family: "FontAwesome";
            font-weight: 100;
            content: "\f040";
        }

        .md-stepper-horizontal .md-step .md-step-title {
            margin-top: 16px;
            font-size: 16px;
            font-weight: 600;
        }

        .md-stepper-horizontal .md-step .md-step-title,
        .md-stepper-horizontal .md-step .md-step-optional {
            text-align: center;
            color: rgba(0, 0, 0, 0.26);
        }

        .md-stepper-horizontal .md-step.active .md-step-title {
            font-weight: 600;
            color: rgba(0, 0, 0, 0.87);
        }

        .md-stepper-horizontal .md-step.active.done .md-step-title,
        .md-stepper-horizontal .md-step.active.editable .md-step-title {
            font-weight: 600;
        }

        .md-stepper-horizontal .md-step .md-step-optional {
            font-size: 12px;
        }

        .md-stepper-horizontal .md-step.active .md-step-optional {
            color: rgba(0, 0, 0, 0.54);
        }

        .md-stepper-horizontal .md-step .md-step-bar-left,
        .md-stepper-horizontal .md-step .md-step-bar-right {
            position: absolute;
            top: 36px;
            height: 1px;
            border-top: 1px solid #dddddd;
        }

        .md-stepper-horizontal .md-step .md-step-bar-right {
            right: 0;
            left: 55%;
            margin-left: 20px;
        }

        .md-stepper-horizontal .md-step .md-step-bar-left {
            left: 0;
            right: 54%;
            margin-right: 20px;
        }

    </style>
    <style>
        body,
        html {
            background: #fff !important;
            -webkit-print-color-adjust: exact !important;
        }

        legend {
            font-size: 1.2rem !important;
        }

        .invoice {
            /* position: relative; */
            background: #fff !important;
            /* min-height: 680px; */
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
            border-left: 6px solid #000;
        }

        .invoice table {
            width: 100%;
            border-collapse: collapse;
            border-spacing: 0;
            margin-bottom: 20px;
            border: 2px solid;
        }

        .invoice table th {
            background: #036;
            color: #fff;
            padding: 15px;
            border-bottom: 2px solid #000
        }

        .invoice table td {
            padding: 10px;
            border-bottom: 1px solid #000;
            font-size: 16px;
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
            border-top: 1px solid #000;
            font-weight: bold;
        }

        .invoice table tfoot tr:first-child td {
            border-top: none
        }

        .invoice table tfoot tr td:first-child {
            border: none
        }

        .invoice footer {
            width: 100%;
            text-align: center;
            color: #777;
            border-top: 1px solid #000;
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

            .dashed-border {
                width: 180px;
                height: 2px;
                margin: 0 auto;
                padding: 0;
                border-top: 1px dashed #454d55 !important;
            }
        }

        @page {
            margin: 5mm 5mm;
        }
    </style>
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
                        <button type="button" class="btn btn-primary btn-sm mr-3" id="print-page"><i
                                class="fas fa-print"></i> {{__('Print')}}</button>
                        <button type="button" class="btn btn-primary btn-sm mr-3" id="btnExport"><i
                                class="fas fa-download"></i> {{__('Download Excel')}}</button>
{{--                        <a href="{{ route('index') }}" class="btn btn-warning btn-sm font-weight-bolder"><i--}}
{{--                                class="fas fa-arrow-left"></i> {{__('Back')}}</a>--}}
                    </div>
                </div>
            </div>

            <div class="card card-custom" id="testTable">
                <div class="card-body">
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <div class="row">
                            <div id="invoice" class="col-md-12 labor-bill">
                                <div class="invoice overflow-auto">
                                    <div class="md-stepper-horizontal orange">
                                        @if($info->status == 1 or $info->status == 2 or $info->status == 3 or $info->status == 4 or $info->status == 5 or $info->status == 6 or $info->status == 7)
                                            <div class="md-step active">
                                                <div class="md-step-circle"><span><i class="fas fa-plane" style="color: white;"></i></span>
                                                </div>
                                                <div class="md-step-title">Application Received </div>
                                                <div class="md-step-bar-left"></div>
                                                <div class="md-step-bar-right"></div>
                                            </div>
                                        @endif
                                        @if($info->status == 2 or $info->status == 3 or $info->status == 4 or $info->status == 5 or $info->status == 6 or $info->status == 7)
                                            <div class="md-step active">
                                                <div class="md-step-circle"><span><i class="fas fa-plane" style="color: white;"></i></span>
                                                </div>
                                                <div class="md-step-title">Application in Process</div>
                                                <div class="md-step-bar-left"></div>
                                                <div class="md-step-bar-right"></div>
                                            </div>
                                        @else
                                            <div class="md-step">
                                                <div class="md-step-circle"><span>2</span></div>
                                                <div class="md-step-title">Application in Processing</div>
                                                <div class="md-step-bar-left"></div>
                                                <div class="md-step-bar-right"></div>
                                            </div>
                                        @endif
                                        @if($info->status == 3 or $info->status == 4 or $info->status == 5 or $info->status == 6 or $info->status == 7)
                                            <div class="md-step active">
                                                <div class="md-step-circle"><span><i class="fas fa-plane" style="color: white;"></i></span>
                                                </div>
                                                <div class="md-step-title"> Missing Documents</div>
                                                <div class="md-step-bar-left"></div>
                                                <div class="md-step-bar-right"></div>
                                            </div>
                                        @else
                                            <div class="md-step">
                                                <div class="md-step-circle"><span>3</span></div>
                                                <div class="md-step-title"> Missing Documents</div>
                                                <div class="md-step-bar-left"></div>
                                                <div class="md-step-bar-right"></div>
                                            </div>
                                        @endif
                                        @if($info->status == 4 or $info->status == 5 or $info->status == 6 or $info->status == 7)
                                            <div class="md-step active">
                                                <div class="md-step-circle"><span><i class="fas fa-plane" style="color: white;"></i></span>
                                                </div>
                                                <div class="md-step-title">Submitted to embassy</div>
                                                <div class="md-step-bar-left"></div>
                                                <div class="md-step-bar-right"></div>
                                            </div>
                                        @else
                                            <div class="md-step">
                                                <div class="md-step-circle"><span>4</span></div>
                                                <div class="md-step-title">Submitted to embassy</div>
                                                <div class="md-step-bar-left"></div>
                                                <div class="md-step-bar-right"></div>
                                            </div>
                                        @endif
                                        @if( $info->status == 5 or $info->status == 6 or $info->status == 7)
                                            <div class="md-step active">
                                                <div class="md-step-circle"><span><i class="fas fa-plane" style="color: white;"></i></span>
                                                </div>
                                                <div class="md-step-title">Ready for delivery</div>
                                                <div class="md-step-bar-left"></div>
                                                <div class="md-step-bar-right"></div>
                                            </div>
                                        @else
                                            <div class="md-step">
                                                <div class="md-step-circle"><span>5</span></div>
                                                <div class="md-step-title">Ready for delivery</div>
                                                <div class="md-step-bar-left"></div>
                                                <div class="md-step-bar-right"></div>
                                            </div>
                                        @endif
                                        @if($info->status == 6)
                                                <div class="md-step active">
                                                    <div class="md-step-circle"><span><i class="fas fa-plane" style="color: white;"></i></span>
                                                    </div>
                                                    <div class="md-step-title">Delivered</div>
                                                    <div class="md-step-bar-left"></div>
                                                    <div class="md-step-bar-right"></div>
                                                </div>
                                              @elseif($info->status == 7)
                                                <div class="md-step active">
                                                    <div class="md-step-circle"><span><i class="fas fa-plane" style="color: white;"></i></span>
                                                    </div>
                                                    <div class="md-step-title">Reject</div>
                                                    <div class="md-step-bar-left"></div>
                                                    <div class="md-step-bar-right"></div>
                                                </div>
                                        @else
                                            <div class="md-step">
                                                <div class="md-step-circle"><span>6</span></div>
                                                <div class="md-step-title">Delivered</div>
                                                <div class="md-step-bar-left"></div>
                                                <div class="md-step-bar-right"></div>
                                            </div>
                                        @endif
                                    </div>
                                    <table style="border: none !important;">
                                        <tr>
                                            <td style="width: 20%;">
                                                <div><span>{{__('Reg No')}}{{' :'}}</span><span> {{$info->id}}</span></div>
                                                <div><span>{{__('Application ID')}}{{' :'}}</span><span> {{$info->uniqueKey}}</span></div>
                                                <div>
                                                    <span>{{__('Application Date')}}{{' :'}}</span><span> {{$info->created_at->format('Y-m-d')}}</span>
                                                </div>
                                            </td>
                                            <td class="text-center" style="width: 60%;">

                                                @if(!empty($info->barcode))
                                                    <img
                                                        src="data:image/png;base64, {!! DNS1D::getBarcodePNG($info->barcode, 'C128') !!}"
                                                        alt="barcode">
                                                    <br><b style="font-size: 20px">{{$info->barcode}}</b>
                                                @endif

                                                <h2 class="name m-0" style="text-transform: uppercase;">
                                                    <b>{{ config('settings.title') ? config('settings.title') : env('APP_NAME') }}</b>
                                                </h2>
                                                @if(config('settings.address'))
                                                    <h3 style="font-weight: normal;margin:0;">{{ config('settings.address') }}</h3>
                                                @endif
                                                <div
                                                    style="width: 250px;background:#036;color:white;font-weight:bolder;margin:5px auto 0 auto;padding: 5px 0;border-radius: 15px;text-align:center;">{{__('Application Details')}}</div>
                                            </td>
                                            @if($info->walkIn_app_type == 3)
                                            <td>
                                                @if(!empty($info->po_file))
                                                 <span>
                                                     <p>P.O File: <a href="{{('storage/material/'.$info->po_file)}}" download style="color: #f96302!important;">{{$info->po_file}}</a></p>
                                                </span>
                                                @endif
                                            </td>
                                            @endif
                                        </tr>
                                    </table>
                                    <div style="width: 100%;height:3px;border-top:1px solid #036;border-bottom:1px solid #036;"></div>
                                    <div class="col-md-12 py-5">
                                        <table class="table table-bordered" id="bill_table">
                                            <thead>
                                                <tr style="background: #036;color: ghostwhite;text-align: center;">
                                                    <td>Applicant Name</td>
                                                    <td>{{__('Passport Number')}}</td>
                                                    <td>{{__('Phone Number')}}</td>
                                                    <td>{{__('Email')}}</td>
                                                    <td>{{__('Visa Type')}}</td>
                                                    <td>{{__('Visa Category')}}</td>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            <tr style="background: ivory;">
                                                <td>{{$info->p_name}}</td>
                                                <td>
                                                    @foreach($info->passports as $row)
                                                       <span >{{$row->passport_no}}</span> <br>
                                                    @endforeach
                                                </td>
                                                <td>{{$info->phone}}</td>
                                                <td>{{$info->email}}</td>
                                                <td>{{$info->visaType ? $info->visaType->visa_type : ''}}</td>
                                                <td>{{$info->checklist ? $info->checklist->title : ''}}</td>
                                            </tr>

                                                @if($info->walkInAppChecklists)
                                                    @foreach($info->walkInAppChecklists as $row)
                                                        <tr>
                                                              <td colspan="4">{{$row->walkinchecklistCat->checklist_name}} -</td>
                                                              <td colspan="2" class="text-center">
                                                                  @if(!empty($row->walkinAppFiles))
                                                                    <span>
{{--                                                                        <a href="{{$row->walkinAppFiles ? ('storage/material/'.$row->walkinAppFiles->file) : ''}}"{{$row->walkinAppFiles->file}} download>--}}
{{--                                                                          <img src="{{$row->walkinAppFiles ? ('storage/material/'.$row->walkinAppFiles->file) : ''}}"  height="80px" width="80px" alt="image"><br>{{$row->walkinAppFiles->file}}--}}
{{--                                                                        </a>--}}
                                                                         <p> <a href="{{('storage/material/'.$row->walkinAppFiles->file)}}" download style="color: #f96302!important;">{{$row->walkinAppFiles->file}}</a></p>
                                                                    </span>
                                                                  @endif
                                                              </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                        @if($info->walkIn_app_type != 3 and $info->app_status == 3)
                                        <table style="border: none;">
                                            <thead>
                                                    <h3>Missing Document : </h3>
                                            </thead>
                                            <tbody>
                                            @foreach($info->missingFiles as $key=>$row)
                                            <tr>
                                                <td style="border: none;">
                                                    <span>
                                                         <p>{{$key + 1}} :   <a href="{{('storage/material/'.$row->missing_file)}}" download style="color: #f96302!important;">{{$row->missing_file}}</a></p>
                                                    </span>
                                                </td>
                                            </tr>
                                            @endforeach
                                            </tbody>
                                        </table>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="col-md-12">
                    <p style="text-align: center;font-size: 19px;font-weight: 700;border-bottom: 1px solid;">Application Log</p>
                    <div class="row mt-3">
                        @foreach($info->statusLogs as $row)
                            @if($row->application_status)
                                <div class="col-md-1">
                                    <p style="font-size: 12px;">{{$row->application_status ? WALKIN_APP_LOG_STATUS_VALUE[$row->application_status] : ''}} By <b>{{$row->changeBy->name ?? ''}}</b></p>
                                    <p style="font-size: 12px;">Date: {{$row->status_date ?? ''}}</p>
                                    <p style="font-size: 12px;">Time: {{$row->status_time ?? ''}}</p>
                                </div>

                                <div class="col-md-1">
                                    <br>
                                    <br>
                                    <br>
                                    <i class="fas fa-arrow-right" style="color: black"></i>
                                </div>
                            @endif
                        @endforeach
                        @foreach($info->statusLogs as $row)
                            @if($row->status)
                                <div class="col-md-1">
                                    <p style="font-size: 12px;">{{$row->status ? WALKIN_APP_STATUS_VALUE[$row->status] : ''}} By <b>{{$row->changeBy->name ?? ''}}</b></p>
                                    <p style="font-size: 12px;">Date: {{$row->status_date ?? ''}}</p>
                                    <p style="font-size: 12px;">Time: {{$row->status_time ?? ''}}</p>
                                </div>

                                <div class="col-md-1">
                                    <br>
                                    <br>
                                    <br>
                                    <i class="fas fa-arrow-right" style="color: black"></i>
                                </div>
                            @endif
                        @endforeach
{{--      passport Ready For delivery Section--}}
                        @foreach($info->passports as $row)
                            @if($row->ready_for_delivery_status == 1)
                                <div class="col-md-1">
                                    <p style="font-size: 12px;">{{WALKIN_APP_PP_RFD_VALUE[$row->ready_for_delivery_status]}} {{$row->passport_no}} By <b>{{$row->readyForDeliveryBy ? $row->readyForDeliveryBy->name : ''}}</b></p>
                                    <p style="font-size: 12px;">Date: {{$row->ready_for_delivery_date ?? ''}}</p>
                                    <p style="font-size: 12px;">Time: {{$row->ready_for_delivery_time ?? ''}}</p>
                                </div>

                                <div class="col-md-1">
                                    <br>
                                    <br>
                                    <br>
                                    <i class="fas fa-arrow-right" style="color: black"></i>
                                </div>
                            @endif
                        @endforeach
 {{--      passport Delivered Section--}}
                            @foreach($info->passports as $row)
                                @if($row->deliverd_status == 1)
                                    <div class="col-md-1">
                                        <p style="font-size: 12px;">{{WALKIN_APP_PP_D_VALUE[$row->deliverd_status]}} {{$row->passport_no}} By <b>{{$row->deliveredBy ? $row->deliveredBy->name : ''}}</b></p>
                                        <p style="font-size: 12px;">Date: {{$row->delivered_date ?? ''}}</p>
                                        <p style="font-size: 12px;">Time: {{$row->delivered_time ?? ''}}</p>
                                    </div>

                                    <div class="col-md-1">
                                        <br>
                                        <br>
                                        <br>
                                        <i class="fas fa-arrow-right" style="color: black"></i>
                                    </div>
                                @endif
                            @endforeach

                        @foreach($info->payments as $row)
                        <div class="col-md-1">
                            <p style="font-size: 12px;">Payment By <b>{{$row->payBy ? $row->payBy->name : ''}}</b></p>
                            <p style="font-size: 12px;">Payment : {{WALKIN_APP_PAYMENT_STATUS[$row->payment_status]}}</p>
                            <p style="font-size: 12px;">Paid Amount: {{$row->paid_amount}} <span>TK</span></p>
                            <p style="font-size: 12px;">Due Amount: {{$row->due_amount}} <span>TK</span></p>
                            <p style="font-size: 12px;">Date: {{$row->payment_date}}</p>
                            <p style="font-size: 12px;">Time: {{$row->payment_time}}</p>
                        </div>
                        <div class="col-md-1">
                            <br>
                            <br>
                            <br>
                            <br>
                            <i class="fas fa-arrow-right" style="color: black"></i>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
@push('scripts')

    <script src="//cdn.rawgit.com/rainabba/jquery-table2excel/1.1.0/dist/jquery.table2excel.min.js"></script>
    <script src="{{asset('js/jquery.printarea.js')}}"></script>
    <script>
        $(document).ready(function () {
            $(document).on('click', '#print-page', function () {
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
    <script type="text/javascript">
        $(function () {
            $("#btnExport").click(function () {
                $("#testTable").table2excel({
                    filename: "Table.xls"
                });
            });
        });
    </script>

@endpush

