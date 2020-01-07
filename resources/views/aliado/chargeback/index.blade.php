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
                @include('aliado.chargeback._navLinks')
            </div>
            <div class="col-md mt-2">
                <form method="POST" action="{{ route('aliadoChargeback.store') }}">
                    @include('contracargos.admin.input_data')
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-md">
                <div class="card bg-light mt-2">
                    <form action="{{ route('aliadoChargeback.storeTxt') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="card-header">
                            Importa contracargos inserta texto
                        </div>
                        <textarea class="w-100" name="text" id="text"
                                  title="insert text"
                                  rows="10" placeholder="Enter text"
                                  required></textarea>
                        <div class="form-group">
                            <input type="text" class="form-control" id="chargeback_date" name="chargeback_date"
                                   placeholder="Ingresa la fecha del contracargo" required>
                        </div>
                        <div class="form-group">

                            <button type="submit" class="btn btn-outline-primary">Registrar</button>
                        </div>

                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12 mt-2">
                @include('contracargos.admin.table_results')
            </div>
        </div>
    </div>

@endsection
