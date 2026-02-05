@extends('layouts.app')

@section('title', $page_title)

<style>
    @media print {
        .no-print {
            visibility: hidden;
        }
    }
</style>
@section('content')
    <div class="d-flex flex-column-fluid" >
        <div class="container-fluid">
            <!--begin::Notice-->
            <div class="card card-custom gutter-b no-print">
                <div class="card-header flex-wrap py-5">
                    <div class="card-title">
                        <h3 class="card-label no-print"><i class="{{ $page_icon }} text-primary"></i> {{ $sub_title }}</h3>
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
            <div class="card card-custom" style="padding-bottom: 100px !important;">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card-toolbar float-right no-print">
                                <button type="button" class="btn btn-primary btn-sm mr-3 printPage" > <i class="fas fa-print"></i>{{__('Print')}}</button>
                            </div>
                            <table class="table table-borderless table-hover ">

                                <tr>
                                   <td style="text-align: center;font-size: 20px;color: #0070c0">Visa Type :: {{ $chicklist->visaType->visa_type }} </td>
                                    <td>
                                        <embed src="{{('storage/'.$chicklist->image)}}" height="80px" width="80px" alt="image">
                                    </td>

                                </tr>

                                <tr>
                                   <td style="font-size: 20px;color: #0070c0">#Visa Category:: {{ $chicklist->title }}</td>
                                </tr>
                                <tr>
                                    <td><b>Visa fees : <span  style="color: red">{{ $chicklist->price - $chicklist->service_charge}} TK</span></b></td>
                                </tr>
                                <tr>
                                    <td><b> Service charges: <span  style="color: red">{{ $chicklist->service_charge }} TK</span></b></td>
                                </tr>
                                <tr>
                                    <td><b>Visa fees & Service charges: <span  style="color: red">{{ $chicklist->price }} TK</span></b></td>
                                </tr>

{{--                                @if(!empty($chicklist->others_price))--}}
{{--                                <tr>--}}
{{--                                    <td><b>For family under 18 child rate: <span  style="color: red">{{ $chicklist->others_price }} TK</span> </b></td>--}}
{{--                                </tr>--}}
{{--                                @endif--}}
                                <tr>
                                    <td class="ml-2">
                                        {!! $chicklist->description !!}
                                        <br>
                                        <h1>#All CHECK LISTS</h1>
                                    </td>
                                </tr>

                                @foreach($chicklist->checklistDetailes as $row)
                                <tr>
                                    <td>{{$row->checklist_name}}</td>
                                </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Card-->
        </div>
    </div>
@endsection
@push('scripts')

    <script>
        $('.printPage').click(function(){
            window.print();
            return false;
        });
    </script>

@endpush
