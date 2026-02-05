@extends('layouts.app')

@section('title', $page_title)
@push('styles')
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
                                <div class="form-group col-md-6" style="border-right: 2px solid">
                                    <h1 style="text-align: center">Passport Ready for Delivery </h1>
                                    <hr>
                                    <div class="">
                                        <h5>Customer Name : {{$visa_info->p_name}}</h5>
                                    </div>
                                        <hr>
                                        @foreach($all_passport as $row)
                                        <form method="post" action="{{route('walkIn.readyForDelivered')}}" style="margin: 10px">
                                            @csrf
                                            <input type="hidden" name="id" value="{{$visa_info->id}}">
                                            <input type="hidden" name="phone" value="{{$visa_info->phone}}">
                                            <input type="hidden" name="email" value="{{$visa_info->email}}">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="line">
                                                            <input type ="checkbox" class ="my-checkbox" name ="passport_no" value ="{{$row->passport_no}}" {{$row->ready_for_delivery_status == 1 ? 'checked' : ''}} required/>
                                                            {{$row->passport_no}}
                                                        </div>
                                                    </div>
                                                    @if($row->ready_for_delivery_status == 1 and $row->deliverd_status == 0)
                                                        <h3 type="text" class="btn btn-info" style="padding: 5px;" title="Ready for Delivery By:{{$row->ready_for_delivery_by}}, Ready for Delivery Date Time : {{$row->ready_for_delivery_date}}">Waiting for Delivery</h3>
                                                    @elseif($row->deliverd_status == 1)
                                                        <h3 type="text" class="btn btn-success" style="padding: 5px;" title="Delivered By:{{$row->ready_for_delivery_by}}, Delivered Date Time : {{$row->ready_for_delivery_date}}">Delivered</h3>
                                                    @else
                                                        <div class="col-md-4">
                                                            <button type="submit" class="btn btn-primary" style="padding: 5px;">Send Ready for Pickup</button>
                                                        </div>
                                                   @endif
                                                </div>
                                            </div>
                                        </form>
                                        @endforeach
                                    <hr>
                                </div>
                                <div class="form-group col-md-6">
                                    <h1 style="text-align: center">Passport Delivery Section</h1>
                                    <hr>
                                    @if($visa_info->status != 6)
                                    <div class="">
                                        <h5>Customer Name : {{$visa_info->p_name}}</h5>
                                    </div>
                                    @endif
                                    <hr>
                                    @foreach($all_passport as $del)
                                        @if($del->ready_for_delivery_status == 1 and $del->deliverd_status == 0)
                                        <form method="post" action="{{route('walkIn.readyForDelivered')}}" style="margin: 10px">
                                            @csrf
                                            <input type="hidden" name="id" value="{{$visa_info->id}}">
                                            <input type="hidden" name="phone" value="{{$visa_info->phone}}">
                                            <input type="hidden" name="email" value="{{$visa_info->email}}">
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-8">
                                                        <div class="line">
                                                            <input type ="checkbox" class ="my-checkbox" name ="passport_no_d" value ="{{$del->passport_no}}" required/>
                                                            {{$del->passport_no}}
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <button type="submit" class="btn btn-primary" style="padding: 5px;">Send for Delivery</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                        @endif
                                    @endforeach
                                    <hr>
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

@endsection

@push('scripts')

@endpush
