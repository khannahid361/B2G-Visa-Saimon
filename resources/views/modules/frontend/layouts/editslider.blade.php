@extends('layouts.app')

@section('title', $page_title)
@push('styles')
@endpush

@section('content')

<form action="{{route('slider.update',$data->id)}}" method="POST" enctype="multipart/form-data" style="width:85%; margin-left: 35px; ">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label for="recipient-name" class="col-form-label">Message</label>
        <input type="text" class="form-control" id="recipient-name" name="message" value="{{ $data->message }}" required>
    </div>
    <div class="form-group">
        <label for="formFile" class="form-label">Image</label>
        <input class="form-control" type="file" id="formFile" name="image" value=" {{( $data->image )}} " >
    </div>
    <button type="cancel" class="btn btn-danger">Cancel</button>
    <button type="submit" class="btn btn-primary">Update</button>
</form>

@endsection