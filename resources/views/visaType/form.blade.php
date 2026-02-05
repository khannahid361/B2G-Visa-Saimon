@extends('layouts.app')

@section('title', $page_title)

@section('content')
<div class="d-flex flex-column-fluid">
    <div class="container-fluid">
        <!--begin::Notice-->
        <div class="card card-custom gutter-b">
            <div class="card-header flex-wrap py-5">
                <div class="card-title">
                    <h3 class="card-label"><i class="{{ $page_icon }} text-primary"></i> {{ $sub_title }}</h3>
                </div>
                <div class="card-toolbar">
                    <!--begin::Button-->
                    <a href="{{ route('department') }}" class="btn btn-primary btn-sm font-weight-bolder">
                        <i class="fas fa-arrow-circle-left"></i> Back
                    </a>
                    <!--end::Button-->
                </div>
            </div>
        </div>
        <!--end::Notice-->
        <!--begin::Card-->
        <div class="card card-custom">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-12">
                        <form id="saveDataForm" method="post">
                            @csrf
                            <div class="row">
                                <div class="form-group col-md-6 required">
                                    <label for="role_name">Department Name</label>
                                    <input type="text" class="form-control" name="department_name" id="department_name" value="" placeholder="Enter Department name">
                                </div>
                                <div class="col-md-6 pt-5 text-center">
                                    <button type="button" class="btn btn-primary btn-sm" id="save-btn">Save</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Card-->
    </div>
</div>
@endsection

@push('scripts')
<script src="js/tree.js"></script>
<script>
$(document).ready(function(){
    $('#permission').treed(); //intialized tree js


    $(document).on('click', '#save-btn', function () {
        let form = document.getElementById('saveDataForm');
        let formData = new FormData(form);
            $.ajax({
                url: "{{route('department.store.or.update')}}",
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
                    $('#saveDataForm').find('.is-invalid').removeClass('is-invalid');
                    $('#saveDataForm').find('.error').remove();
                    if (data.status == false) {
                        $.each(data.errors, function (key, value) {
                            $('#saveDataForm input#' + key).addClass('is-invalid');
                            $('#saveDataForm #' + key).parent().append(
                            '<small class="error text-danger">' + value + '</small>');
                        });
                    } else {
                        notification(data.status, data.message);
                        if (data.status == 'success') {
                            window.location.replace("{{ route('department') }}");
                        }
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
