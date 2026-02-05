@extends('layouts.app')

@section('title', $page_title)
@push('styles')
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
    <div class="d-flex flex-column-fluid no_print">
        <div class="container-fluid">
            <!--begin::Notice-->
            @php
                $datas_recep = \Modules\Ticket\Entities\Ticket::where('status',3)->latest()->first();
            @endphp
            @if($datas_recep != '')
                @if($datas_recep->sender_id == \Illuminate\Support\Facades\Auth::user()->id)
                    <div class="alert alert-success" id="mydiv" style="float: right">Success!Executive Accepted !</div>
                @endif
            @endif
            <div class="card card-custom gutter-b">
                <div class="card-header flex-wrap py-5">
                    <div class="card-title no_print">
                        <h3 class="card-label"><i class="{{ $page_icon }} text-primary"></i> {{ $sub_title }}</h3>
                    </div>
                    <div class="card-toolbar no_print">
                        <!--begin::Button-->
                        <div class="card-toolbar">
                            @if (permission('ticket-add'))
                                <a href="javascript:void(0);" onclick="showFormModal('{{__('Add New Token')}}','{{__('Save')}}')" class="btn btn-primary btn-sm font-weight-bolder"><i class="fas fa-plus-circle"></i> {{__('Add New')}}</a>
                            @endif
                        </div>
                        <!--end::Button-->
                    </div>
                </div>
            </div>
            <!--end::Notice-->
            <!--begin::Card-->
            <div class="card card-custom">
                <div class="card-header flex-wrap py-5">
                    <form method="POST" id="form-filter" class="col-md-12 px-0">
                        <div class="row">
                            <x-form.textbox labelName="Customer Name" name="name" col="col-md-3" placeholder="Name" />
                            <x-form.textbox labelName="Phone No" name="phone" col="col-md-3" placeholder="Phone No" />

                            <div class="col-md-6">
                                <div style="margin-top:28px;">
                                    <div style="margin-top:28px;">
                                        <button id="btn-reset" class="btn btn-danger btn-sm btn-elevate btn-icon float-right" type="button"
                                                data-toggle="tooltip" data-theme="dark" title="{{__('Reset')}}">
                                            <i class="fas fa-undo-alt"></i></button>

                                        <button id="btn-filter" class="btn btn-primary btn-sm btn-elevate btn-icon mr-2 float-right" type="button"
                                                data-toggle="tooltip" data-theme="dark" title="{{__('Search')}}">
                                            <i class="fas fa-search"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body no_print">
                    <!--begin: Datatable-->
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <div class="row">
                            <div class="col-sm-12">
                                <table id="dataTable" class="table table-bordered table-hover">
                                    <thead class="bg-primary">
                                    <tr>
                                        <th>SL</th>
                                        <th>Visa Department</th>
                                        <th>Customer Name</th>
                                        <th>Ticket Code</th>
                                        <th>Phone No</th>
                                        <th>Date</th>
                                        <th>Assigned Supervisor</th>
                                        <th>Assigned Executive</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!--end: Datatable-->
                </div>
            </div>
            <!--end::Card-->
        </div>
    </div>
    @include('ticket::modal')
    @include('ticket::view')
    @include('ticket::editModal')
@endsection

