@extends('layouts.app')
@section('title',"Urbano")
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4 bg-light mt-2">
                <h1><b>Urbano Affinitas</b></h1>
                @include('urbano._navLinks')
            </div>
            <div class="row">
                <div class="col">
                    <div class="card bg-light mt-2">
                        <form method="POST" action="{{ route('urbano.affinitas.store') }}"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="card bg-light mt-2">
                                <div class="card-header">
                                    Importa files .CSV de cobro Affinitas
                                </div>
                                <div class="card-body">
                                    <input type="file" multiple="true" name="files[]" accept=".csv"
                                           class="btn btn-secondary btn-lg btn-block">
                                    <br>
                                    <button class="btn btn-outline-primary">Import Data</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-7  bg-light mt-2">
                <form method="POST" action="{{ route('urbano.affinitas.chargebackStore') }}">
                    <div class="card-header">
                        Importa contracargos AFFINITAS, inserta numeros de tarjeta para buscar.
                    </div>
                    @csrf
                    <div class="form-group">
                            <textarea class="w-100" name="cards" id="cards" pattern="\d"
                                      title="escribe tarjetas listas para buscar con 'like'.
               e.g. 223324&2345"
                                      rows="10" placeholder="tarjetas"
                                      required></textarea>
                    </div>
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
        <div class="row">
            <div class="col">
                <div class="card bg-light mt-2">
                    <form method="POST" action="{{ route('urbano.affinitasHistoric.store') }}"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="card bg-light mt-2">
                            <div class="card-header">
                                Importa files .CSV del historico de cobros Affinitas
                            </div>
                            <div class="card-body">
                                <input type="file" multiple="true" name="files[]" accept=".csv"
                                       class="btn btn-secondary btn-lg btn-block">
                                <br>
                                <button class="btn btn-outline-primary">Import Data</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
