@extends('layouts.app')
@section('title',"Cellers")
@section('content')
    [
    @foreach($emails as $email)
        <h6>
            "{{$email->email}}",
        </h6>
    @endforeach

@endsection
