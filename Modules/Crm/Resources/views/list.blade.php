@extends('layouts.app')
@section('title', $page_title)
@push('styles')
    <link href="{{asset('css/daterangepicker.min.css')}}" rel="stylesheet" type="text/css"/>
    <style>
        .payment {
            display: none;
        }
    </style>
@endpush
@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <div class="card card-custom gutter-b">
                <div class="card-header flex-wrap py-5">
                    <div class="card-title">
                        <h3 class="card-label"><i class="{{ $page_icon }} text-primary"></i> {{ $sub_title }}</h3>
                    </div>
                </div>
            </div>
            <div class="card card-custom">
                <div class="card-header flex-wrap py-5">
                    <form method="POST" id="form-filter" class="col-md-12 px-0">
                        <div class="row">
                            <x-form.selectbox labelName="{{__('Destination')}}" name="visaType_id" id="visaType_id"
                                              col="col-md-3" class="">
                                @foreach ($visa_type as $row)
                                    <option value="{{ $row->id }}">{{ $row->visa_type }}</option>
                                @endforeach
                            </x-form.selectbox>
                            <x-form.textbox labelName="Application ID" name="uniqueKey" col="col-md-3"
                                            placeholder="Enter Application unique id"/>
                            <x-form.textbox labelName="Phone No." name="phone" col="col-md-2"
                                            placeholder="Enter phone number"/>

                            <x-form.textbox labelName="Barcode" name="barcode" col="col-md-3"
                                            placeholder="Enter Barcode"/>
                            <div class="col-md-1">
                                <div style="margin-top:28px;">
                                    <div style="margin-top:28px;">
                                        <button id="btn-reset"
                                                class="btn btn-danger btn-sm btn-elevate btn-icon float-right"
                                                type="button"
                                                data-toggle="tooltip" data-theme="dark" title="{{__('Reset')}}">
                                            <i class="fas fa-undo-alt"></i></button>

                                        <button id="btn-filter"
                                                class="btn btn-primary btn-sm btn-elevate btn-icon mr-2 float-right search"
                                                type="button"
                                                data-toggle="tooltip" data-theme="dark" title="{{__('Search')}}">
                                            <i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table id="dataTable" class="table table-bordered table-hover">
                                        <thead class="bg-primary">
                                            <tr>
                                                <th>SL</th>
                                                <th>Application ID</th>
                                                <th>Date</th>
                                                <th>Corporates Name</th>
                                                <th>Application Type</th>
                                                <th>Visa Type</th>
                                                <th> Name</th>
                                                <th>Email</th>
                                                <th>Phone</th>
                                                <th>Passport No</th>
                                                <th>Assign Collector</th>
                                                <th>Schedule Date</th>
                                                <th>Schedule Time</th>
                                                <th>P.O Invoice</th>
                                                <th>Visa Status</th>
                                                <th>Payment Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('crm::modal')
{{--    @include('crm::status_modal')--}}
    @include('walkin::openModal')
    @include('walkin::modal')
    @include('walkin::payment_modal')
