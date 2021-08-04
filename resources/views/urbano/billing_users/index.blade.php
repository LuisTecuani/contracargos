@extends('layouts.app')
@section('title',"Urbano")
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4 bg-light mt-2">
                <h1><b>Cobro urbano</b></h1>
                @include('urbano._navLinks')
                @include('urbano.file_making._navLinks')
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
