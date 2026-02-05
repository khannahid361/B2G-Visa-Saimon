@extends('layouts.app')

@section('title', $page_title)
@push('styles')
@endpush

@section('content')

<form action="{{route('faq.update',$data->id)}}" method="POST" style="width:85%; margin-left: 35px; ">
        @csrf
        @method('PUT')
    <div class="form-group">
        <label for="exampleFormControlTextarea1">Question</label>
        <textarea class="form-control" id="exampleFormControlTextarea1" name="question" rows="4" required >{{ $data->question}}</textarea>
    </div>
    <div class="form-group">
        <label for="exampleFormControlTextarea1">Answer</label>
        <textarea class="form-control" id="exampleFormControlTextarea1" name="answer" rows="6" required >{{ $data->answer}}</textarea>
    </div>
    <button type="cancel" class="btn btn-danger">Cancel</button>
    <button type="submit" class="btn btn-primary">Update</button>
</form>

@endsection