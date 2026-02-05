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
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="modal-body">
                                    <div class="row">
                                        <input type="hidden" name="update_id" id="update_id"/>
                                        <input type="hidden" name="user_id" id="user_id" value="{{$user}}"/>
                                        <input type="hidden" name="date" id="date" value="{{date('Y-m-d')}}"/>
                                        {{-------------------------- 1st step ----------------------------}}

                                        <div class="form-group col-md-4 required">
                                            <label for="department_id">Application Type</label>
                                            <select class="form-control  departmentUser application_type select1" id="application_type"  name="application_type" required data-customer = customer_0 data-live-search="true">
                                                <option value="1">General</option>
                                                <option value="2">Travel Agent</option>
                                                <option value="3">Corporates</option>
                                            </select>
                                        </div>

                                        <div class="col-md-8 form-group required type_element" style="display: none">
                                            <div class="form-group col-md-8 required">
                                                <label for="role_id">Select Customer</label>
                                                <select class="form-control companyAgent selectpicker" id="customer_0"  required="required" data-live-search="true">

                                                </select>
                                            </div>
                                        </div>
                            {{--   new option  --}}
                                        <div class="col-md-4 form-group required agent_type_element" style="display: none">
                                            <label for="title">{{__('Travel Agent Name')}}</label>
                                            <div class="input-group" >
                                                <input type="text" class="form-control agent_name" required  id="agent-id" placeholder="Travel Agent Name">
                                            </div>
                                        </div>
                                        <div class="col-md-4 form-group required agent_type_element" style="display: none">
                                            <label for="title">{{__('Travel Agent Info')}}</label>
                                            <div class="input-group" >
                                                <input type="text" class="form-control information" required name="information" id="information" placeholder="Travel Agent Info">
                                            </div>
                                        </div>

                       {{----------------------------2nd step ------------------------------}}
                                        <!-- NAVBAR CODE END -->
                                        <div class="col-md-12  col-sm-12">
                                            <div class="fancy-collapse-panel">
                                                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                                    <button type="button" class="btn btn-primary btn-sm addCollapseRaw"><i class="fas fa-plus-circle"></i>Add Visa Application</button>
                                                    </br>
                                                    </br>
                                                    <div data-role="collapsibleset" data-content-theme="a" data-iconpos="right" id="set">
                                                        <div data-role="collapsible" id="set1" data-collapsed="true">
                                                            <div class="panel panel-default">
                                                                <div class="panel-heading" role="tab" id="headingOne">
                                                                    <h4 class="panel-title">
                                                                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">Application <span style="background: #0BB7AF;padding: 10px 13px;border-radius: 19px;">1</span><i class="fa" style="float: right;"></i>
                                                                        </a>
                                                                    </h4>
                                                                </div>
                                                                <form id="store_or_update_form_1"  method="post" enctype="multipart/form-data">
                                                                    @csrf
                                                                    <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                                                                        <div class="panel-body">
                                                                            <div class="col-md-12"  style="border:3px solid #27A;margin-bottom: 23px;">
                                                                                <div class="form-group">
                                                                                    <div class="">
                                                                                        <table class="table table-bordered quarterSelectTable" id="visaApplication">
                                                                                            <thead class="bg-primary text-center" >
                                                                                            <th width="100%" >Application Information</th>
                                                                                            </thead>
                                                                                            <tbody id="invoice_item">
{{--                                                                                            <input type="hidden" class="walkIn_app_type" name="walkIn_app_type" id="walkIn_app_type" value=""/>--}}
{{--                                                                                            <input type="hidden" class="agent_name type_element" name="agent_name" id="agent_name" value=""/>--}}
{{--                                                                                            <input type="hidden" class="agent_name2 agent_type_element" name="name" id="agent_name" value=""/>--}}
{{--                                                                                             <input type="hidden" class="information agent_type_element" name="information" id="information" value=""/>--}}
                                                                                            <input type="hidden" name="user_id" id="user_id" value="{{$user}}"/>
                                                                                            <tr>
                                                                                                <td>
                                                                                                    <div class="row">
                                                                                                        <div class="col-md-4 form-group required">
                                                                                                            <label for="p_names">{{__('Name')}}</label>
                                                                                                            <div class="input-group" >
                                                                                                                <input type="text" class="form-control p_name" required="required" name="p_name" id="p_name"  placeholder="name">
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="col-md-4 form-group required">
                                                                                                            <label for="title">{{__('Phone Number')}}</label>
                                                                                                            <div class="input-group" >
                                                                                                                <input type="text" class="form-control" required="required" name="phone" id="phone" placeholder="phone Number">
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="col-md-4 form-group required">
                                                                                                            <label for="email">{{__('Email')}}</label>
                                                                                                            <div class="input-group" >
                                                                                                                <input type="email" class="form-control" required="required" name="email" id="email" placeholder="email">
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="col-md-4">
                                                                                                            <div class="form-group required">
                                                                                                                <div class="">
                                                                                                                    <table class="table" id="visaPassportTable">
                                                                                                                        <tbody>
                                                                                                                        <tr>
                                                                                                                            <td>
                                                                                                                                <label for="passport_no">{{__('Passport Number')}}</label>
                                                                                                                                <input type="text" class="form-control" required="required" name="passport_no[]"/>
                                                                                                                            </td>
                                                                                                                            <td><button type="button" class="btn btn-primary btn-sm addRaw" style="margin-top: 25%;"><i class="fas fa-plus-circle"></i></button></td>
                                                                                                                        </tr>
                                                                                                                        </tbody>
                                                                                                                    </table>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                        <div class="form-group col-md-4 required">
                                                                                                            <label for="visaType_id">Destination</label>
                                                                                                            <select class="form-control selectpicker  VisaTypeCat" name="visaType_id" id="visaType_id" required="required" data-receiver = receiver_0 data-live-search="true">
                                                                                                                <option value="">Select Please</option>
                                                                                                                @foreach ($visa_type as $row)
                                                                                                                    <option value="{{ $row->id }}">{{ $row->visa_type }}</option>
                                                                                                                @endforeach
                                                                                                            </select>
                                                                                                        </div>
                                                                                                        <div class="form-group col-md-4 required">
                                                                                                            <label for="role_id">Visa Category</label>
                                                                                                            <select class="form-control VisaCatChecklist" id="receiver_0" name="visa_category" data-checklist = "checklist_1" required="required"></select>
                                                                                                        </div>
                                                                                                        <div class="col-md-12 form-group">
                                                                                                            <fieldset>
                                                                                                                <legend>{{__('Visa Checklist :')}}</legend>
                                                                                                                <div class="form-group " id="checklist_1"></div>
                                                                                                            </fieldset>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </td>
                                                                                            </tr>
                                                                                            </tbody>
                                                                                        </table>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="col-md-12">
                                                                                    <div class="modal-footer">
                                                                                        <button type="button" class="btn btn-danger btn-sm hold_btn" data-form_id = "store_or_update_form_1"  onclick="getOption()">Hold Application</button>
                                                                                        <button type="button" class="btn btn-primary btn-sm save_btn" data-form_id = "store_or_update_form_1"  onclick="getOption()">Save Application</button>
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="modal-footer">
                                                <a href="{{route('index')}}" style="margin: 0 auto;"> <button  class="btn btn-primary btn-sm save_btn"><b style="font-size: 17px;">Finish Application</b></button></a>
                                            </div>
                                        </div>
                                        <!-- CONATINER END -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end: Datatable-->
                </div>
            </div>
            <!--end::Card-->
        </div>
    </div>
    <div id="test"></div>
