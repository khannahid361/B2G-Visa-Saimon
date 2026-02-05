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
                            @if (permission('customer-group-assign-add'))
                                <a href="javascript:void(0);" onclick="showFormModal('{{__('Customer Group Assign')}}','{{__('Save')}}')" class="btn btn-primary btn-sm font-weight-bolder"><i class="fas fa-plus-circle"></i> {{__('Add New')}}</a>
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
                                        <th>Group Name</th>
                                        <th>Price</th>
                                        <th>Customer Name</th>
                                        <th>Customer Phone</th>
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
    @include('customer::assign.modal')
@endsection

@push('scripts')
<script src="{{asset('js/datatables.bundle.js')}}" type="text/javascript"></script>
<script>
    var table;
    $(document).ready(function(){
        table = $('#dataTable').DataTable({
            "processing": true, //Feature control the processing indicator
            "serverSide": true, //Feature control DataTable server side processing mode
            "order"     : [], //Initial no order
            "responsive": false, //Make table responsive in mobile device
            "bInfo"     : true, //TO show the total number of data
            "bFilter"   : false, //For datatable default search box show/hide
            "ordering"   : false, //For datatable default search box show/hide
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
                "url": "{{route('customer.group.assign.datatable.data')}}",
                "type": "POST",
                "data": function (data) {
                    data.name         = $("#form-filter #name").val();
                    data._token       = _token;
                }
            },
            "columnDefs": [{
                "targets"  : [0,1,2,3,4,5],
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

        $('#btn-filter').click(function () { table.ajax.reload(); });
        $('#btn-reset').click(function () {
            $('#form-filter')[0].reset();
            $('#form-filter .selectpicker').selectpicker('refresh');
            table.ajax.reload();
        });

        $(document).on('click', '#save-btn', function () {
            let form = document.getElementById('store_or_update_form');
            let formData = new FormData(form);
            let url = "{{route('customer.group.assign.store')}}";
            let id = $('#update_id').val();
            let method;
            if (id) {
                method = 'update';
            } else {
                method = 'add';
            }
            store_or_update_data(table, method, url, formData);
        });

        $(document).on('click', '.edit_data', function () {
            let id = $(this).data('id');
            $('#store_or_update_form')[0].reset();
            $('#store_or_update_form .select').val('');
            $('#store_or_update_form').find('.is-invalid').removeClass('is-invalid');
            $('#store_or_update_form').find('.error').remove();
            if (id) {
                $.ajax({
                    url: "{{route('user.edit')}}",
                    type: "POST",
                    data: { id: id,_token: _token},
                    dataType: "JSON",
                    success: function (data) {
                        console.log(data);
                        if(data.status == 'error'){
                            notification(data.status,data.message)
                        }else{
                            $('#store_or_update_form #update_id').val(data.id);
                            $('#store_or_update_form #customer_id').val(data.id);
                            $('#store_or_update_form #customer_group_id').val(data.customer_group_id);

                            $('#store_or_update_form .selectpicker').selectpicker('refresh');
                            $('#password, #password_confirmation').parents('.form-group').removeClass('required');
                            $('#store_or_update_modal').modal({
                                keyboard: false,
                                backdrop: 'static',
                            });
                            $('#store_or_update_modal .modal-title').html(
                                '<i class="fas fa-edit text-white"></i> <span>Edit ' + data.name + '</span>');
                            $('#store_or_update_modal #save-btn').text('Update');
                        }

                    },
                    error: function (xhr, ajaxOption, thrownError) {
                        console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
                    }
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
    });
</script>
@endpush
