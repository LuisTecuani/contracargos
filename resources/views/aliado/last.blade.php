@extends('layouts.app')
@section('title',"Aliado")
@section('content')
    [
         @foreach($emails as $email)
    <h6>
              "{{$email->email}}",
    </h6>
    @endforeach

@endsection
