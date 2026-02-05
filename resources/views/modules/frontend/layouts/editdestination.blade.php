@extends('layouts.app')

@section('title', $page_title)
@push('styles')
@endpush

@section('content')

<form action="{{route('destination.update',$data->id)}}" method="POST" enctype="multipart/form-data" style="width:85%; margin-left: 35px; ">
    @csrf
    @method('PUT')
    <div class="form-group">
        <label for="recipient-name" class="col-form-label">Country Name</label>
        <div class="form-group">
            <div class="form-group col-md-12 required">
                <label for="receiver_id">Destination Name</label>
                <select class="form-control selectpicker name" id="name" name="name" required="required" data-live-search="true">
                    <option value="">Select </option>
                    @foreach($countries as $row)
                        <option value="{{$row->id}}" {{$row->id == $data->name ? 'selected' : ''}}>{{$row->country_name}}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="formFile" class="form-label">Image</label>
        <input class="form-control" type="file" id="formFile" name="image" value=" {{( $data->image )}} " >
    </div>
    <button type="cancel" class="btn btn-danger">Cancel</button>
    <button type="submit" class="btn btn-primary">Update</button>
</form>

@endsection
