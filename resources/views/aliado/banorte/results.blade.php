@extends('layouts.app')
@section('title',"Aliado")
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4 bg-light mt-2">
                <h1><b>Cobro aliado</b></h1>
            @include('aliado._navLinks')
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <h3>Usuarios a cobrar</h3>

                <h6>Total {{$users}}</h6>

            </div>

        </div>
    </div>

@endsection
