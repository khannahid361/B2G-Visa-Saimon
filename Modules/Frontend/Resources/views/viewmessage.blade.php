@extends('layouts.app')

@section('title', $page_title)
@push('styles')
@endpush

@section('content')

<div class="modal-body">
    <div class="d-flex">
        <h6>Name - </h6>
        <p>{{ $data->name}}</p>
    </div>
    <br>
    <div class="d-flex">
        <h6>Email - </h6>
        <p>{{ $data->email}}</p>
    </div>
    <br>
    <div class="d-flex">
        <h6>Phone Number - </h6>
        <p>{{ $data->phone}}</p>
    </div>
    <br>
    <div class="d-flex">
        <h6>Subject - </h6>
        <p>{{ $data->subject}}</p>
    </div>
    <br>
    <div class="d-flex">
        <h6>Message - </h6>
        <p>{{ $data->message}}</p>
    </div>
</div>
<div class="footer">
<a href="{{route('contactus')}}"  class="btn btn-secondary">Back</a></div>

@endsection