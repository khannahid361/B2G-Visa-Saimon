@extends('layouts.app')

@section('title', $page_title)
@push('styles')
    {{--    <link rel='stylesheet' href='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css'>--}}
    {{--    <script src='https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js'></script>--}}
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
                                {{--                                          <span class="type_element" style="display: none">--}}
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
                                {{--                                        </span>--}}
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
                                                                    <div class="col-md-3 form-group required">
                                                                        <label for="title">{{__('Name')}}</label>
                                                                        <div class="input-group" >
                                                                            <input type="text" class="form-control" required name="p_name" id="name" placeholder="name" value="{{$visa_info->p_name}}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3 form-group required">
                                                                        <label for="title">{{__('Passport Number')}}</label>
                                                                        <div class="input-group" >
                                                                            <input type="text" class="form-control" required name="passport_number" id="passport_number" placeholder="passport number" value="{{$visa_info->passport_number}}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3 form-group required">
                                                                        <label for="title">{{__('Phone Number')}}</label>
                                                                        <div class="input-group" >
                                                                            <input type="text" class="form-control" required name="phone" id="phone" placeholder="phone Number" value="{{$visa_info->phone}}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-md-3 form-group required">
                                                                        <label for="title">{{__('Email')}}</label>
                                                                        <div class="input-group" >
                                                                            <input type="email" class="form-control" required name="email" id="email" placeholder="email" value="{{$visa_info->email}}">
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group col-md-6 required">
                                                                        <label for="visaType_id">Visa Type</label>
                                                                        <select class="form-control selectpicker  VisaTypeCat" name="visaType_id" required data-receiver = receiver_0 data-live-search="true">
                                                                            <option value="">Select Please</option>
                                                                            @foreach ($visa_type as $row)
                                                                                <option value="{{ $row->id }}"{{$row->id == $visa_info->visaType_id ? 'selected' : ''}}>{{ $row->visa_type }}</option>
                                                                            @endforeach
                                                                        </select>
                                                                    </div>
                                                                    <div class="form-group col-md-6 required">
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
                                                        {{----------------------------3rd step ------------------------------}}
                                                        <table class="table1 table-bordered " id="dependentTable" width="100%">
                                                            <thead>
                                                            <legend>
                                                                <button type="button" class="btn btn-primary btn-sm addDependentRaw" id="addDependentRaw"><i class="fas fa-plus-circle"></i>Add Dependent</button>
                                                            </legend>
                                                            </thead>
                                                            <tbody id="invoice_item1">
                                                            @foreach($visa_app_details as $key=>$v_app_d)
                                                                @php
                                                                 $w_app_d_c = \Modules\WalkIn\Entities\WalkinAppDetailsChecklist::where('walkin_app_details_id',$v_app_d->id)->first();
                                                                @endphp
                                                            <tr>
                                                                <td>
                                                                    <div class="row">
                                                                        <div class="col-md-12 form-group required">
                                                                            <p class="text-center p-3"  style="background-color: #5290b6!important;color: white">Dependent Information</p>
                                                                        </div>
                                                                        <input type="hidden" name="update_details_id" id="update_details_id" value="{{$v_app_d->walkin_app_info_id}}"/>

                                                                        <div class="col-md-3 form-group required">
                                                                            <label for="title">{{__('Name')}}</label>
                                                                            <div class="input-group" >
                                                                                <input type="text" class="form-control" required name="visa[{{ $key+1 }}][c_name]" id="name" placeholder="Dependent name" value="{{$v_app_d->c_name}}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-3 form-group required">
                                                                            <label for="title">{{__('Passport Number')}}</label>
                                                                            <div class="input-group" >
                                                                                <input type="text" class="form-control" required name="visa[{{ $key+1 }}][c_passport_number]" id="passport_number" placeholder="Dependent Passport Number" value="{{$v_app_d->c_passport_number}}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-3 form-group required">
                                                                            <label for="title">{{__('Phone Number')}}</label>
                                                                            <div class="input-group" >
                                                                                <input type="text" class="form-control" required name="visa[{{ $key+1 }}][c_phone]" id="phone" placeholder="Dependent Phone Number" value="{{$v_app_d->c_phone}}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-3 form-group required">
                                                                            <label for="title">{{__('Email')}}</label>
                                                                            <div class="input-group" >
                                                                                <input type="email" class="form-control" required name="visa[{{ $key+1 }}][c_email]" id="email" placeholder="Dependent Email" value="{{$v_app_d->c_email}}">
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group col-md-6 required">
                                                                            <label for="department_id">Visa Type</label>
                                                                            <select class="form-control selectpicker  VisaTypeCat" id="visaType_id_{{ $key+1 }}" name="visa[{{ $key+1 }}][c_visaType_id]" required data-receiver = receiver_{{ $key+1 }} data-live-search="true">

                                                                                <option value="">Select Please</option>
                                                                                @foreach ($visa_type as $row)
                                                                                    <option value="{{ $row->id }}"{{$row->id == $v_app_d->c_visaType_id ? 'selected' : ''}}>{{ $row->visa_type }}</option>
                                                                                @endforeach
                                                                            </select>
                                                                        </div>
                                                                        <div class="form-group col-md-6 required">
                                                                            @php
                                                                             $d_cat = \Modules\Checklist\Entities\Checklist::where('id',$v_app_d->c_visa_category)->first();
                                                                            @endphp
                                                                            <label for="role_id">Visa Category</label>
                                                                            <select class="form-control selectpicker receiver_id VisaCatChecklistChild" id="receiver_{{ $key+1 }}" name="visa[{{ $key+1 }}][c_visa_category]" required="required" data-receiver = receiver_{{ $key+1 }} data-checklistchild = "checklistchild_{{ $key+1 }}" data-live-search="true">
                                                                                <option value="{{ $d_cat->id }}" >{{ $d_cat->title }}</option>
                                                                            </select>
                                                                        </div>

                                                                        <div class="col-md-12 form-group">
                                                                            <fieldset>
                                                                                @php
                                                                                    $d_checklist = \Modules\Checklist\Entities\ChecklistDetailes::where('checklist_id',$d_cat->id)->get()
                                                                                @endphp
                                                                                <legend>{{__('Visa Checklist :')}}</legend>
                                                                                <div class="form-group input_field" id="checklistchild_{{ $key+1 }}">
                                                                                    @php
                                                                                        $check_d_lists = \Modules\WalkIn\Entities\WalkinAppDetailsChecklist::where('walkin_app_details_id',$v_app_d->id)->get();
                                                                                    @endphp
                                                                                    @foreach($d_checklist as $keys=>$ckl_d)
                                                                                        @if(!empty($w_app_d_c->walkin_app_details_id))
                                                                                        <input type="hidden" name="update_details_checkList_id" id="update_details_checkList_id" value="{{$w_app_d_c->walkin_app_details_id}}"/>
                                                                                        @endif
                                                                                        @php
                                                                                            $check_d_list = $check_d_lists->where('c_check_list_id',$ckl_d->id)->first();
                                                                                        @endphp
                                                                                        <div class="line">
                                                                                            @if(!empty($check_d_list))
                                                                                            <input type="checkbox" class="my-checkbox" name="visa[[c_check_list_id][]]" value="{{$ckl_d->id}}"{{  ($ckl_d->id == $check_d_list->c_check_list_id  ? ' checked' : '') }} />
                                                                                            {{$ckl_d->checklist_name}}
                                                                                            @else
                                                                                                <input type="checkbox" class="my-checkbox" name="visa[{{$v_app_d[2]}}][c_check_list_id][{{$keys+1}}][]" value="{{$ckl_d->id}}" />
                                                                                                {{$ckl_d->checklist_name}}
                                                                                            @endif
                                                                                        </div>
                                                                                    @endforeach
                                                                                    {{--  Show Visa Category Wise Checklist --}}
                                                                                </div>
                                                                            </fieldset>
                                                                        </div>
                                                                    </div>
                                                                </td>
                                                                <td>
                                                                    <button type = "button" class = "btn btn-danger btn-sm deleteRaw"><i class = "fas fa-minus-circle"></i></button>
                                                                </td>
                                                            </tr>
                                                            @endforeach
                                                            </tbody>
                                                        </table>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="modal-footer">
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

        //--------------- dependent --------------
        var nextId = 10;
        $(document).on('click','.addDependentRaw', function(){
            let html;
            nextId++
            html = `
            <tr>
                 <td>
                    <div class="row">
                     <div class="col-md-12 form-group required">
                        <p class="text-center p-3"  style="background-color: #5290b6!important;color: white">Dependent Information</p>
                        </div>
                            <div class="col-md-3 form-group required">
                                <label for="title">{{__('Name')}}</label>
                                <div class="input-group" >
                                    <input type="text" class="form-control" required name="visa[`+nextId+`][c_name]" id="name" placeholder="Dependent name">
                                </div>
                            </div>
                            <div class="col-md-3 form-group required">
                                <label for="title">{{__('Passport Number')}}</label>
                                <div class="input-group" >
                                    <input type="text" class="form-control" required name="visa[`+nextId+`][c_passport_number]" id="passport_number" placeholder="Dependent Passport Number">
                                </div>
                            </div>
                            <div class="col-md-3 form-group required">
                                <label for="title">{{__('Phone Number')}}</label>
                                <div class="input-group" >
                                    <input type="text" class="form-control" required name="visa[`+nextId+`][c_phone]" id="phone" placeholder="Dependent Phone Number">
                                </div>
                            </div>
                            <div class="col-md-3 form-group required">
                                <label for="title">{{__('Email')}}</label>
                                <div class="input-group" >
                                    <input type="email" class="form-control" required name="visa[`+nextId+`][c_email]" id="email" placeholder="Dependent Email">
                                </div>
                            </div>
                            <div class="form-group col-md-6 required">
                                <label for="department_id">Visa Type</label>
                                <select class="form-control selectpicker  VisaTypeCat" id="visaType_id_`+ nextId +`" name="visa[`+nextId+`][c_visaType_id]" required data-receiver = receiver_`+ nextId +` data-live-search="true">

                                    <option value="">Select Please</option>
                                    @foreach ($visa_type as $row)
                                        <option value="{{ $row->id }}">{{ $row->visa_type }}</option>
                                    @endforeach
                                </select>
                            </div>
                             <div class="form-group col-md-6 required">
                                <label for="role_id">Visa Category</label>
                                <select class="form-control selectpicker receiver_id VisaCatChecklistChild" id="receiver_`+ nextId +`" name="visa[`+nextId+`][c_visa_category]" required="required" data-receiver = receiver_`+ nextId +` data-checklistchild = "checklistchild_`+ nextId +`" data-live-search="true"></select>
                           </div>

                         <div class="col-md-12 form-group">
                            <fieldset>
                                <legend>{{__('Visa Checklist :')}}</legend>
                                <div class="form-group input_field" id="checklistchild_`+ nextId +`">
                                                        {{--  Show Visa Category Wise Checklist --}}
                                </div>
                            </fieldset>
                        </div>
                    </div>
                </td>
                <td>
                <button type = "button" class = "btn btn-danger btn-sm deleteRaw"><i class = "fas fa-minus-circle"></i></button>
                </td>
        </tr>
`;
            $(' #dependentTable tbody').append(html);
            $('.selectpicker').selectpicker('refresh');

        });
        $(document).on('click','.deleteRaw',function(){
            // i--;
            $(this).parent().parent().remove();
        });

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


@endpush