@endsection

@push('scripts')

    <script>
        function _(x){
            return document.getElementById(x);
        }
        $('.application_type').on('change', function() {
            if($(this).val() === '2') {
                $('.agent_type_element').show();
                $('.type_element').hide();
                $('.information').show();
            }else if($(this).val() === '3') {
                $('.agent_name2').val('');
                $('.agent_name').val('');
                $('.information').val('');
                $('.type_element').show();
                $('.agent_type_element').hide();
                $('.information').hide();

            }else {
                $('.type_element').hide();
                $('.agent_type_element').hide();
                $('.information').hide();
            }
        });

        function getOption() {
            inputString = $(".agent_name").val();
            $(".agent_name2").val(inputString);

            info= $("#information").val();
            $(".information").val(info);

            selectElement = document.querySelector('#application_type');
            output = selectElement.options[selectElement.selectedIndex].value;
            $(".walkIn_app_type").val(output);

            var output1 = $('.companyAgent').find(':selected').val();
            $('#agent_name').val(output1);


        }


        //--------------- add Collapse Row --------------
        var collapseId = 1;
        $(document).on('click','.addCollapseRaw',function(){
            collapseId++
            let html1;
            let i =0;
            html1 = `
          <div data-role="collapsible" id='set`+ collapseId +`' data-collapsed="true">
            <div class="panel panel-default">
                <div class="panel-heading" role="tab" id="headingOne">
                    <h4 class="panel-title">
                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseOne`+ collapseId +`" aria-expanded="false" aria-controls="collapseOne">Application <span style="background: #0BB7AF;padding: 10px 13px;border-radius: 19px;">`+ collapseId +`</span><i class="fa" style="float: right;"></i>
                        </a>
                    </h4>
                </div>
                   <form id="store_or_update_form`+ collapseId +`" class="store_or_update_form"  enctype="multipart/form-data">
                       @csrf
                        <div id="collapseOne`+ collapseId +`" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
                            <div class="panel-body">
                               <div class="col-md-12" style="border:3px solid #27A;margin-bottom: 23px;>
                                    <div class="form-group">
                                        <div class="">
                                            <table class="table table-bordered quarterSelectTable" id="visaApplication">
                                                <thead class="bg-primary text-center" >
                                                    <th width="100%" >Application Information</th>
                                                </thead>
                                                <tbody id="invoice_item">

                                                    <input type="hidden" name="user_id" id="user_id" value="{{$user}}"/>
                                                <tr>
                                                    <td>
                                                        <div class="row">
                                                            <div class="col-md-4 form-group required">
                                                                <label for="title">{{__('Name')}}</label>
                                                                <div class="input-group" >
                                                                    <input type="text" class="form-control p_name" required name="p_name" id="p_name" placeholder="name">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 form-group required">
                                                                <label for="title">{{__('Phone Number')}}</label>
                                                                <div class="input-group" >
                                                                    <input type="text" class="form-control" required name="phone" id="phone" placeholder="phone Number">
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4 form-group required">
                                                                <label for="title">{{__('Email')}}</label>
                                                                <div class="input-group" >
                                                                    <input type="email" class="form-control" required name="email" id="email" placeholder="email">
                                                                </div>
                                                            </div>
                                                              <div class="col-md-4">
                                                                <div class="form-group required">
                                                                    <div class="">
                                                                        <table class="table" id="visaPassportTable">
                                                                            <tbody>
                                                                                <tr>
                                                                                    <td>
                                                                                        <label for="passport_no">{{__('Passport Number')}}</label>
                                                                                        <input type="text" class="form-control" required="required" name="passport_no[]"/>
                                                                                    </td>
                                                                                    <td><button type="button" class="btn btn-primary btn-sm addRaw" style="margin-top: 25%;"><i class="fas fa-plus-circle"></i></button></td>
                                                                                </tr>
                                                                            </tbody>
                                                                        </table>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="form-group col-md-4 required">
                                                                <label for="department_id">Destination</label>
                                                                <select class="form-control selectpicker  VisaTypeCat" id="visaType_id`+ collapseId +`" name="visaType_id" required data-receiver = receiver`+ collapseId +` data-live-search="true">
                                                                    <option value="">Select Please</option>
                                                                    @foreach ($visa_type as $row)
                                                                    <option value="{{ $row->id }}">{{ $row->visa_type }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        <div class="form-group col-md-4 required">
                                                            <label for="role_id">Visa Category</label>
                                                                <select class="form-control selectpicker receiver_id VisaCatChecklist" id="receiver`+ collapseId +`" name="visa_category" required="required" data-receiver = receiver`+ collapseId +` data-checklist = "checklist_`+ collapseId +`" data-live-search="true"></select>
                                                             </div>

                                                            <div class="col-md-12 form-group">
                                                                <fieldset>
                                                                   <legend>{{__('Visa Checklist :')}}</legend>
                                                                    <div class="form-group input_field" id="checklist_`+ collapseId +`">
                                                                     {{--  Show Visa Category Wise Checklist --}}
                                                                    </div>
                                                                </fieldset>
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                   <div class="col-md-12">
                <div class="modal-footer">
{{--<button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">{{__('Hold Application')}}</button>--}}
                    <button type="button" class="btn btn-danger btn-sm hold_btn" data-form_id = "store_or_update_form`+ collapseId +`" onclick="getOption()">Hold Application</button>
                                                <button type="button" class="btn btn-primary btn-sm save_btn" data-form_id = "store_or_update_form`+ collapseId +`" onclick="getOption()">Save Application</button>
                                            </div>
                                        </div>
                                    </div>
                               </div>
                            </div>
                        </div>
                   </form>
                   <button type = "button" class = "btn btn-danger btn-sm collapseRawRemove" style="position: relative;bottom: 48px;"><i class = "fas fa-minus-circle"></i></button>
                </div>
            </div>

`;
            // $('#set').append(html1).collapsibleset( "refresh" );
            $('#set').append(html1);
            $('.selectpicker').selectpicker('refresh');
            i++;
        });
        $(document).on('click','.collapseRawRemove',function(){
            $(this).parent().remove();
        });
    </script>
    <script>
        $(document).on('click', '.save_btn', function () {
            let form = _($(this).data('form_id'));
            let formData = new FormData(form);

            // Append the selected value of "Application Type" dropdown
            formData.append('walkIn_app_type', $('#application_type').val());

            // Append the selected value of "Select Customer" dropdown
            formData.append('agent_name', $('#customer_0').val());

            // Check if "Application Type" is "Travel Agent" and append "agent_name" and "information" fields
            if ($('#application_type').val() === '2') {
                formData.append('name', $('.agent_name').val());
                formData.append('information', $('#information').val());
            }

            let url = "{{route('walkIn.store.or.update')}}";
            $.ajax({
                url: url,
                type: "POST",
                data: formData,
                dataType: "JSON",
                contentType: false,
                processData: false,
                cache: false,
                beforeSend: function () {
                    $('#save-btn').addClass('spinner spinner-white spinner-right');
                },
                complete: function () {
                    $('#save-btn').removeClass('spinner spinner-white spinner-right');
                },
                success: function (data) {
                    var formId = $(form).attr("id");
                    // console.log(formId);
                    $('#'+ formId ).find('.is-invalid').removeClass('is-invalid');
                    $('#'+ formId).find('.error').remove();
                    if (data.status == false) {
                        $.each(data.errors, function (key, value) {
                            var key = key.split('.').join('_');
                            $('#' + formId + ' input#' + key).addClass('is-invalid');
                            $('#' + formId + ' textarea#' + key).addClass('is-invalid');
                            $('#' + formId + ' select#' + key).parent().addClass('is-invalid');
                            $('#' + formId + ' #' + key).parent().append(
                                '<br><p class="error text-danger">' + value + '</p>');
                        });
                    } else {
                        notification(data.status, data.message);
                        if (data.status == 'success') {

                        }
                    }
                },
                error: function (xhr, ajaxOption, thrownError) {
                    console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
                }
            });
        });


        $(document).on('click','.hold_btn',function(){
            let form     = _($(this).data('form_id'));
            let formData = new FormData(form);
            let url      = "{{route('walkIn.hold.or.update')}}";
            var bla = _($('#main-id').val());
            $('#agent_name').val(bla);
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
                    $('#sale_store_form').find('.is-invalid').removeClass('is-invalid');
                    $('#sale_store_form').find('.error').remove();
                    if (data.status == false) {
                        $.each(data.errors, function (key, value) {
                            var key = key.split('.').join('_');
                            $('#sale_store_form input#' + key).addClass('is-invalid');
                            $('#sale_store_form textarea#' + key).addClass('is-invalid');
                            $('#sale_store_form select#' + key).parent().addClass('is-invalid');
                            $('#sale_store_form #' + key).parent().append(
                                '<small class="error text-danger">' + value + '</small>');
                        });
                    } else {
                        notification(data.status, data.message);
                        if (data.status == 'success') {
                            {{--window.location.replace("{{ route('sale') }}");--}}
                        }
                    }
                },
                error: function (xhr, ajaxOption, thrownError) {
                    console.log(thrownError + '\r\n' + xhr.statusText + '\r\n' + xhr.responseText);
                }
            });
        });

        $(document).ready(function() {
            $(document).on('change','.application_type',function(){
                let html;
                let applicationTypeId = $(this).find('option:selected').val();
                let customer   = $(this).data('customer');
                if(applicationTypeId != ''){
                    $.ajax({
                        url      : "{{url('application-type-wise-customer')}}/" + applicationTypeId,
                        type     : 'GET',
                        dataType : "JSON",
                        success  : function(data){
                            console.log(data)
                            if(data != ''){
                                html = `<option value="">Select Please</option>`;
                                $.each(data, function(key, value) { html += '<option value="'+ value.visaType_id +'">'+ value.title +'</option>'; });
                                $('#'+ customer +'').empty();
                                $('#'+ customer +'').append(html);
                                $('.selectpicker').selectpicker('refresh');
                            }
                        }
                    });
                }
            });
        });

        $(document).ready(function() {
            $(document).on('change','.VisaTypeCat',function(){
                let html;
                let visaTypeId = $(this).find('option:selected').val();
                let receiveId   = $(this).data('receiver');
                if(visaTypeId != ''){
                    $.ajax({
                        url      : "{{url('type-wise-cat')}}/" + visaTypeId,
                        type     : 'GET',
                        dataType : "JSON",
                        success  : function(data){
                            // console.log(data)
                            if(data != ''){
                                html = `<option value="">Select Please</option>`;
                                $.each(data, function(key, value) { html += '<option value="'+ value.visaType_id +'">'+ value.title +'</option>'; });
                                $('#'+ receiveId +'').empty();
                                $('#'+ receiveId +'').append(html);
                                $('.selectpicker').selectpicker('refresh');
                            }
                        }
                    });
                }
            });
        });

        $(document).ready(function() {
            $(document).on('change','.VisaCatChecklist',function(){
                let visaCateId  = $(this).find('option:selected').val();
                let checklistId = $(this).data('checklist')
                if(visaCateId != ''){
                    $.ajax({
                        url      : "{{url('cat-wise-checklist')}}/" + visaCateId,
                        type     : 'GET',
                        dataType : "JSON",
                        success  : function(data){
                            $('#'+ checklistId +'').empty();
                            if(data != ''){
                                $.each(data, function(key, value) {
                                    $('#'+ checklistId +'').append('<div class="line"><input type = "checkbox" class = "my-checkbox" name = "check_list_id[]" value = "'+ value.visaType_id +'"/>'+ value.title +'</div>');
                                });
                            }
                        }
                    });
                }
            });
        });

        $(document).ready(function() {
            $(document).on('change','.VisaCatChecklistChild',function(){
                let visaCateChildId = $(this).find('option:selected').val();
                let checklistChildId = $(this).data('checklistchild');
                let parentId = checklistChildId.split('_');
                // console.log(parentId[1]);

                if(visaCateChildId != ''){
                    $.ajax({
                        url      : "{{url('cat-wise-checklist-child')}}/" + visaCateChildId,
                        type     : 'GET',
                        dataType : "JSON",
                        success  : function(data){
                            $('#'+ checklistChildId +'').empty();
                            if(data != ''){
                                $.each(data, function(key, value) {
                                    $('#'+ checklistChildId +'').append('<div class="line"><input type ="checkbox" class ="my-checkbox" name ="visa['+parentId[1]+'][c_check_list_id]['+key+'] []" value = "'+ value.visaType_id +'"/>'+ value.title +'</div>');
                                });
                            }
                        }
                    });
                }
            });
        });

    </script>

    <script>
        $(document).on('click','.addRaw',function(){
            let html;
            html = `<tr>
                 <td><input type="text" class="form-control "  name="passport_no[]" /></td>
                 <td>
                    <button type="button" class="btn btn-primary btn-sm addRaw"><i class="fas fa-plus-circle"></i></button><span>&nbsp;&nbsp;</span>
                   <button type = "button" class = "btn btn-danger btn-sm deleteRaw"><i class = "fas fa-minus-circle"></i></button>
                </td>
                </tr>`;
            $('#visaPassportTable tbody').append(html);
            i++;
        });
        $(document).on('click','.deleteRaw',function(){
            $(this).parent().parent().remove();
        });
    </script>

<script>
    $(document).ready(function(){
        // Handle select change event for all select elements with class 'VisaTypeCat'
        $('.VisaTypeCat').on('change', function(){
            // Get the ID of the select element
            var selectId = $(this).attr('id');
            var selectedOption = $(this).val();

            // Extract the 'collapseId' from the select element's ID
            var collapseId = selectId.match(/\d+/)[0];

            // Update input fields based on the selected option and collapseId
            if (selectedOption === 'option1') {
                $('#input1' + collapseId).val('Value for Option 1');
                $('#input2' + collapseId).val('Another Value for Option 1');
            } else if (selectedOption === 'option2') {
                $('#input1' + collapseId).val('Value for Option 2');
                $('#input2' + collapseId).val('Another Value for Option 2');
            } else if (selectedOption === 'option3') {
                $('#input1' + collapseId).val('Value for Option 3');
                $('#input2' + collapseId).val('Another Value for Option 3');
            } else {
                // Handle other cases or set default values
                $('#input1' + collapseId).val('');
                $('#input2' + collapseId).val('');
            }
        });
    });
</script>

@endpush
