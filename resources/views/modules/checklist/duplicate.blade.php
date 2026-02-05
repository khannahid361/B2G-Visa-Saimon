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
                    <div class="card-toolbar">
                        <!--begin::Button-->
                        <a href="{{ route('checklist') }}" class="btn btn-warning btn-sm font-weight-bolder">
                            <i class="fas fa-arrow-left"></i>{{__('Back')}}</a>
                        <!--end::Button-->
                    </div>
                </div>
            </div>
            <!--end::Notice-->
            <!--begin::Card-->
            <div class="card card-custom">
                <div class="card-body">
                    <!--begin: Datatable-->
                    <div id="kt_datatable_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                        <div class="row">
                            <div class="col-sm-12">
                                <form  method="post" action="{{route('checklist.list.store')}}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="row">
                                            <input type="hidden" name="update_id" id="update_id"/>
                                            <input type="hidden" name="user_id" id="user_id" value="{{$user}}"/>
                                            <input type="hidden" name="date" id="date" value="{{date('Y-m-d')}}"/>
                                            <div class="form-group col-md-4 required">
                                                <label for="department_id">Type</label>
                                                <select class="form-control selectpicker  appliactionType_id" id="appliactionType_id" name="appliactionType_id" required="required" data-receiver = receiver_0 data-live-search="true">
                                                    <option value="">Select Please</option>
                                                    <option value="1"{{$checklist->appliactionType_id == 1 ? 'selected' : ''}}>General</option>
                                                    <option value="2"{{$checklist->appliactionType_id == 2 ? 'selected' : ''}}>Travel Agent</option>
                                                    <option value="3"{{$checklist->appliactionType_id == 3 ? 'selected' : ''}}>Corporates</option>
                                                </select>
                                            </div>
                                            <div class="form-group col-md-4 required">
                                                <label for="department_id">Destination</label>
                                                <select class="form-control selectpicker  visaType_id" id="visaType_id" name="visaType_id" required="required" data-receiver = receiver_0 data-live-search="true">
                                                    <option value="">Select Please</option>
                                                    @foreach ($visa_type as $row)
                                                        <option value="{{ $row->id }}"{{$row->id == $checklist->visaType_id ? 'selected' : ''}}>{{ $row->visa_type }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-4 form-group required">
                                                <label for="title">{{__('Visa Price')}}</label>
                                                <div class="input-group" >
                                                    <input type="text" class="form-control" required="required" name="price" id="price" value="{{$checklist->price - $checklist->service_charge}}">
                                                </div>
                                            </div>

                                            <div class="col-md-4 form-group required">
                                                <label for="title">{{__('Service Charge')}}</label>
                                                <div class="input-group" >
                                                    <input type="text" class="form-control" required="required" name="service_charge" id="service_charge" value="{{$checklist->service_charge}}">
                                                </div>
                                            </div>

                                            <div class="form-group col-md-4 required">
                                                <label for="department_id">From Country</label>
                                                <select class="form-control selectpicker departmentUser fromCountry_id" id="fromCountry_id" name="fromCountry_id" required="required"  data-live-search="true">
                                                    <option value="">Select Please</option>
                                                    @foreach ($countries as $row)
                                                        <option value="{{ $row->id }}"{{$row->id == $checklist->fromCountry_id ? 'selected' : ''}}>{{ $row->country_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="form-group col-md-4 required">
                                                <label for="department_id">To Country</label>
                                                <select class="form-control selectpicker departmentUser toCountry_id" id="toCountry_id" name="toCountry_id" required="required"  data-live-search="true">
                                                    <option value="">Select Please</option>
                                                    @foreach ($toCountries as $row)
                                                        <option value="{{ $row->id }}"{{$row->id == $checklist->toCountry_id ? 'selected' : ''}}>{{ $row->country_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="col-md-12 form-group required">
                                                <label for="title">{{__('Check List Title')}}</label>
                                                <div class="input-group" >
                                                    <input type="text" class="form-control" required="required" name="title" id="title" value="{{$checklist->title}}">
                                                </div>
                                            </div>

                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="">
                                                        <table class="table table-bordered quarterSelectTable" id="saleTable">
                                                            <thead class="bg-primary text-center">
                                                            <th width="100%" >Required Checklist Name</th>
                                                            </thead>

                                                                <tbody id="invoice_item">
                                                                @foreach($checklist_detailes as $row)
                                                                <tr class="text-center">
                                                                    <td><input type="text" class="form-control" required="required" name="checklist_name[]" value="{{$row->checklist_name}}"/></td>
                                                                    <td>
                                                                        <button type = "button" class = "btn btn-danger btn-sm deleteRaw"><i class = "fas fa-minus-circle"></i></button>
                                                                    </td>
                                                                </tr>
                                                                @endforeach

                                                                <button type="button" class="btn btn-primary btn-sm addRaw" style="float: right;margin-bottom: 10px;"><i class="fas fa-plus-circle"></i></button>      <br>
                                                                </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="form-group">
                                                <label for="formFile" class="form-label">File <strong style="color: red">(File size must be 2mb or less)</strong></label>
                                                <input class="form-control" type="file" id="formFile" name="image">
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label for="description">Description</label>
                                                <textarea type="text" class="form-control" name="description" id="editor">{{$checklist->description}}</textarea>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">{{__('Close')}}</button>
                                        <button type="submit" class="btn btn-primary btn-sm" id="save-btn">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!--end: Datatable-->
                </div>
            </div>
            <!--end::Card-->
        </div>
    </div>
    @include('checklist::modal')
@endsection

@push('scripts')

    <script src="https://cdn.ckeditor.com/ckeditor5/35.3.1/classic/ckeditor.js"></script>

    <script>
        ClassicEditor
            .create(document.querySelector('#editor'))
            .catch(error => {
                console.error(error);
            });

        $(document).on('click','.addRaw',function(){
            let html;
            html = `<tr>
                 <td><input type="text" class="form-control "  name="checklist_name[]" /></td>
                 <td>
                   <button type = "button" class = "btn btn-danger btn-sm deleteRaw"><i class = "fas fa-minus-circle"></i></button>
                </td>
                </tr>`;
            $('#saleTable tbody').append(html);
            // $('.selectpicker').selectpicker('refresh');
            i++;
        });
        $(document).on('click','.deleteRaw',function(){
            // i--;
            $(this).parent().parent().remove();
        });
    </script>

@endpush
