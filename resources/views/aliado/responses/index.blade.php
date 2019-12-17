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
            <div class="col-md-4">
                <div class="card bg-light mt-2">
                    <form action="{{ route('aliado.responses.storeReps') }}" method="POST" enctype="multipart/form-data">
                        @include('contracargos.admin.import_rep')
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <form method="POST" action="{{ route('aliado.responses.storePdf') }}" enctype="multipart/form-data">
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
    </div>

@endsection
