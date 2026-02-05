@extends('layouts.app')

@section('title', $page_title)
@push('styles')
@endpush

@section('content')

<form action="{{route('service.update', $data->id)}}" method="POST" style="width:85%; margin-left: 35px; ">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label for="recipient-name" class="col-form-label">Name</label>
        <input type="text" class="form-control" id="recipient-name" name="name" value="{{ $data->name}}" required>
    </div>
    <div class="form-group">
        <label for="exampleFormControlTextarea1">Details</label>
        <textarea class="form-control" id="editor" name="details" rows="6" required>{{ $data->details}}</textarea>
    </div>
    <button type="cancel" class="btn btn-danger">Cancel</button>
    <button type="submit" class="btn btn-primary">Update</button>
</form>

<script src="https://cdn.ckeditor.com/ckeditor5/35.3.1/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create(document.querySelector('#editor'))
        .catch(error => {
            console.error(error);
        });
</script>
@endsection
