@extends('layouts.app')
@section('title',"Sanborns")
@section('content')

    <div class="row">
        <div class="col-md-1 bg-light mt-1"></div>
        <div class="col-md-5 mt-5">
            <h2><b>Cobros</b></h2>
            <div class="card bg-light mt-4">
                <div class="card-body">
                    <form action="{{ route('sanbornsCobroImport') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @include('contracargos.admin.import_sanborns')
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-5 mt-5">
            <h2><b>Devoluciones</b></h2>
            <div class="card bg-light mt-4">
                <div class="card-body">
                    <form action="{{ route('sanbornsDevolucionImport') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @include('contracargos.admin.import_sanborns')
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-1 mt-1">
        </div>
    </div>
    <div class="container">
        <div class="col-md-2 mt-2">
        </div>
        <div class="col-md-10 mt-10">
            @include('contracargos.errors_import')
        </div>
    </div>
@endsection
