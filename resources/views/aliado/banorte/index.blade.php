@extends('layouts.app')
@section('title',"Aliado")
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4 bg-light mt-2">
                <h1><b>Aliado banorte</b></h1>
            @include('aliado._navLinks')
            </div>
        </div>
        <div class="row">

            <div class="col">
                <form method="POST" action="{{ route('aliado.cobroBanorteFtp') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="card-header">
                        Obtiene los usuarios a cobrar del .FTP generado este dia en el dashboard
                    </div>
                    <div class="card-body">
                        <input type="file" name="file" accept=".ftp"
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

@endsection
