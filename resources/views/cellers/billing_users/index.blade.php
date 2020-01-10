@extends('layouts.app')
@section('title',"Cellers")
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4 bg-light mt-2">
                <h1><b>Cobro cellers</b></h1>
                @include('cellers._navLinks')
                @include('cellers.file_making._navLinks')
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <h3>Usuarios a cobrar</h3>

                <h6>Prosa {{$expUsers}}</h6><br>
                <h6>Banorte {{$vigUsers}}</h6>

            </div>

        </div>
    </div>

@endsection
