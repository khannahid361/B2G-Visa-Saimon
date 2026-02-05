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
        background: #fff !important;
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
        padding: 10px;
        border-bottom: 1px solid #fff;
    }

    .invoice table td {
        padding: 10px;
        border-bottom: 1px solid #eee;
    }

    @page {
        margin: 5mm 5mm;
    }

    @media print {
        body,
        html {
            -webkit-print-color-adjust: exact !important;
            font-family: sans-serif;
        }

        .no_print {
            display: none !important;
        }
    }
</style>
@endpush

@section('content')
<div class="d-flex flex-column-fluid no_print">
    <div class="container-fluid">
        @php
        $datas_recep = \Modules\Ticket\Entities\Ticket::where('status', 3)->latest()->first();
        @endphp

        @if($datas_recep && $datas_recep->sender_id == Auth::user()->id)
            <div class="alert alert-success" id="mydiv" style="float: right;">Success! Executive Accepted!</div>
        @endif

        <div class="card card-custom gutter-b">
            <div class="card-header flex-wrap py-5">
                <div class="card-title no_print">
                    <h3 class="card-label">
                        <i class="{{ $page_icon }} text-primary"></i> {{ $sub_title }}
                    </h3>
                </div>
                <div class="card-toolbar no_print">
                    @if (permission('customer-group-assign-add'))
                        <a href="javascript:void(0);"
                            onclick="showFormModal('{{__('Customer Group Assign')}}','{{__('Save')}}')"
                            class="btn btn-primary btn-sm font-weight-bolder">
                            <i class="fas fa-plus-circle"></i> {{__('Add New')}}
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="card card-custom">
            <div class="card-header flex-wrap py-5">
                <form method="POST" id="form-filter" class="col-md-12 px-0">
                    <div class="row">
                        <x-form.textbox labelName="Customer Name" name="name" col="col-md-3" placeholder="Name" />
                        <div class="col-md-6">
                            <div style="margin-top:28px;">
                                <button id="btn-reset"
                                    class="btn btn-danger btn-sm btn-elevate btn-icon float-right"
                                    type="button" data-toggle="tooltip" title="{{__('Reset')}}">
                                    <i class="fas fa-undo-alt"></i>
                                </button>

                                <button id="btn-filter"
                                    class="btn btn-primary btn-sm btn-elevate btn-icon mr-2 float-right"
                                    type="button" data-toggle="tooltip" title="{{__('Search')}}">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="card-body no_print">
                <div class="table-responsive">
                    <table id="dataTable" class="table table-bordered table-hover align-middle">
                        <thead class="bg-primary text-white">
                            <tr>
                                <th>SL</th>
                                <th>Group Name</th>
                                <th>Price</th>
                                <th>Customer Name</th>
                                <th>Customer Phone</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@include('customer::assign.modal')
@endsection

@push('scripts')
<script src="{{ asset('js/datatables.bundle.js') }}" type="text/javascript"></script>
<script>
    var table;
    $(document).ready(function(){
        table = $('#dataTable').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            autoWidth: false,
            order: [],
            bInfo: true,
            bFilter: false,
            ordering: false,
            lengthMenu: [
                [10, 25, 50, 100, -1],
                [10, 25, 50, 100, "All"]
            ],
            pageLength: 25,
            language: {
                processing: `<i class="fas fa-spinner fa-spin fa-3x fa-fw text-primary"></i>`,
                emptyTable: '<strong class="text-danger">No Data Found</strong>',
                zeroRecords: '<strong class="text-danger">No Data Found</strong>'
            },
            ajax: {
                url: "{{ route('customer.group.assign.datatable.data') }}",
                type: "POST",
                data: function (data) {
                    data.name   = $("#form-filter #name").val();
                    data._token = _token;
                }
            },
            columnDefs: [
                {
                    targets: [0,1,2,3,4,5],
                    orderable: false,
                    className: "text-center align-middle"
                },
            ],
            dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6 text-right'B>>" +
                 "<'row'<'col-sm-12'tr>>" +
                 "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            buttons: [
                {
                    extend: 'colvis',
                    className: 'btn btn-secondary btn-sm text-white',
                    text: '{{__("Column")}}'
                },
                {
                    extend: 'print',
                    text: '<i class="fa fa-print"></i> Print',
                    className: 'btn btn-secondary btn-sm text-white',
                    title: "{{ $page_title }} List",
                    orientation: "landscape",
                    pageSize: "legal",
                    customize: function (win) {
                        $(win.document.body).addClass('bg-white');
                        $(win.document.body).find('table thead').css({'background': '#034d97', 'color': '#fff'});
                        $(win.document.body).find('h1').css({'text-align': 'center', 'font-size': '15px'});
                    },
                },
                {
                    extend: 'csv',
                    text: '<i class="fa fa-file-csv"></i> CSV',
                    className: 'btn btn-secondary btn-sm text-white',
                    title: "{{ $page_title }} List",
                    filename: "{{ strtolower(str_replace(' ','-',$page_title)) }}-list",
                },
                {
                    extend: 'excelHtml5',
                    text: '<i class="fa fa-file-excel"></i> Excel',
                    className: 'btn btn-secondary btn-sm text-white',
                    title: "{{ $page_title }} List",
                    filename: "{{ strtolower(str_replace(' ','-',$page_title)) }}-list",
                },
                {
                    extend: 'pdfHtml5',
                    text: '<i class="fa fa-file-pdf"></i> PDF',
                    className: 'btn btn-secondary btn-sm text-white',
                    title: "{{ $page_title }} List",
                    filename: "{{ strtolower(str_replace(' ','-',$page_title)) }}-list",
                    orientation: "landscape",
                    pageSize: "legal",
                    customize: function (doc) {
                        doc.defaultStyle.fontSize = 7;
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
            let url = "{{ route('customer.group.assign.store') }}";
            let id = $('#update_id').val();
            let method = id ? 'update' : 'add';
            store_or_update_data(table, method, url, formData);
        });

        $(document).on('click', '.edit_data', function () {
            let id = $(this).data('id');
            if (id) {
                $.ajax({
                    url: "{{ route('user.edit') }}",
                    type: "POST",
                    data: { id: id, _token: _token },
                    dataType: "JSON",
                    success: function (data) {
                        if(data.status == 'error'){
                            notification(data.status, data.message);
                        } else {
                            $('#store_or_update_form #update_id').val(data.id);
                            $('#store_or_update_form #customer_id').val(data.id);
                            $('#store_or_update_form #customer_group_id').val(data.customer_group_id);
                            $('#store_or_update_form .selectpicker').selectpicker('refresh');
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
            let id = $(this).data('id');
            let name = $(this).data('name');
            let row = table.row($(this).closest('tr'));
            let url = "{{ route('ticket.delete') }}";
            delete_data(id, url, table, row, name);
        });
    });
</script>
@endpush
