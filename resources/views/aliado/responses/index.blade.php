@extends('layouts.app')
@section('title',"Aliado")
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1><b>Respuestas de cobro Aliado</b></h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mt-2">
                @include('aliado._navLinks')
            </div>
        </div>
        <div class="row">
            @include('aliado.responses._storeRep')
            @include('aliado.responses._storePdf')
        </div>
    </div>

@endsection
