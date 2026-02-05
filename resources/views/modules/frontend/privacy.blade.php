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
                    @if (permission('privacy-access'))
                    <a href="javascript:void(0);"
                        onclick="showFormModal('{{__('Add New Privacy Policy')}}','{{__('Save')}}')"
                        class="btn btn-primary btn-sm font-weight-bolder"><i class="fas fa-plus-circle"></i>
                        {{__('Add New')}}</a>
                    @endif
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
                                        <th>{{__('Details')}}</th>
                                        <th>{{__('Type')}}</th>
                                        <th>{{__('Created By')}}</th>
                                        <th>{{__('Modified By')}}</th>
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
@include('frontend::modal.privacymodal')
@endsection
@push('scripts')
<script src="plugins/custom/datatables/datatables.bundle.js" type="text/javascript"></script>
<script src="js/spartan-multi-image-picker.min.js"></script>
<script src="https://cdn.ckeditor.com/ckeditor5/35.3.1/classic/ckeditor.js"></script>
<script>
    ClassicEditor
            .create(document.querySelector('#details'))
            .then(editor => {
                window.detailsEditor = editor; // Store globally for later sync
            })
            .catch(error => {
                console.error(error);
            });

</script>
<script>
    let table;
        $(document).ready(function(){
            $("#image").spartanMultiImagePicker({
                fieldName: 'image',
                maxCount: 1,
                rowHeight: '150px',
                groupClassName: 'col-md-4 col-sm-4 col-xs-4',
                maxFileSize: '',
                dropFileLabel: "Drop Here",
                allowedExt: '',
            });

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
                    "url": "{{route('privacyPolicy.datatable.data')}}",
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
            $(document).on('click', '#save-btn', function () {
                // Sync CKEditor content to underlying textarea
                if (window.detailsEditor) {
                    detailsEditor.updateSourceElement();
                }

                let form     = document.getElementById('store_or_update_form');
                let formData = new FormData(form);
                let url      = "{{ route('privacyPolicy.store.or.update') }}";
                let id       = $('#update_id').val();
                let method   = id ? 'update' : 'add';

                store_or_update_data(table, method, url, formData);
            });
            $(document).on('click', '.edit_data', function () {
                let id = $(this).data('id');
                $('#store_or_update_form')[0].reset();
                $('#store_or_update_form').find('.is-invalid').removeClass('is-invalid');
                $('#store_or_update_form').find('.error').remove();
                $('#preview-image').attr('src', '');

                if (id) {
                    $.ajax({
                        url: "{{ route('privacyPolicy.edit') }}",
                        type: "POST",
                        data: { id: id, _token: _token },
                        dataType: "JSON",
                        success: function (data) {
                            if (data.status === 'error') {
                                notification(data.status, data.message);
                            } else {
                                $('#store_or_update_form #update_id').val(data.id);
                                $('#store_or_update_form #policy_id').val(data.policy_id);

                                // ✅ Set CKEditor content
                                if (window.detailsEditor) {
                                    detailsEditor.setData(data.details);
                                }

                                $('#store_or_update_modal').modal({
                                    keyboard: false,
                                    backdrop: 'static',
                                });

                                $('#store_or_update_modal .modal-title').html(
                                    '<i class="fas fa-edit text-warning"></i> <span>{{ __("Edit Privacy Policy") }}</span>'
                                );
                                $('#store_or_update_modal #save-btn').text('{{ __("Update") }}');
                            }
                        },
                        error: function (xhr, ajaxOption, thrownError) {
                            console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
                        }
                    });
                }
            });
        });
</script>
@endpush