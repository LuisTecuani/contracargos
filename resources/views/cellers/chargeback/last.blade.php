@extends('layouts.app')
@section('title',"Cellers")
@section('content')
    [
    @foreach($ids as $row)
        <h6>
            "{{$row->user_id}}",
        </h6>
    @endforeach

@endsection
