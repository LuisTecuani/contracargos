@extends('layouts.app')
@section('title',"Sanborns")
@section('content')

    <div class="row">
        <div class="col-md-1 bg-light"></div>
        <div class="col-md-5 bg-light"><h1><b>Sanborns</b></h1></div>
        <div class="col-md-5"></div>
        <div class="col-md-1 bg-light"></div>
    </div>
    <div class="row">
        <div class="col-md-1 bg-light"></div>
        <div class="col-md-5 mt-1">
            <h3><b>Cobros Y Devoluciones</b></h3>
            <div class="card bg-light mt-1">
                <div class="card-body">
                    <form action="{{ route('sanbornsStoreChargesReturnsImport') }}" method="POST"
                          enctype="multipart/form-data">
                        @include('contracargos.admin.import_sanborns')
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-1 bg-light"></div>
        <div class="col-md-2 mt-1">
            <h3>Ingrese usuario's</h3>
            <form method="POST" action="{{ route('sanbornsSearch') }}">
                <div class="form-group">
                    @csrf
                    <textarea name="sanborns_id" id="sanborns_id" cols="15" rows="10"
                              placeholder="Ingrese Sanborns_id a buscar"></textarea>
                    <button type="submit" class="btn btn-outline-primary mt-lg-3">Consultar</button>
                </div>
            </form>
        </div>
        <div class="col-md-2 bg-light">@include('contracargos.errors_import')</div>
        <div class="col-md-1 bg-light"></div>
    </div>
    <div class="row">
        <div class="col-md-2 bg-light"></div>
        @include('contracargos.admin.sanborns_table')
        <div class="col-md-2 bg-light"></div>
    </div>


@endsection
