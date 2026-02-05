@extends('layouts.app')
@section('title', $page_title)
@section('content')
<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <div class="card card-custom gutter-b">
            <div class="card-header flex-wrap py-5">
                <div class="card-title">
                    <h3 class="card-label"><i class="{{ $page_icon }} text-primary"></i> {{ $sub_title }}</h3>
                </div>
                <div class="card-toolbar">

                </div>
            </div>
        </div>
        <div class="card card-custom">

            <div class="card-body">
                <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="dataTable" class="table table-bordered table-hover">
                                <thead class="bg-primary">
                                    <tr>
                                        <th>{{__('SL')}}</th>
                                        <th>{{__('Name')}}</th>
                                        <th>{{__('Phone')}}</th>
                                        <th>{{__('Email')}}</th>
                                        <th>{{__('Subject')}}</th>
                                        <th>{{__('Message')}}</th>
                                        <th>{{__('Action')}}</th>
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
@include('frontend::viewmessage')
@endsection
@push('scripts')
<script src="plugins/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
<script>
    let table;
        $(document).ready(function(){
            table = $('#dataTable').DataTable({
                "processing": true,
                "serverSide": true,
                "order"     : [],
                "responsive": true,
                "bInfo"     : true,
                "bFilter"   : false,
                "lengthMenu": [
                    [5, 10, 15, 25, 50, 100, 1000, 10000, -1],
                    [5, 10, 15, 25, 50, 100, 1000, 10000, "All"]
                ],
                "pageLength": 25, //number of data show per page
                "language"  : {
                    processing : `<i class="fas fa-spinner fa-spin fa-3x fa-fw text-primary"></i> `,
                    emptyTable : '<strong class="text-danger">No Data Found</strong>',
                    infoEmpty  : '',
                    zeroRecords: '<strong class="text-danger">No Data Found</strong>'
                },
                "ajax": {
                    "url": "{{route('contactus.datatable.data')}}",
                    "type": "POST",
                    "data": function (data) {
                        data._token          = _token;
                    }
                },
                "columnDefs": [{
                    "targets": [0,1,2,3,4,5],
                    "orderable": false,
                    "className": "text-center"
                },
                ],
            });
            $('#btn-filter').click(function () {table.ajax.reload();});
            $('#btn-reset').click(function () {
                $('#form-filter')[0].reset();
                $('#form-filter .selectpicker').selectpicker('refresh');
                table.ajax.reload();
            });
            $(document).on('click', '.view-data', function () {
                let id = $(this).data('id');
                let _token = $('meta[name="csrf-token"]').attr('content'); // Ensure CSRF token exists in <head>

                if (id) {
                    $.ajax({
                        url: "{{ route('contactus.view') }}",
                        type: "POST",
                        data: { id: id, _token: _token },
                        dataType: "JSON",
                        success: function (data) {
                            if (data.status === 'error') {
                                notification('error', data.message);
                            } else {
                                // Fill modal fields
                                $('#view_name').text(data.name ?? '');
                                $('#view_email').text(data.email ?? '');
                                $('#view_phone').text(data.phone ?? '');
                                $('#view_subject').text(data.subject ?? '');
                                $('#view_message').text(data.message ?? '');

                                // Show modal
                                $('#store_or_update_modal').modal({
                                    keyboard: false,
                                    backdrop: 'static'
                                });
                            }
                        },
                        error: function (xhr, ajaxOption, thrownError) {
                            console.error(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
                        }
                    });
                }
            });
            $(document).on('click', '.delete-data', function () {
                let id    = $(this).data('id');
                let name  = $(this).data('name');
                let row   = table.row($(this).parent('tr'));
                let url   = "{{ route('contactus.delete') }}";
                delete_data(id, url, table, row, name);
            });
        });
</script>
@endpush