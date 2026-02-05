@extends('layouts.app')

@section('title', $page_title)
@push('styles')
@endpush

@section('content')

<form action="{{route('priovacy.update', $data->id)}}" method="POST" style="width:85%; margin-left: 35px; ">
    @csrf
    @method('PUT')
    <div class="form-group col-md-12 required">
        <label for="receiver_id">Select Policy</label>
        <select class="form-control  receiver_id" id="policy_id" name="policy_id" required="required" data-live-search="true">
            <option value="">Select </option>
            <option value="1"{{$data->policy_id == 1 ? 'selected' : ''}}>Privacy Policy </option>
            <option value="2"{{$data->policy_id == 2 ? 'selected' : ''}}>Payment & Refund policy </option>
        </select>
    </div>
    <div class="form-group">
        <textarea type="text" class="form-group" id="editor" name="details" >{{ $data->details }}</textarea>
    </div>
    <button type="cancel" class="btn btn-danger">Cancel</button>
    <button type="submit" class="btn btn-primary">Update</button>
</form>

<script src="https://cdn.ckeditor.com/ckeditor5/35.3.1/classic/ckeditor.js"></script>
<script>
    ClassicEditor
        .create( document.querySelector( '#editor' ) )
        .catch( error => {
            console.error( error );
        } );
</script>

@endsection
