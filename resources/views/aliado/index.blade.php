@extends('layouts.app')
@section('title',"Aliado")
@section('content')

            <div class="row">
                <div class="col-md-2 bg-light mt-2"></div>
                <div class="col-md-2 mt-2">
                    <h1><b>Contracargos Aliado</b></h1>
                    @include('contracargos.errors')
                </div>
                <div class="row">
                    <div class="col-md-2 mt-1">
                        <form method="POST" action="{{ route('aliado.store') }}">
                            @include('contracargos.admin.input_data')
                        </form>
                    </div>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-3">
                    <div class="card bg-light mt-2">
                        <form action="{{ route('importAliado') }}" method="POST" enctype="multipart/form-data">
                            @include('contracargos.admin.import_rep')
                        </form>
                    </div>
                    <div class="col">
                        <form method="POST" action="{{ route('aliado.banorte') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="card-header">
                                Importa respuestas de cobro de banorte desde .XML
                            </div>
                            <div class="card-body">
                                <input type="file" multiple="true" name="files[]" accept=".xml"
                                       class="btn btn-secondary btn-lg btn-block">
                                <br>
                                <button class="btn btn-outline-success">Import Data</button>
                            </div>
                        </form>
                    </div>
                    <div class="col">
                        <form method="POST" action="{{ route('aliado.banortePdf') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="card-header">
                                Importa respuestas de cobro de banorte desde .PDF
                            </div>
                            <div class="card-body">
                                <input type="file" multiple="true" name="files[]" accept=".pdf"
                                       class="btn btn-secondary btn-lg btn-block">
                                <br>
                                <button class="btn btn-outline-success">Import Data</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
            <a href="/aliado/last">ultima consulta</a>
            @include('contracargos.admin.table_results')

            <div class="row">
                <div class="col-md-2 bg-light mt-2"></div>
                <div class="col-md-2 mt-2">

                </div>
                <div class="col-md-1"></div>

                <div class="col-md-3"></div>
                <div class="col-md-2"></div>
            </div>


@endsection
