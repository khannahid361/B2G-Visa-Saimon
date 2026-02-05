@extends('layouts.app')

@section('title', $page_title)
@push('styles')
    <style>
        .my-checkbox {
            transform: scale(2);
            margin: 11px 20px;

        }

        input[type=checkbox], input[type=radio] {
            margin: 10px;
            margin-top: 1px\9;
            /* line-height: normal; */
        }
        /* FANCY COLLAPSE PANEL STYLES */

        .panel-heading {
            padding: 0;
            border:0;
        }
        .panel-title>a, .panel-title>a:active{
            display:block;
            padding:15px;
            color:#555;
            font-size:16px;
            font-weight:bold;
            text-transform:uppercase;
            letter-spacing:1px;
            word-spacing:3px;
            text-decoration:none;
            text-align: center;
        }
        .panel-heading  a:before {
            float: right;
            transition: all 0.5s;
        }
        .panel-heading.active a:before {
            -webkit-transform: rotate(180deg);
            -moz-transform: rotate(180deg);
            transform: rotate(180deg);
        }
        .panel-heading {
            padding: 0;
            border: 0;
            box-shadow: 2px 1px 6px 2px rgb(0 0 0 / 20%);
        }
        [data-toggle="collapse"] .fa:before {
            content: "\f139";
        }

        [data-toggle="collapse"].collapsed .fa:before {
            content: "\f13a";
        }

    </style>
@endpush
@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <!--begin::Card-->
            <div class="card card-custom">
                <div class="card-body">
                    <!--begin: Datatable-->
                    <div class="col-sm-12">
                        <form  method="POST" action="{{route('walkIn.po.invoice.save')}}" enctype="multipart/form-data">
                            @csrf
                            <div class="modal-body">
                                <input type="hidden" name="id" value="{{$payment_info->id}}">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="row">
                                            <div class="form-group col-md-6 total_po_price">
                                                <label for="total_po_price">{{__('Total Price')}}</label>
                                                <input type="text" class="form-control" value="{{$val}}"  id="net_total" readonly >
                                            </div>
                                            <div class="form-group col-md-6 po_invoice">
                                                <label for="po_invoice">{{__('Upload P.O Invoice')}}</label>
                                                <input type="file" name="po_invoice" class="form-control"  id="po_invoice" readonly >
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="form-group col-md-2 total_po_price"></div>
                                        <div class="form-group col-md-4 total_po_price">
                                            <div class="footer">
                                                <button type="submit" class="btn btn-primary btn-sm" id="payment-status-btn" style="float: right">P.O Invoice Upload</button>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4 po_invoice">

                                        </div>
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                    <!--end: Datatable-->
                </div>
            </div>
            <!--end::Card-->
        </div>
    </div>

@endsection

@push('scripts')
    <script>

    </script>


@endpush
