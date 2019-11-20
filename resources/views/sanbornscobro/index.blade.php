@extends('layouts.app')
@section('title',"Sanborns")
@section('content')

    <div class="row">
        <div class="col-md-1 bg-light"></div>
        <div class="col-md-5 bg-light"><h1><b>Sanborns</b></h1></div>
        <div class="col-md-5">
            @include('contracargos.errors_import')
        </div>
        <div class="col-md-1 bg-light"></div>
        <div class="col-md-1 bg-light"></div>
        <div class="col-md-5 mt-4">
            <h3><b>Cobros</b></h3>
            <div class="card bg-light mt-1">
                <div class="card-body">
                    <form action="{{ route('sanbornsCobroImport') }}" method="POST" enctype="multipart/form-data">
                        @include('contracargos.admin.import_sanborns')
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-5 mt-4">
            <h3><b>Devoluciones</b></h3>
            <div class="card bg-light mt-1">
                <div class="card-body">
                    <form action="{{ route('sanbornsDevolucionImport') }}" method="POST" enctype="multipart/form-data">
                        @include('contracargos.admin.import_sanborns')
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-1 mt-1"></div>
    </div>
    <div class="row">
        <div class="col-md-8 mt-1"></div>
        <div class="col-md-3 mt-1">
            <form method="POST" action="{{ route('sanbornsNumberChargesReturns') }}">
                @csrf
                <button type="submit" class="btn btn-outline-primary mt-lg-3">
                    Actualizar veces cobro y devoluciones
                </button>
            </form>
        </div>
        <div class="col-md-1 mt-1"></div>
        <div class="col-md-1 bg-light"></div>
        <div class="col-md-5 mt-4">
            <h3><b>Cobros Y Devoluciones</b></h3>
            <div class="card bg-light mt-1">
                <div class="card-body">
                    <form action="{{ route('sanbornsStoreChargesReturnsImport') }}" method="POST" enctype="multipart/form-data">
                        @include('contracargos.admin.import_sanborns')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
