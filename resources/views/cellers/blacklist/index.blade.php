@extends('layouts.app')
@section('title',"Cellers")
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1><b>Blacklist Cellers</b></h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mt-2">
                @include('cellers._navLinks')
                <div class="col">
                    Añade los usuarios con contracargos de hoy.
                    <form action="{{ route('aliadoBlacklist.storeChargedback') }}" method="POST">
                        @csrf
                        <button class="btn btn-outline-success">Añadir</button>
                    </form>
                </div>
            </div>
            <div class="col-md mt-2">
                <form method="POST" action="{{ route('cellersBlacklist.store') }}">
                    @csrf
                    <div class="form-group">
    <textarea class="w-100" name="emails" id="email" pattern="\d"
              title="email debe escribirse sin comas o espacios extra, separados unicamente por el salto de linea"
              rows="10" placeholder="Enter emails"
              required></textarea>
                        <button type="submit" class="btn btn-outline-primary">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <form method="POST" action="{{ route('cellersBlacklist.storeIds') }}">
                    @csrf
                    <div class="form-group">
    <textarea class="w-100" name="ids" id="ids" pattern="\d"
              title="las ids deben escribirse sin comas o espacios extra, separados unicamente por el salto de linea"
              rows="10" placeholder="Enter ids"
              required></textarea>
                        <button type="submit" class="btn btn-outline-primary">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
