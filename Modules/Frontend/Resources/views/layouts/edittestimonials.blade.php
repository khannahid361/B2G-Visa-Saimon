@extends('layouts.app')

@section('title', $page_title)
@push('styles')
@endpush

@section('content')

<form action="{{route('testimonial.update',$data->id)}}" method="POST" enctype="multipart/form-data" style="width:85%; margin-left: 35px; ">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label for="recipient-name" class="col-form-label">Name</label>
        <input type="text" class="form-control" id="recipient-name" name="name" value="{{ $data->name }}" required>
    </div>
    <div class="form-group">
        <label for="message-text">Details</label>
        <textarea class="form-control" id="message-text" name="details" rows="6" required>{{ $data->details}}</textarea>
    </div>
    <div class="form-group">
        <label for="formFile" class="form-label">Image</label>
        <input class="form-control" type="file" id="formFile" name="image" value=" {{( $data->image )}} " >
    </div>
    <button type="cancel" class="btn btn-danger">Cancel</button>
    <button type="submit" class="btn btn-primary">Update</button>
</form>

@endsection