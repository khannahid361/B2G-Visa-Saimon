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
                    <div class="col-sm-12">
                        <div class="modal-body">
                            <div class="row">
                                <input type="hidden" name="update_id" id="update_id"/>
                                <input type="hidden" name="user_id" id="user_id" value="{{$user}}"/>
                                <input type="hidden" name="date" id="date" value="{{date('Y-m-d')}}"/>
                                {{-------------------------- 1st step ----------------------------}}

                                <div class="form-group col-md-4 required">
                                    <label for="department_id">Application Type</label>
                                    <select class="form-control  departmentUser application_type select1" id="application_type"  name="application_type" required data-receiver = receiver_0 data-live-search="true">
                                        {{--                                                <option value="">Select Please</option>--}}
                                        <option value="1"{{$visa_info->walkIn_app_type == 1 ? 'selected' : ''}}>General</option>
                                        <option value="2"{{$visa_info->walkIn_app_type == 2 ? 'selected' : ''}}>Agent</option>
                                        <option value="3"{{$visa_info->walkIn_app_type == 3 ? 'selected' : ''}}>Company</option>
                                    </select>
                                </div>
                                @if($visa_info->walkIn_app_type == 2 or $visa_info->walkIn_app_type == 3)
                                <div class="col-md-4 form-group required type_element" style="">
                                    <label for="title">{{__('Agent/Company Name')}}</label>
                                    <div class="input-group" >
                                        <input type="text" class="form-control company_name" required  id="agent-id" placeholder="Agent/Company Name" value="{{$visa_info->name}}">
                                    </div>
                                </div>
                                <div class="col-md-4 form-group required type_element" style="">
                                    <label for="title">{{__('Agent/Company Info')}}</label>
                                    <div class="input-group" >
                                        <input type="text" class="form-control information" required name="information" id="information" placeholder="Agent/Company Info" value="{{$visa_info->information}}">
                                    </div>
                                </div>
                                @endif
                                {{----------------------------2nd step ------------------------------}}
                                <div class="panel panel-default">
                                    <form id="store_or_update_form_1"  method="post" enctype="multipart/form-data">
                                        @csrf
                                        <input type="hidden" name="update_id" id="update_id" value="{{$visa_info->id}}"/>

                                        <div class="col-md-12"  style="border:3px solid #27A;margin-bottom: 23px;">
                                            <div class="form-group">
                                                <div class="">
                                                    <table class="table table-bordered quarterSelectTable" id="visaApplication">
                                                        <thead class="bg-primary text-center" >
                                                        <th width="100%" >Application Information</th>
                                                        </thead>
                                                        <tbody id="invoice_item">
                                                            <input type="hidden" class="walkIn_app_type" name="walkIn_app_type" id="walkIn_app_type" value=""/>
                                                            <input type="hidden" class="agent_name" name="name" id="agent_name" value=""/>
                                                            <input type="hidden" class="information" name="information" id="information" value=""/>
                                                            <input type="hidden" name="user_id" id="user_id" value="{{$user}}"/>
                                                            <tr>
                                                                <td>
                                                                    <div class="row">
                                                                        <div class="col-md-4 form-group required">
                                                                            <label for="title">{{__('Name')}}</label>
                                                                            <div class="input-group" >
                                                                                <input type="text" class="form-control" required name="p_name" id="name" placeholder="name" value="{{$visa_info->p_name}}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4 form-group required">
                                                                            <label for="title">{{__('Phone Number')}}</label>
                                                                            <div class="input-group" >
                                                                                <input type="text" class="form-control" required name="phone" id="phone" placeholder="phone Number" value="{{$visa_info->phone}}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4 form-group required">
                                                                            <label for="title">{{__('Email')}}</label>
                                                                            <div class="input-group" >
                                                                                <input type="email" class="form-control" required name="email" id="email" placeholder="email" value="{{$visa_info->email}}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-4">
                                                                            <div class="form-group required">
                                                                                <div class="">
                                                                                    <table class="table" id="visaPassportTable">
                                                                                        <tbody>
                                                                                        @foreach($visa_passport as $row)
                                                                                        <tr>
                                                                                            <td>
                                                                                                <label for="passport_no">{{__('Passport Number')}}</label>
                                                                                                <input type="text" class="form-control" required="required" name="passport_no[]" value="{{$row->passport_no}}"/>
                                                                                            </td>
                                                                                            <td style="padding-top: 6% !important;">
                                                                                                <button type="button" class="btn btn-primary btn-sm addRaw"><i class="fas fa-plus-circle"></i></button><span>&nbsp;&nbsp;</span>
                                                                                                <button type = "button" class = "btn btn-danger btn-sm deleteRaw"><i class = "fas fa-minus-circle"></i></button>
                                                                                            </td>
                                                                                        </tr>
                                                                                        @endforeach
                                                                                        </tbody>
                                                                                    </table>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group col-md-4 required">
                                                                            <label for="visaType_id">Destination</label>
                                                                            <select class="form-control selectpicker  VisaTypeCat" name="visaType_id" required data-receiver = receiver_0 data-live-search="true">
                                                                                <option value="">Select Please</option>
                                                                                @foreach ($visa_type as $row)
                                                                                    <option value="{{ $row->id }}"{{$row->id == $visa_info->visaType_id ? 'selected' : ''}}>{{ $row->visa_type }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="form-group col-md-4 required">
                                                                            <label for="role_id">Visa Category</label>
                                                                            <select class="form-control selectpicker  VisaCatChecklist" id="receiver_0" name="visa_category" data-checklist = "checklist_1" required="required" data-live-search="true">
                                                                               @foreach($visa_category as $cat)
                                                                                <option value="{{ $cat->id }}">{{ $cat->title }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>

                                                                        <div class="col-md-12 form-group">
                                                                            <fieldset>
                                                                                <legend>{{__('Visa Checklist :')}}</legend>
                                                                                <div class="form-group " id="checklist_1">
                                                                                    @php
                                                                                        $check_lists = \Modules\WalkIn\Entities\WalkinAppChecklist::where('walkin_app_info_id',$visa_info->id)->get();
                                                                                    @endphp
                                                                                    @foreach($visa_checklist as $ckl)
                                                                                        @php
                                                                                            $check_list = $check_lists->where('check_list_id',$ckl->id)->first();
                                                                                        @endphp
                                                                                    <div class="line">
                                                                                        @if(!empty($check_list))
                                                                                        <input type="checkbox" class="my-checkbox" name="check_list_id[]" value="{{$ckl->id}}"
                                                                                            {{  ($ckl->id == $check_list->check_list_id  ? 'checked' : '') }}/>
                                                                                            {{$ckl->checklist_name}}
                                                                                        @else
                                                                                            <input type="checkbox" class="my-checkbox" name="check_list_id[]" value="{{$ckl->id}}"
                                                                                               />{{$ckl->checklist_name}}
                                                                                        @endif
                                                                                    </div>
                                                                                    @endforeach
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
                                            </div>
                                            <div class="col-md-12">
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-danger btn-sm hold_btn" data-form_id = "store_or_update_form_1"  onclick="getOption()">Hold Application</button>
                                                    <button type="button" class="btn btn-primary btn-sm save_btn" data-form_id = "store_or_update_form_1"  onclick="getOption()">Save Application</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <!-- CONATINER END -->
                            </div>
                        </div>
                    </div>
                    <!--end: Datatable-->
                </div>
            </div>
            <!--end::Card-->
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        function _(x){
            return document.getElementById(x);
        }
        $('.application_type').on('change', function() {
            if($(this).val() === '2') {
                $('.type_element').show();
            }else if($(this).val() === '3') {
                $('.type_element').show();
            }else {
                $('.type_element').hide();
            }
        });


        function getOption() {
            selectElement = document.querySelector('#application_type');
            output = selectElement.options[selectElement.selectedIndex].value;
            $(".walkIn_app_type").val(output);
            inputString = $("#agent-id").val();
            $(".agent_name").val(inputString);

            info= $("#information").val();
            $(".information").val(info);
            // alert(inputString);
            // document.querySelector('.output').textContent = output;
        }

        var collapseId = 1;

    </script>
    <script>
        $(document).on('click','.save_btn',function(){
            // alert (bla);
            let form     = _($(this).data('form_id'));
            let formData = new FormData(form);
            let url      = "{{route('walkIn.update')}}";
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
                    $('#store_or_update_form').find('.is-invalid').removeClass('is-invalid');
                    $('#store_or_update_form').find('.error').remove();
                    if (data.status == false) {
                        $.each(data.errors, function (key, value) {
                            $('#store_or_update_form input#' + key).addClass('is-invalid');
                            $('#store_or_update_form textarea#' + key).addClass('is-invalid');
                            $('#store_or_update_form select#' + key).parent().addClass('is-invalid');
                            if(key == 'password' || key == 'password_confirmation'){
                                $('#store_or_update_form #' + key).parents('.form-group').append(
                                    '<small class="error text-danger">' + value + '</small>');
                            }else{
                                $('#store_or_update_form #' + key).parent().append(
                                    '<small class="error text-danger">' + value + '</small>');
                            }
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

            // alert (bla);
            let form     = _($(this).data('form_id'));
            let formData = new FormData(form);
            let url      = "{{route('walkIn.update_hold')}}";
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
            $(document).on('change','.VisaTypeCat',function(){
                let html;
                let visaTypeId = $(this).find('option:selected').val();
                let receiveId   = $(this).data('receiver');
                console.log(receiveId);
                if(visaTypeId != ''){
                    $.ajax({
                        url      : "{{url('type-wise-cat')}}/" + visaTypeId,
                        type     : 'GET',
                        dataType : "JSON",
                        success  : function(data){
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
                let html;
                let visaCateId = $(this).find('option:selected').val();
                let checklistId = $(this).data('checklist')

                if(visaCateId != ''){
                    $.ajax({
                        url      : "{{url('cat-wise-checklist')}}/" + visaCateId,
                        type     : 'GET',
                        dataType : "JSON",
                        success  : function(data){
                            // console.log(data)
                            $.each(data, function(key, value) {
                                html += `
                                <div class="line">

                                    <input type="checkbox" class="my-checkbox" name="check_list_id[]" value="`+value.visaType_id+`" />
                                    `+value.title+`
                                    </div>
                                `;
                            });
                            $('#'+ checklistId +'').empty();
                            $('#'+ checklistId +'').append(html);
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
                console.log(parentId[1]);

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
            // $('.selectpicker').selectpicker('refresh');
            i++;
        });
        $(document).on('click','.deleteRaw',function(){
            $(this).parent().parent().remove();
        });
    </script>

@endpush
