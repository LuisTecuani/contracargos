@extends('layouts.app')
@section('title',"Aliado")
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1><b>Contracargos Aliado</b></h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mt-2">
                @include('contracargos.errors')
                @include('aliado._navLinks')
            </div>
            <div class="col-md mt-2">
                <form method="POST" action="{{ route('aliado.store') }}">
                    @include('contracargos.admin.input_data')
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-12 mt-2">
                @include('contracargos.admin.table_results')
            </div>
        </div>
    </div>

@endsection
