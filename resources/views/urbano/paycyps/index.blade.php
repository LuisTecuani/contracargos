@extends('layouts.app')
@section('title',"Urbano")
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4 bg-light mt-2">
                <h1><b>Urbano Paycyps</b></h1>
                @include('urbano._navLinks')
                @include('urbano.paycyps._navLinks')
            </div>
            <div class="col">
                <div class="card bg-light mt-2">
                    <form method="POST" action="{{ route('urbano.paycyps.storeCsv') }}"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="card bg-light mt-2">
                            <div class="card-header">
                                Importa files .CSV de cobro Paycyps
                            </div>
                            <div class="card-body">
                                <input type="file" name="file" accept=".csv" class="btn btn-secondary btn-lg btn-block">
                                <br>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="folio" name="folio"
                                           placeholder="Ingresa el folio de procesamiento de paycips" required>
                                </div>
                                <br>
                                <button class="btn btn-outline-primary">Import Data</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md  bg-light mt-2">
                <form method="POST" action="{{ route('urbano.paycyps.chargebackStore') }}">
                    <div class="card-header">
                        Importa contracargos PAYCYPS, inserta numeros de tarjeta para buscar.
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
            <div class="col-md  bg-light mt-2">
                <form method="POST" action="{{ route('urbano.paycyps.update') }}">
                    <div class="card-header">
                        Agrega fecha en deleted_at a PAYCYPS, inserta numeros de tarjeta para buscar.
                    </div>
                    @csrf
                    <div class="form-group">
                            <textarea class="w-100" name="cards" id="cards" pattern="\d"
                                      title="escribe tarjetas listas para buscar con 'like'.
               e.g. 223324%2345"
                                      rows="10" placeholder="tarjetas"
                                      required></textarea>
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="deleted_at" name="deleted_at"
                               placeholder="Ingresa la fecha de la baja" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-outline-primary">Registrar</button>
                    </div>
                </form>
            </div>
            <div class="col-md  bg-light mt-2">
                <div class="card bg-light mt-2">
                    <form method="POST" action="{{ route('urbano.paycyps.updateCsv') }}"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="card bg-light mt-2">
                            <div class="card-header">
                                Importa files .CSV de bajas
                            </div>
                            <div class="card-body">
                                <input type="file" name="file" accept=".csv" class="btn btn-secondary btn-lg btn-block">
                                <br>
                                <button class="btn btn-outline-primary">Import Data</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="card bg-light mt-2">
                    <form method="POST" action="{{ route('urbano.paycypsHistoric.store') }}"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="card bg-light mt-2">
                            <div class="card-header">
                                Importa files .CSV del historico de cobros Paycyps
                            </div>
                            <div class="card-body">
                                <input type="file" multiple="true" name="files[]" accept=".csv, .xls"
                                       class="btn btn-secondary btn-lg btn-block">
                                <br>
                                <button class="btn btn-outline-primary">Import Data</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <div class="card bg-light mt-2">
                    <form method="POST" action="{{ route('urbano.paycypsHistoric.storeFolios') }}"
                          enctype="multipart/form-data">
                        @csrf
                        <div class="card bg-light mt-2">
                            <div class="card-header">
                                Importa files liquidaciones.CSV del historico de cobros Paycyps. Y verifica por folio
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
