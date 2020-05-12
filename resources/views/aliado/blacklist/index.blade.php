@extends('layouts.app')
@section('title',"Aliado")
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1><b>Blacklist Aliado</b></h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mt-2">
                @include('aliado._navLinks')
                <div class="col">
                    Añade los usuarios con contracargos de hoy.
                    <form action="{{ route('aliadoBlacklist.storeChargedback') }}" method="POST">
                        @csrf
                        <button class="btn btn-outline-success">Añadir</button>
                    </form>
                </div>
            </div>
            <div class="col-md mt-2">
                @include('aliado.blacklist._store')
            </div>
        </div>
    </div>

@endsection
