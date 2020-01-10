@extends('layouts.app')
@section('title',"Mediakey")
@section('content')
    @foreach($emails as $email)
        <h6>"{{$email->email}}",</h6>
    @endforeach

@endsection
