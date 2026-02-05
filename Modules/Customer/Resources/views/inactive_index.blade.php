@extends('layouts.app')

@section('title', $page_title)
@push('styles')
@endpush

@section('content')
    <div class="d-flex flex-column-fluid">
        <div class="container-fluid">
            <!--begin::Notice-->
            <div class="card card-custom gutter-b">
                <div class="card-header flex-wrap py-5">
                    <div class="card-title">
                        <h3 class="card-label"><i class="{{ $page_icon }} text-primary"></i> {{ $sub_title }}</h3>
                    </div>
                </div>
            </div>
            <!--end::Notice-->
            <!--begin::Card-->
            <div class="card card-custom">
                <div class="card-header flex-wrap py-5">
                    <form method="POST" id="form-filter" class="col-md-12 px-0">
                        <div class="row">
                            <x-form.textbox labelName="{{__('Name')}}" name="name" col="col-md-3" placeholder="{{__('Name')}}" />
                            <x-form.textbox labelName="{{__('Phone No')}}." name="phone" col="col-md-3" placeholder="{{__('Phone No')}}" />
                            <x-form.textbox labelName="{{__('Email')}}" name="email" col="col-md-3" placeholder="{{__('Email')}}" />

                            <div class="col-md-3">
                                <div style="margin-top:28px;">
                                    <div style="margin-top:28px;">
                                        <button id="btn-reset" class="btn btn-danger btn-sm btn-elevate btn-icon float-right" type="button"
                                                data-toggle="tooltip" data-theme="dark" title="{{__('Reset')}}">
                                            <i class="fas fa-undo-alt"></i>
                                        </button>

                                        <button id="btn-filter" class="btn btn-primary btn-sm btn-elevate btn-icon mr-2 float-right" type="button"
                                                data-toggle="tooltip" data-theme="dark" title="{{__('Search')}}">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <!--begin: Datatable-->
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <div class="row">
                            <div class="col-sm-12">
                                <table id="dataTable" class="table table-bordered table-hover">
                                    <thead class="bg-primary">
                                    <tr>
                                        <th>SL</th>
                                        <th>Avatar</th>
                                        <th>Application Type</th>
                                        <th>Name</th>
                                        <th>Phone No</th>
                                        <th>Email</th>
                                        <th>Gender</th>
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
    @include('customer::view')
    @include('customer::modal')
@endsection

@push('scripts')
    <script src="{{asset('js/datatables.bundle.js')}}" type="text/javascript"></script>
    <script>
        var table;
        $(document).ready(function(){

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
                    emptyTable: '<strong class="text-danger">{{__('file.No Data Found')}}</strong>',
                    infoEmpty: '',
                    zeroRecords: '<strong class="text-danger">{{__('file.No Data Found')}}</strong>'
                },
                "ajax": {
                    "url": "{{route('customer.datatable.inactive.data')}}",
                    "type": "POST",
                    "data": function (data) {
                        data.name     = $("#form-filter #name").val();
                        data.phone    = $("#form-filter #phone").val();
                        data.email    = $("#form-filter #email").val();
                        data.status   = $("#form-filter #status").val();
                        data._token   = _token;
                    }
                },
                "columnDefs": [{
                    "targets"  : [0,1,2,3,4,5,6,7],
                    "orderable": false,
                    "className": "text-center"
                },
                ],
                "dom": "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6' <'float-right'B>>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'<'float-right'p>>>",
            });

            $('#btn-filter').click(function () {
                table.ajax.reload();
            });

            $('#btn-reset').click(function () {
                $('#form-filter')[0].reset();
                $('#form-filter .selectpicker').selectpicker('refresh');
                table.ajax.reload();
            });

            $(document).on('click', '.delete_data', function () {
                let id    = $(this).data('id');
                let name  = $(this).data('name');
                let row   = table.row($(this).parent('tr'));
                let url   = "{{ route('customer.delete') }}";
                delete_data(id, url, table, row, name);
            });

            $(document).on('click', '.view_data', function () {
                let id = $(this).data('id');
                if (id) {
                    $.ajax({
                        url: "{{route('customer.view')}}",
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

            //Show Status Change Modal
            $(document).on('click', '.change_status', function () {
                $('#approve_status_form #status_id').val($(this).data('id'));
                $('#approve_status_form #status').val($(this).data('status'));
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
                var status = $('#approve_status_form #status').val();
                var phone = $('#approve_status_form #phone').val();
                var email = $('#approve_status_form #email').val();
                // console.log(visa_status);
                $.ajax({
                    url: "{{route('customer.change.status')}}",
                    type: "POST",
                    data: {status_id: status_id, status: status,phone:phone,email:email, _token: _token},
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

        });

    </script>
@endpush
