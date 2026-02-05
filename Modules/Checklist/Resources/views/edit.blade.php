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
                                <form id="store_or_update_form" method="post" action="{{route('checklist.update',$checklist->id)}}" enctype="multipart/form-data">
                                    @csrf
                                    <div class="modal-body">
                                        <div class="row">
                                            <input type="hidden" name="update_id" id="update_id" value="{{$checklist->id}}"/>
                                            <input type="hidden" name="user_id" id="user_id" value="{{$user}}"/>
                                            <input type="hidden" name="date" id="date" value="{{date('Y-m-d')}}"/>
                                            <div class="col-md-3 form-group required">
                                                <label for="title">{{__('Visa Price')}}</label>
                                                <div class="input-group" >
                                                    <input type="text" class="form-control" required name="price" id="price" value="{{$checklist->price - $checklist->service_charge}}" placeholder="Visa Price">
                                                </div>
                                            </div>
                                            <div class="col-md-3 form-group required">
                                                <label for="title">{{__('Service Charge')}}</label>
                                                <div class="input-group" >
                                                    <input type="text" class="form-control" required name="service_charge" id="service_charge" value="{{$checklist->service_charge}}" placeholder="service charge">
                                                </div>
                                            </div>

                                            <div class="col-md-6 form-group required">
                                                <label for="title">{{__('Chick List Title')}}</label>
                                                <div class="input-group" >
                                                    <input type="text" class="form-control" name="title" id="title" value="{{$checklist->title}}">

                                                </div>
                                            </div>

                                            <div class="form-group col-md-12 required">
                                                <label for="role_id">Description</label>
                                                <textarea type="text" class="form-control" name="description" id="editor">{{$checklist->description}}</textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="image" class="form-label">File <strong style="color: red">(File size must be 2mb or less)</strong></label>
                                                <input class="form-control" type="file" id="formFile" name="image">
                                                <embed src="{{('storage/'.$checklist->image)}}" height="80px" width="80px" alt="image">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary btn-sm" id="save-btn">Update</button>
                                    </div>
                                </form>

                                <h3 class="bg-primary text-center" style="color: white;padding: 10px;">Update All Checklist Name</h3>
                                @foreach($checklist_detailes as $row)
                                <form  method="post" action="{{route('checklist.list.update')}}">
                                    @csrf
                                    <input type="hidden" class="form-control" name="id" value="{{$row->id}}"/>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <div class="">
                                                        <table class="table table-bordered quarterSelectTable" id="saleTable">
                                                            <tbody id="invoice_item">
                                                                <tr class="text-center">
                                                                    <td><input type="text" class="form-control" name="checklist_name" value="{{$row->checklist_name}}"/></td>
                                                                    <td>
                                                                        <button type="submit" class="btn btn-primary btn-sm" id="save-btn">Update</button>
                                                                    </td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                                @endforeach
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
                    <button type="button" class="btn btn-primary btn-sm addRaw"><i class="fas fa-plus-circle"></i></button><span>&nbsp;&nbsp;</span>
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
