@extends('layouts.app')
@section('title',"The Hive Experience")
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4 bg-light mt-2">
                <h1><b>Cobro thx</b></h1>
                @include('thx._navLinks')
                @include('thx.file_making._navLinks')
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <h3>Usuarios a cobrar</h3>
                <h6>Usuarios agregados {{$billUsers}}</h6><br>
            </div>

        </div>
    </div>

@endsection
