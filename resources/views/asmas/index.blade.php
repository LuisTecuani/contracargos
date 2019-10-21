@extends('layouts.app')
@section('title',"Asmas")
@section('content')


            <div class="row">
                <div class="col-md-2 bg-light mt-2"></div>
                <div class="col-md-2 mt-2">
                    <h1><b>Rechazados aliado</b></h1>
                    @include('contracargos.errors')
                </div>
                <div class="row">
                    <div class="col-md-2 mt-1">
                        <form method="POST" action="{{ route('asmas.store') }}">
                            @include('contracargos.admin.input_data')
                        </form>
                    </div>
                </div>
                <div class="col-md-1"></div>
                <div class="col">
                    <form method="POST" action="{{ route('aliado.rechazados') }}" enctype="multipart/form-data">
                        @csrf
                        <div class="card-header">
                            Importa respuestas rechazadas de cobranza (REP)
                        </div>
                        <div class="card-body">
                            <input type="file" multiple="true" name="files[]" accept=".rep"
                                   class="btn btn-secondary btn-lg btn-block">
                            <br>
                            <button class="btn btn-outline-success">Import Data</button>
                        </div>
                    </form>
                </div>
            </div>


@endsection