@endsection
@push('scripts')
    <script src="{{asset('js/datatables.bundle.js')}}" type="text/javascript"></script>
    <script>
        var table;
        $(document).ready(function () {
            table = $('#dataTable').DataTable({
                "processing": true, //Feature control the processing indicator
                "serverSide": true, //Feature control DataTable server side processing mode
                "order": [], //Initial no order
                "responsive": true, //Make table responsive in mobile device
                "bInfo": true, //TO show the total number of data
                "bFilter": false, //For datatable default search box show/hide
                "lengthMenu": [
                    [5, 10, 15, 25, 50, 100, 1000, 10000, -1],
                    [5, 10, 15, 25, 50, 100, 1000, 10000, "All"]
                ],
                "pageLength": 25, //number of data show per page
                "language": {
                    processing: `<i class="fas fa-spinner fa-spin fa-3x fa-fw text-primary"></i> `,
                    emptyTable: '<strong class="text-danger">No Data Found</strong>',
                    infoEmpty: '',
                    zeroRecords: '<strong class="text-danger">No Data Found</strong>'
                },
                "ajax": {
                    "url": "{{route('onlineApp.datatable.data')}}",
                    "type": "POST",
                    "data": function (data) {
                        data.visaType_id = $("#form-filter #visaType_id").val();
                        data.phone = $("#form-filter #phone").val();
                        data.uniqueKey = $("#form-filter #uniqueKey").val();
                        data.barcode = $("#form-filter #barcode").val();
                        data._token = _token;

                    }
                },
                "columnDefs": [{
                    "targets"  : [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16],
                    "orderable": false,
                    "className": "text-center"
                },
                ],
                "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6' <'float-right'B>>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'<'float-right'p>>>",

                "buttons": [

                    {
                        'extend': 'colvis',
                        'className': 'btn btn-secondary btn-sm text-white',
                        'text': '{{__('Column')}}',
                        'columns': ':gt(0)'
                    },
                    {
                        "extend": 'print',
                        'text': '{{__('Print')}}',
                        'className': 'btn btn-secondary btn-sm text-white',
                        "title": "{{ $page_title }} List",
                        "orientation": "landscape", //portrait
                        "pageSize": "legal", //A3,A5,A6,legal,letter
                        customize: function (win) {
                            $(win.document.body).addClass('bg-white');
                            $(win.document.body).find('table thead').css({'background': '#034d97'});
                            $(win.document.body).find('table tfoot tr').css({'background-color': '#034d97'});
                            $(win.document.body).find('h1').css('text-align', 'center');
                            $(win.document.body).find('h1').css('font-size', '15px');
                            $(win.document.body).find('table').css('font-size', 'inherit');
                        },
                    },
                    {
                        "extend": 'csv',
                        'text': '{{__('CSV')}}',
                        'className': 'btn btn-secondary btn-sm text-white',
                        "title": "{{ $page_title }} List",
                        "filename": "{{ strtolower(str_replace(' ','-',$page_title)) }}-list",
                    },
                    {
                        "extend": 'excel',
                        'text': '{{__('Excel')}}',
                        'className': 'btn btn-secondary btn-sm text-white',
                        "title": "{{ $page_title }} List",
                        "filename": "{{ strtolower(str_replace(' ','-',$page_title)) }}-list",
                    },
                    {
                        "extend": 'pdf',
                        'text': '{{__('PDF')}}',
                        'className': 'btn btn-secondary btn-sm text-white',
                        "title": "{{ $page_title }} List",
                        "filename": "{{ strtolower(str_replace(' ','-',$page_title)) }}-list",
                        "orientation": "landscape", //portrait
                        "pageSize": "legal", //A3,A5,A6,legal,letter

                        customize: function (doc) {
                            doc.defaultStyle.fontSize = 7; //<-- set fontsize to 16 instead of 10
                            doc.styles.tableHeader.fontSize = 7;
                            doc.pageMargins = [5, 5, 5, 5];
                        }
                    },
                ],
            });
            $(document).on('click', '.edit_data', function () {
                let id = $(this).data('id');
                $('#store_or_update_form')[0].reset();
                $('#store_or_update_form').find('.is-invalid').removeClass('is-invalid');
                $('#store_or_update_form').find('.error').remove();
                if (id) {
                    $.ajax({
                        url: "{{route('onlineApp.assign')}}",
                        type: "POST",
                        data: { id: id,_token: _token},
                        dataType: "JSON",
                        success: function (data) {
                            console.log(data);
                            if(data.status == 'error'){
                                notification(data.status,data.message)
                            }else{
                                $('#store_or_update_form #update_id').val(data.id);
                                $('#store_or_update_form #phone').val(data.phone);

                                $('#store_or_update_modal').modal({
                                    keyboard: false,
                                    backdrop: 'static',
                                });
                                $('#store_or_update_modal .modal-title').html('<i class="fas fa-edit text-warning"></i> <span>{{__('Assign Collector')}}</span>');
                                $('#store_or_update_modal #save-btn').text('{{__('Assign Collector')}}');
                            }
                        },
                        error: function (xhr, ajaxOption, thrownError) { console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText); }
                    });
                }
            });
            $('#btn-filter').click(function () {
                // alert($("#form-filter #barcode").val())
                $('#approve_modal').show();
                table.ajax.reload();
            });
            $('#btn-reset').click(function () {
                $('#form-filter')[0].reset();
                $('#form-filter .selectpicker').selectpicker('refresh');
                table.ajax.reload();
            });
            //Show Status Change Modal
            $(document).on('click', '.change_status', function () {
                $('#approve_status_form #status_id').val($(this).data('id'));
                $('#approve_status_form #visa_status').val($(this).data('visa_status'));
                // $('#approve_status_form #note').val($(this).data('note'));
                $('#approve_status_form #phone').val($(this).data('phone'));
                $('#approve_status_form #email').val($(this).data('email'));
                $('#approve_status_form #visa_status.selectpicker').selectpicker('refresh');
                $('#approve_status_modal').modal({
                    keyboard: false,
                    backdrop: 'static',
                });
                $('#approve_status_modal .modal-title').html('<span>{{__('Change Status')}}</span>');
                $('#approve_status_modal #status-btn').text('{{__('Change Status')}}');

            });

            $(document).on('click', '#status-btn', function () {
                var status_id = $('#approve_status_form #status_id').val();
                var visa_status = $('#approve_status_form #visa_status').val();
                var note = $('#approve_status_form #note').val();
                var phone = $('#approve_status_form #phone').val();
                var email = $('#approve_status_form #email').val();
                // console.log(visa_status);
                $.ajax({
                    url: "{{route('walkIn.change.status')}}",
                    type: "POST",
                    data: {status_id: status_id, visa_status: visa_status, note: note,phone:phone,email:email, _token: _token},
                    dataType: "JSON",
                    beforeSend: function () {
                        $('#status-btn').addClass('kt-spinner kt-spinner--md kt-spinner--light');
                    },
                    complete: function () {
                        $('#status-btn').removeClass('kt-spinner kt-spinner--md kt-spinner--light');
                    },
                    success: function (data) {
                        notification(data.status, data.message);
                        if (data.status == 'success') {
                            $('#approve_status_modal').modal('hide');
                            table.ajax.reload(null, false);
                        }
                    },
                    error: function (xhr, ajaxOption, thrownError) {
                        console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
                    }
                });
            });

            //Show Payment Status Change Modal
            $('.payment_status').on('change', function () {
                if ($(this).val() === '3') {
                    $('.payment').show();
                    $('.n_total').show();
                    $('.discount').hide();
                    $('.total_price').hide();
                }
                if($(this).val() === '1'){
                    $('.n_total').show();
                    $('.payment').hide();
                    $('.discount').hide();
                    $('.total_price').hide();
                }
                if($(this).val() === '2'){
                    $('.n_total').show();
                    $('.discount').show();
                    $('.total_price').show();
                    $('.payment').hide();
                }if($(this).val() === '4'){
                    $('.n_total').show();
                    $('.discount').hide();
                    $('.total_price').hide();
                    $('.payment').hide();
                    $('.po_invoice').show();
                }
            });


            $(document).on('click', '.change_payment_status', function () {
                $('#approve_payment_status_form #payment_status_id').val($(this).data('id'));
                $('#approve_payment_status_form #visa_payment_status').val($(this).data('payment_status'));
                $('#approve_payment_status_form #net_total').val($(this).data('price'));
                $('#approve_payment_status_form #due_amount').val($(this).data('due_amount'));
                $('#approve_payment_status_form #visa_payment_status').val($(this).data('visa_status'));
                $('#approve_payment_status_form #po_invoice').val($(this).data('po_invoice'));
                $('#approve_payment_status_form #phone').val($(this).data('phone'));
                $('#approve_payment_status_form #email').val($(this).data('email'));
                // $('#approve_payment_status_form #visa_payment_status.selectpicker').selectpicker('refresh');
                $('#approve_Payment_status_modal').modal({
                    keyboard: false,
                    backdrop: 'static',
                });
                $('#approve_Payment_status_modal .modal-title').html('<span>{{__('Visa Payment')}}</span>');
                $('#approve_Payment_status_modal #payment-status-btn').text('{{__('Visa Payment')}}');

            });

            $(document).on('click', '#payment-status-btn', function () {
                var payment_status = $('#approve_payment_status_form #visa_payment_status option:selected').val();
                if(payment_status == ""){
                    notification('error', '{{__('Please select !')}}');
                }else{
                    var visa_payment_status = $('#approve_payment_status_form #visa_payment_status option:selected').val();
                    var payment_status_id   = $('#approve_payment_status_form #payment_status_id').val();
                    var net_total           = $('#approve_payment_status_form #net_total').val();
                    var paid_amount         = $('#approve_payment_status_form #paid_amount').val();
                    var due_amount          = $('#approve_payment_status_form #due_amount').val();
                    var discount            = $('#approve_payment_status_form #discount').val();
                    var payment_note        = $('#approve_payment_status_form #payment_note').val();
                    var po_invoice          = $('#approve_payment_status_form #po_invoice').val();
                    var phone               = $('#approve_payment_status_form #phone').val();
                    var email               = $('#approve_payment_status_form #email').val();

                    $.ajax({
                        url: "{{route('walkIn.payment.status')}}",
                        type: "POST",
                        data: {
                            payment_status_id: payment_status_id,
                            visa_payment_status: visa_payment_status,
                            net_total: net_total,
                            paid_amount: paid_amount,
                            payment_note: payment_note,
                            due_amount: due_amount,
                            discount: discount,
                            po_invoice: po_invoice,
                            phone: phone,
                            email: email,
                            _token: _token
                        },
                        dataType: "JSON",
                        beforeSend: function () {
                            $('#payment-status-btn').addClass('kt-spinner kt-spinner--md kt-spinner--light');
                        },
                        complete: function () {
                            $('#payment-status-btn').removeClass('kt-spinner kt-spinner--md kt-spinner--light');
                        },
                        success: function (data) {
                            notification(data.status, data.message);
                            if (data.status == 'success') {
                                $('#approve_Payment_status_modal').modal('hide');
                                table.ajax.reload(null, false);
                                location.reload();
                            }
                        },
                        error: function (xhr, ajaxOption, thrownError) {
                            console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
                        }
                    });
                }
            });
            $(document).on('click', '#payment-status-btn-close', function () {
                // $('#approve_Payment_status_modal').modal(r);
                // $('#approve_Payment_status_modal').removeData();
                location.reload();
            })

            // Calculate Amount ------------
            $(function () {
                $('#net_total, #paid_amount').keyup(function () {
                    var value1 = parseFloat($('#net_total').val()) || 0;
                    var value2 = parseFloat($('#paid_amount').val()) || 0;
                    $('#due_amount').val(value1 - value2);
                    if (value1 < value2) {
                        $('#paid_amount').val(value1);
                        $('#due_amount').val(0);
                        notification('error', '{{__('Paid amount cannot be bigger than total amount')}}');
                    }
                });
            });
            $(function () {
                $('#net_total, #discount').keyup(function () {
                    var value1 = parseFloat($('#net_total').val()) || 0;
                    var value2 = parseFloat($('#discount').val()) || 0;
                    $('#total_price').val(value1 - value2);
                    if (value1 < value2) {
                        $('#discount').val(0);
                        $('#total_price').val(0);
                        notification('error', '{{__('Discount amount cannot be bigger than total amount')}}');
                    }
                });
            });

        });
    </script>
@endpush

