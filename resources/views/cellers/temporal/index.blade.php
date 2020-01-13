@extends('layouts.app')
@section('title',"Cellers")
@section('content')

    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1><b>Contracargos Cellers</b></h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6 mt-2">
                @include('contracargos.errors')
                @include('cellers._navLinks')
            </div>
            <div class="col-md-6 mt-2">
                <form method="POST" action="{{ route('cellers.store') }}">
                    @include('contracargos.admin.input_data')
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-md">
                <div class="card bg-light mt-2">
                    <form action="{{ route('importCellers') }}" method="POST" enctype="multipart/form-data">
                        @include('contracargos.admin.import_rep')
                    </form>
                </div>
            </div>
            <div class="col-md">
                <form method="POST" action="{{ route('cellers.banortePdf') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="card bg-light mt-2">
                        <div class="card-header">
                            Importa respuestas de cobro de banorte desde .PDF
                        </div>
                        <div class="card-body">
                            <input type="file" multiple="true" name="files[]" accept=".pdf"
                                   class="btn btn-secondary btn-lg btn-block">
                            <br>
                            <button class="btn btn-outline-success">Import Data</button>
                        </div>
                    </div>
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




