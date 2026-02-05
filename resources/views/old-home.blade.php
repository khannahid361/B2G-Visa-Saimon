@extends('layouts.app')

@section('title','Dashboard')

@push('styles')
<link rel="stylesheet" href="css/chart.min.css">
<style>
    .today-btn {
        border-radius: 5px 0 0 5px !important;
    }

    .week-btn,
    .month-btn {
        border-radius: 0 !important;
    }

    .year-btn {
        border-radius: 0 5px 5px 0 !important;
    }

    .icon {
        width: 40px;
        height: 40px;
    }
</style>
@endpush

@section('content')
<div class="d-flex flex-column-fluid">

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-4 mb-5 text-primary bold">
                <div class="bg-white text-center py-3 text-primary bold">
                    <a href="{{route('collection')}}">
                        <div><img src="{{asset('icon/income.png')}}" alt="income"></div>
                        <h6>{{ $collection ?? 0 }} TK</h6>
                        <h6>{{__('Collection')}}</h6>
                    </a>
                </div>
            </div>

            <div class="col-md-4 mb-5">
                <div class="bg-white text-center py-3 text-primary bold">
                    <a href="{{route('totalDue')}}">
                        <div><img src="{{asset('icon/due.png')}}" alt="supplier" style="height: 64px"></div>
                        <h6>{{ $due ?? 0}}</h6>
                        <h6>{{__('Due')}}</h6>
                    </a>
                </div>
            </div>
            <div class="col-md-4 mb-5">
                <div class="bg-white text-center py-3 text-primary bold">
                    <a href="{{route('received')}}">
                        <div><img src="{{asset('icon/received.jpg')}}" alt="labor" style="height: 64px"></div>
                        <h6>{{ $received ?? 0}}</h6>
                        <h6>{{__('Application Received')}}</h6>
                    </a>
                </div>
            </div>
            <div class="col-md-4 mb-5">
                <div class="bg-white text-center py-3 text-primary bold">
                    <a href="{{route('processing')}}">
                        <div><img src="{{asset('icon/processing.jpg')}}" alt="bank" style="height: 64px"></div>
                        <h6>{{ $processing ?? 0}}</h6>
                        <h6>{{__('Application in Process')}}</h6>
                    </a>
                </div>
            </div>
            <div class="col-md-4 mb-5">
                <div class="bg-white text-center py-3 text-primary bold">
                    <a href="{{route('missing.document')}}">
                        <div><img src="{{asset('icon/missing.png')}}" alt="bank" style="height: 64px"></div>
                        <h6>{{ $missing_Documents ?? 0}}</h6>
                        <h6>{{__('Required Missing Documents')}}</h6>
                    </a>
                </div>
            </div>
            <div class="col-md-4 mb-5">
                <div class="bg-white text-center py-3 text-primary bold">
                    <a href="{{route('submittedEmbassy')}}">
                        <div><img src="{{asset('icon/material.png')}}" alt="bank" style="height: 64px"></div>
                        <h6>{{ $submitted_to_embassy ?? 0}}</h6>
                        <h6>{{__('Submitted to embassy')}}</h6>
                    </a>
                </div>
            </div>
            <div class="col-md-4 mb-5">
                <div class="bg-white text-center py-3 text-primary bold">
                    <a href="{{route('delivery')}}">
                        <div><img src="{{asset('icon/ready_delivery.png')}}" alt="bank" style="height: 64px"></div>
                        <h6>{{ $ready_for_delivery ?? 0}}</h6>
                        <h6>{{__('Ready for delivery')}}</h6>
                    </a>
                </div>
            </div>
            <div class="col-md-4 mb-5">
                <div class="bg-white text-center py-3 text-primary bold">
                    <a href="{{route('delivered')}}">
                        <div><img src="{{asset('icon/delivered.png')}}" alt="bank" style="height: 64px"></div>
                        <h6>{{ $delivered ?? 0}}</h6>
                        <h6>{{__('Delivered')}}</h6>
                    </a>
                </div>
            </div>
            {{-- <div class="col-md-4 mb-5">--}}
                {{-- <div class="bg-white text-center py-3 text-primary bold">--}}
                    {{-- <a href="{{route('reject')}}">--}}
                        {{-- <div><img src="{{asset('icon/rejected.png')}}" alt="cash" style="height: 64px"></div>
                        --}}
                        {{-- <h6>{{$reject?? 0 }}</h6>--}}
                        {{-- <h6>{{__('Visa Reject')}}</h6>--}}
                        {{-- </a>--}}
                    {{-- </div>--}}
                {{-- </div>--}}

            <div class="col-md-4 mb-5">
                <div class="bg-white text-center py-3 text-primary bold">
                    <a href="{{url('all-application')}}">
                        <div><img src="{{asset('icon/application.png')}}" alt="material" style="height: 64px"></div>
                        <h6>{{$application ?? 0 }}</h6>
                        <h6>{{__('Visa Applications')}}</h6>
                    </a>
                </div>
            </div>
            <div class="col-md-4 mb-5">
                <div class="bg-white text-center py-3 text-primary bold">
                    <a href="{{route('customer')}}">
                        <div><img src="{{asset('icon/customer.png')}}" alt="customer"></div>
                        <h6>{{$customer ?? 0 }}</h6>
                        <h6>{{__('Customers')}}</h6>
                    </a>
                </div>
            </div>
            <div class="col-md-4 mb-5">
                <div class="bg-white text-center py-3 text-primary bold">
                    <a href="{{route('user')}}">
                        <div><img src="{{asset('icon/user.png')}}" alt="user"></div>
                        <h6>{{ $employee ?? 0 }}</h6>
                        <h6>{{__('Employees')}}</h6>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')

@endpush