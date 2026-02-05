@extends('layouts.app')
@section('title', $page_title)
@push('styles')
<link href="{{asset('css/daterangepicker.min.css')}}" rel="stylesheet" type="text/css" />
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
                            col="col-md-4" class="">
                            @foreach ($visa_type as $row)
                            <option value="{{ $row->id }}">{{ $row->visa_type }}</option>
                            @endforeach
                        </x-form.selectbox>
                        <x-form.textbox labelName="Application ID" name="uniqueKey" col="col-md-4"
                            placeholder="Enter Application unique id" />
                        <x-form.textbox labelName="Phone No." name="phone" col="col-md-4"
                            placeholder="Enter phone number" />

                        <x-form.textbox labelName="Barcode" name="barcode" col="col-md-4" placeholder="Enter Barcode" />
                        <x-form.textbox labelName="Passport No" name="passport_no" col="col-md-4"
                            placeholder="Enter Passport No" />
                        <div class="col-md-4">
                            <div style="margin-top:28px;">
                                <div style="margin-top:28px;">

                                    <button id="btn-reset"
                                        class="btn btn-danger btn-sm btn-elevate btn-icon float-right" type="button"
                                        data-toggle="tooltip" data-theme="dark" title="{{__('Reset')}}">
                                        <i class="fas fa-undo-alt"></i>
                                    </button>
                                    <button id="btn-filter"
                                        class="btn btn-primary btn-sm btn-elevate btn-icon mr-2 float-right search"
                                        type="button" data-toggle="tooltip" data-theme="dark" title="{{__('Search')}}">
                                        <i class="fas fa-search"></i>
                                    </button>
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
                                            <th>General / Travel Agent / Corporates Name</th>
                                            <th>Application Status</th>
                                            <th>Application Type</th>
                                            <th>Destination</th>
                                            <th> Name</th>
                                            <th>Email</th>
                                            <th>Phone</th>
                                            <th>Passport No</th>
                                            <th>P.O Invoice</th>
                                            <th>Visa Status</th>
                                            <th>Payment Status</th>
                                            <th>Payment Method</th>
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
@include('walkin::openModal')
@include('walkin::modal')
@include('walkin::payment_modal')
@include('walkin::full_payment_modal')
@include('walkin::partial_payment_modal')
{{-- @include('walkin::barcode_show')--}}

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
                    "url": "{{route('walkIn.datatable.data')}}",
                    "type": "POST",
                    "data": function (data) {
                        data.visaType_id    = $("#form-filter #visaType_id").val();
                        data.phone          = $("#form-filter #phone").val();
                        data.uniqueKey      = $("#form-filter #uniqueKey").val();
                        data.barcode        = $("#form-filter #barcode").val();
                        data.passport_no    = $("#form-filter #passport_no").val();
                        data._token         = _token;

                    }
                },
                "columnDefs": [{
                    "targets"  : [0,1,2,3,4,5,6,7,8,9,10,11,12,13,14,15],
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

            $('#btn-filter').click(function () {
                // alert($("#form-filter #barcode").val())
                $('#approve_modal').show();
                table.ajax.reload();
            });
            $(document).on('click', '.search', function () {
                var barcode = $("#form-filter #barcode").val()
                // alert(barcode)
                if (barcode.length != 0) {

                    $('#approve_status_form #barcode_id').val(barcode);
                    // $('#approve_status_form #department_id').val(a);

                    $('#approve_barcode_modal').modal({
                        keyboard: false,
                        backdrop: 'static',

                    });
                }

            });
            $(document).on('click', '.search', function () {
                let html;
                var barcode = $("#form-filter #barcode").val()
                let receiverId = $(this).data('receiver');
                if (barcode != '') {
                    $.ajax({
                        url: "{{url('walkIn/barcodeData')}}/" + barcode,
                        type: 'GET',
                        dataType: "JSON",
                        success: function (data) {
                            console.log(data)
                            if (data.status == 'error') {
                                notification(data.status, data.message)
                            } else {
                                $('#approve_status_form #name').val(data.p_name);
                                $('#approve_status_form #passport_id').val(data.passport_number);
                                $('#approve_status_form #visa_type_id').val(data.type);
                                if (data.status == 1) {
                                    $('#approve_status_form #status').val('Submitted Online');
                                } else if (data.status == 2) {
                                    $('#approve_status_form #status').val('Processing');
                                } else if (data.status == 3) {
                                    $('#approve_status_form #status').val('Reject');
                                } else {
                                    $('#approve_status_form #status').val('Approved');
                                }

                                // $('#approve_status_form #barcode_id').val(barcode);
                            }
                        }
                    });
                }
            });
            $('#btn-reset').click(function () {
                $('#form-filter')[0].reset();
                $('#form-filter .selectpicker').selectpicker('refresh');
                table.ajax.reload();
            });

            $(document).on('change','.accountType',function(){
                let html;
                let accountType = $(this).find('option:selected').val();
                let accountId   = $(this).data('account');
                if(accountType != ''){
                    $.ajax({
                        url      : "{{url('get-accounts')}}/" + accountType,
                        type     : 'GET',
                        dataType : "JSON",
                        success  : function(data){
                            if(data != ''){
                                html = `<option value="">Select Please</option>`;
                                $.each(data, function(key, value) { html += '<option value="'+ value.account_id +'">'+ value.name +' </option>'; });
                                $('#'+ accountId +'').empty();
                                $('#'+ accountId +'').append(html);
                                $('.selectpicker').selectpicker('refresh');
                            }else {
                                let previousOptions = $('#' + accountId + '').data('previous-options');
                                $('#' + accountId + '').empty().append(previousOptions);
                                $('.selectpicker').selectpicker('refresh');
                                notification('error', 'No Account Set Under These Account Type');
                            }
                        }
                    });
                }
            });

            $(document).on('click', '.delete_data', function () {
                let id = $(this).data('id');
                let name = $(this).data('name');
                let row = table.row($(this).parent('tr'));
                let url = "{{ route('walkIn.delete') }}";
                delete_data(id, url, table, row, name);
            });

            function multi_delete() {
                let ids = [];
                let rows;
                $('.select_data:checked').each(function () {
                    ids.push($(this).val());
                    rows = table.rows($('.select_data:checked').parents('tr'));
                });
                if (ids.length == 0) {
                    Swal.fire({
                        type: 'error',
                        title: 'Error',
                        text: '{{__('file.Please checked at least one row of table!')}}',
                        icon: 'warning',
                    });
                } else {
                    let url = "{{route('checklist.bulk.delete')}}";
                    bulk_delete(ids, url, table, rows);
                }
            }

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
                    $('.common').show();
                    $('.n_total').show();
                    $('.discount').hide();
                    $('.discounted-by').hide();
                    $('.available-discount').hide();
                    $('.total_price').hide();
                }
                if($(this).val() === '1'){
                    $('.n_total').show();
                    $('.common').show();
                    $('.payment').hide();
                    $('.discount').hide();
                    $('.discounted-by').hide();
                    $('.available-discount').hide();
                    $('.total_price').hide();
                }
                if($(this).val() === '2'){
                    $('.n_total').show();
                    $('.common').show();
                    $('.discount').show();
                    $('.discounted-by').show();
                    $('.available-discount').show();
                    $('.total_price').show();
                    $('.payment').hide();
                }if($(this).val() === '4'){
                    $('.n_total').show();
                    $('.common').show();
                    $('.discount').hide();
                    $('.discounted-by').hide();
                    $('.available-discount').hide();
                    $('.total_price').hide();
                    $('.payment').hide();
                    $('.po_invoice').show();
                }
                $('.common-field').val('0');
            });


            $(document).on('click', '.change_payment_status', function () {
                console.log('You are in click .change_payment_status function');
                
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
                let serviceCharge = Number($(this).data('service_charge'));
                let netTotal = Number($(this).data('visa_fee'));
                let applicationId = $(this).data('id');
                let url = "{{ route('walkIn.get.max.discount') }}";
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {id: applicationId, _token: _token},
                    dataType: "JSON",
                    success: function (response) {
                        netTotal = netTotal - (Number(response.total_visa_fee) || 0);
                        console.log(netTotal);
                        
                        let paidCharge = Number(response.total_service_charge)||0;
                        $('#approve_payment_status_form #available_discount').val(serviceCharge - paidCharge);
                        $('#approve_payment_status_form #payable_service_charge').val(serviceCharge - paidCharge);
                        $('#approve_payment_status_form #payable_visa_fee').val(netTotal);
                    }
                });
            });

            $(document).on('click', '#payment-status-btn', function () {
                var payment_status = $('#approve_payment_status_form #visa_payment_status').val();

                if (payment_status === "") {
                    notification('error', '{{ __("Please select!") }}');
                    return; // stop further execution
                }

                var form = $('#approve_payment_status_form');

                var data = {
                    payment_status_id: form.find('#payment_status_id').val(),
                    account_type: form.find('#account_type').val(),
                    account_id: form.find('#account_id').val(),
                    visa_payment_status: payment_status,
                    net_total: form.find('#net_total').val(),
                    payment: form.find('#total_price').val(),
                    payment_note: form.find('#payment_note').val(),
                    due_amount: form.find('#due_amount').val(),
                    discount: form.find('#discount').val(),
                    po_invoice: form.find('#po_invoice').val(),
                    phone: form.find('#phone').val(),
                    email: form.find('#email').val(),
                    visa_fee: form.find('#visa_fee').val(),
                    service_charge: form.find('#service_charge').val(),
                    discounted_by: form.find('#discounted_by').val(),
                    _token: "{{ csrf_token() }}"
                };

                $.ajax({
                    url: "{{ route('walkIn.payment.status') }}",
                    type: "POST",
                    data: data,
                    dataType: "JSON",
                    beforeSend: function () {
                        $('#payment-status-btn')
                            .addClass('kt-spinner kt-spinner--md kt-spinner--light')
                            .prop('disabled', true);
                    },
                    complete: function () {
                        $('#payment-status-btn')
                            .removeClass('kt-spinner kt-spinner--md kt-spinner--light')
                            .prop('disabled', false);
                    },
                    success: function (data) {
                        notification(data.status, data.message);

                        if (data.status === 'success') {
                            $('#approve_Payment_status_modal').modal('hide');
                            if (typeof table !== 'undefined' && table.ajax) {
                                table.ajax.reload(null, false);
                            }
                            location.reload();
                        } else if (data.errors) {
                            form.find('.error.text-danger').remove(); // clear old errors
                            form.find('.is-invalid').removeClass('is-invalid');

                            $.each(data.errors, function (key, value) {
                                var field = form.find('#' + key);
                                field.addClass('is-invalid');
                                field.parent().append('<small class="error text-danger">' + value + '</small>');
                            });
                        }
                    },
                    error: function (xhr, ajaxOption, thrownError) {
                        console.error(thrownError + '\n' + xhr.statusText + '\n' + xhr.responseText);
                    }
                });
            });


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
                $('#service_charge, #visa_fee').keyup(function () {
                    let serviceCharge = parseFloat($('#service_charge').val()) || 0;
                    let visaFee = parseFloat($('#visa_fee').val()) || 0;
                    let payableServiceCharge = parseFloat($('#payable_service_charge').val()) || 0;
                    let payableVisaFee = parseFloat($('#payable_visa_fee').val()) || 0;
                    let discount = parseFloat($('#discount').val()) || 0;
                    let maximumDiscount = parseFloat($('#available_discount').val()) || 0;
                    let netTotal = Number($('#net_total').val());
                    if (serviceCharge > payableServiceCharge) {
                        serviceCharge = payableServiceCharge;
                        $('#service_charge').val(payableServiceCharge);
                        notification('error', '{{__('Service charge cannot be bigger than maximum service charge')}}');
                    }
                    if (visaFee > payableVisaFee) {
                        visaFee = payableVisaFee;
                        $('#visa_fee').val(payableVisaFee);
                        notification('error', '{{__('Visa fee cannot be bigger than maximum visa fee')}}');
                    }
                    if (discount > maximumDiscount) {
                        discount = maximumDiscount;
                        $('#discount').val(maximumDiscount);
                        notification('error', '{{__('Discount amount cannot be bigger than maximum discount amount')}}');
                    }
                    let dueAmount = netTotal - (serviceCharge + visaFee - discount);
                    $('#due_amount').val(dueAmount);
                    let totalPrice = serviceCharge + visaFee;
                    $('#total_price').val(totalPrice);
                });
            });

            $(function () {  
                $('#discount').keyup(function () {
                    let discount = parseFloat($('#discount').val()) || 0;
                    let maximumDiscount = parseFloat($('#available_discount').val()) || 0;
                    let payableServiceCharge = parseFloat($('#payable_service_charge').val()) || 0;
                    let payableVisaFee = parseFloat($('#payable_visa_fee').val()) || 0;
                    if (discount > maximumDiscount) {
                        discount = maximumDiscount;
                        $('#discount').val(maximumDiscount);
                        notification('error', '{{__('Discount amount cannot be bigger than maximum discount amount')}}');
                    }
                    let totalPrice = payableServiceCharge - discount;
                    $('#visa_fee').val(payableVisaFee);
                    $('#service_charge').val(totalPrice).trigger('keyup');
                });
            });
        });

</script>
@endpush