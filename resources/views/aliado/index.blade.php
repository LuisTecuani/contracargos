@extends('layouts.app')
@section('title',"Aliado")
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1><b>Cobranza Aliado</b></h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mt-2">
                @include('aliado._navLinks')
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mt-2">
                {{ $string }}
            </div>
        </div>
    </div>

@endsection