@push('scripts')
<script src="{{asset('js/datatables.bundle.js')}}" type="text/javascript"></script>
    <script>
        var table;
        $(document).ready(function(){

            // var d = document.getElementById("department_id");
            // d.className += " selectpicker";

            $(document).on('change','.departmentUser',function(){
                let html;
                let departmentId = $(this).find('option:selected').val();
                let receiverId   = $(this).data('receiver');
                if(departmentId != ''){
                    $.ajax({
                        url      : "{{url('department-wise-user')}}/" + departmentId,
                        type     : 'GET',
                        dataType : "JSON",
                        success  : function(data){
                            if(data != ''){
                                html = `<option value="">Select Please</option>`;
                                $.each(data, function(key, value) { html += '<option value="'+ value.department_id +'">'+ value.name +' - '+ value.employee_type +'</option>'; });
                                $('#'+ receiverId +'').empty();
                                $('#'+ receiverId +'').append(html);
                                $('.selectpicker').selectpicker('refresh');
                            }else{
                                $(".receiver_id").html('select');
                                notification('error','No Employee Set Under These Department');
                            }
                        }
                    });
                }
            });

            //Generate Visa Code
            $(document).on('click','#generate-code',function(){
                $.ajax({
                    url: "{{ route('ticket.generate.ticket.code') }}",
                    type: "GET",
                    dataType: "JSON",
                    beforeSend: function(){
                        $('#generate-code').addClass('spinner spinner-white spinner-right');
                    },
                    complete: function(){
                        $('#generate-code').removeClass('spinner spinner-white spinner-right');
                    },
                    success: function (data) {
                        data ? $('#store_or_update_form #ticket_code').val(data) : $('#store_or_update_form #ticket_code').val('');
                    },
                    error: function (xhr, ajaxOption, thrownError) {
                        console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
                    }
                });
            });

            table = $('#dataTable').DataTable({
                "processing": true, //Feature control the processing indicator
                "serverSide": true, //Feature control DataTable server side processing mode
                "order": [], //Initial no order
                "responsive": true, //Make table responsive in mobile device
                "bInfo": true, //TO show the total number of data
                "bFilter": false, //For datatable default search box show/hide
                "ordering": false, //For datatable default search box show/hide
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
                    "url": "{{route('ticket.datatable.data')}}",
                    "type": "POST",
                    "data": function (data) {
                        data.name     = $("#form-filter #name").val();
                        data.phone    = $("#form-filter #phone").val();
                        data._token   = _token;
                    }
                },

            });

            $('#btn-filter').click(function () {
                table.ajax.reload();
            });

            $('#btn-reset').click(function () {
                $('#form-filter')[0].reset();
                $('#form-filter .selectpicker').selectpicker('refresh');
                table.ajax.reload();
            });

            $(document).on('click', '#save-btn', function () {
                let form     = document.getElementById('store_or_update_form');
                let formData = new FormData(form);
                let url      = "{{route('ticket.store.or.update')}}";
                let id       = $('#update_id').val();
                let method;
                if (id) {
                    method = 'update';
                } else {
                    method = 'add';
                }
                $.ajax({
                    url: url,
                    type: "POST",
                    data: formData,
                    dataType: "JSON",
                    contentType: false,
                    processData: false,
                    cache: false,
                    beforeSend: function(){
                        $('#save-btn').addClass('spinner spinner-white spinner-right');
                    },
                    complete: function(){
                        $('#save-btn').removeClass('spinner spinner-white spinner-right');
                    },
                    success: function (data) {
                        $('#store_or_update_form').find('.is-invalid').removeClass('is-invalid');
                        $('#store_or_update_form').find('.error').remove();
                        if (data.status == false) {
                            $.each(data.errors, function (key, value) {
                                $('#store_or_update_form input#' + key).addClass('is-invalid');
                                $('#store_or_update_form textarea#' + key).addClass('is-invalid');
                                $('#store_or_update_form select#' + key).parent().addClass('is-invalid');
                                $('#store_or_update_form #' + key).parent().append(
                                    '<small class="error text-danger">' + value + '</small>');
                            });
                        } else {
                            notification(data.status, data.message);
                            if (data.status == 'success') {
                                if (method == 'update') {
                                    table.ajax.reload(null, false);
                                } else {
                                    table.ajax.reload();
                                }
                                $('#store_or_update_modal').modal('hide');

                            }
                        }
                    },
                    error: function (xhr, ajaxOption, thrownError) {
                        console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
                    }
                });
            });

            $(document).on('click', '.edit_data', function () {
                let id = $(this).data('id');
                $('#store_or_update_form')[0].reset();
                $('#store_or_update_form').find('.is-invalid').removeClass('is-invalid');
                $('#store_or_update_form').find('.error').remove();
                if (id) {
                    $.ajax({
                        url: "{{route('ticket.edit')}}",
                        type: "POST",
                        data: { id: id,_token: _token},
                        dataType: "JSON",
                        success: function (data) {
                            console.log(data);
                            if(data.status == 'error'){
                                notification(data.status,data.message)
                            }else{
                                $('#store_or_update_form #update_id').val(data.id);
                                $('#store_or_update_form #name,#store_or_update_form #old_name').val(data.name);
                                $('#store_or_update_form #ticket_code').val(data.ticket_code);
                                $('#store_or_update_form #phone').val(data.phone);
                                $('#store_or_update_form #department_id').val(data.department_id);
                                $('#store_or_update_form #receiver_id').val(data.receiver_id);
                                $('#store_or_update_form #purpose').val(data.purpose);

                                $('#store_or_update_form #department_id.selectpicker').selectpicker('refresh');
                                $('#store_or_update_form #receiver_id.selectpicker').selectpicker('refresh');

                                $('#store_or_update_modal').modal({
                                    keyboard: false,
                                    backdrop: 'static',
                                });
                                $('#store_or_update_modal .modal-title').html('<i class="fas fa-edit text-warning"></i> <span>{{__('Edit')}} ' + data.name + '</span>');
                                $('#store_or_update_modal #save-btn').text('{{__('Update')}}');
                            }
                        },
                        error: function (xhr, ajaxOption, thrownError) { console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText); }
                    });
                }
            });

            $(document).on('click', '.edit_data1', function () {
                let id = $(this).data('id');
                $('#store_or_update_forms')[0].reset();
                $('#store_or_update_forms').find('.is-invalid').removeClass('is-invalid');
                $('#store_or_update_forms').find('.error').remove();
                if (id) {
                    $.ajax({
                        url: "{{route('ticket.edit.child')}}",
                        type: "POST",
                        data: { id: id,_token: _token},
                        dataType: "JSON",
                        success: function (data) {
                            // console.log(data)
                            if(data.status == 'error'){
                                notification(data.status,data.message)
                            }else{
                                console.log(data);
                                $('#store_or_update_forms #update_id').val(data.id);
                                $('#store_or_update_forms #name,#store_or_update_forms #old_name').val(data.name);
                                $('#store_or_update_forms #ticket_code').val(data.ticket_code);
                                $('#store_or_update_forms #phone').val(data.phone);
                                $('#store_or_update_forms #purpose').val(data.purpose);
                                $('#store_or_update_forms #department_id').val(data.department_id);
                                $('#store_or_update_forms #receiver_id').val(data.receiver_id);
                                $('#store_or_update_forms #ticket_receiver_id').val(data.ticket_receiver_id);

                                $('#store_or_update_forms #ticket_receiver_id.selectpicker').selectpicker('refresh');

                                $('#store_or_update_forms #department_id.selectpicker').selectpicker('refresh');
                                // $('#store_or_update_form #receiver.selectpicker').selectpicker('refresh');

                                $('#store_or_update_modals').modal({
                                    keyboard: false,
                                    backdrop: 'static',
                                });
                                $('#store_or_update_modals .modal-title').html('<i class="fas fa-edit text-warning"></i> <span>{{__('Assign')}}</span>');
                                $('#store_or_update_modals #save-btn').text('{{__('Update')}}');
                            }
                        },
                        error: function (xhr, ajaxOption, thrownError) { console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText); }
                    });
                }
            });

            $(document).on('click', '.delete_data', function () {
                let id    = $(this).data('id');
                let name  = $(this).data('name');
                let row   = table.row($(this).parent('tr'));
                let url   = "{{ route('ticket.delete') }}";
                delete_data(id, url, table, row, name);
            });

            $(document).on('click', '.view_data', function () {
                let id = $(this).data('id');
                if (id) {
                    $.ajax({
                        url: "{{route('ticket.view')}}",
                        type: "POST",
                        data: { id: id,_token: _token},
                        success: function (data) {
                            $('#view_modal #view-data').html('');
                            $('#view_modal #view-data').html(data);
                            $('#view_modal').modal({
                                keyboard: false,
                                backdrop: 'static',
                            });
                        },
                        error: function (xhr, ajaxOption, thrownError) {
                            console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
                        }
                    });
                }
            });

        });
    </script>

    <script>
    $(document).ready(function() {
        Pusher.logToConsole = true;
        var pusher = new Pusher('3eb86b1d629138656fea', {
            cluster: 'ap2'
        });
        var channel = pusher.subscribe('symon_visa_management');
        channel.bind('ticketNotification', function(data) {
            // alert(JSON.stringify(data));
            if(data.from) {
                let pending = parseInt($('#' + data.from).find('.pending').html());
                $('#' + data.from).find('.pending').html(pending + 1);
            }
        });
    });

</script>

<script src="{{asset('js/jquery.printarea.js')}}"></script>
<script>
    $(document).ready(function () {
        $(document).on('click','#print-page',function(){
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
    setTimeout(function() {
        $('#mydiv').fadeOut('fast');
    }, 5000); // <-- time in milliseconds
</script>
@endpush
