@extends('layouts.app')
@section('title',"Cellers")
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4 bg-light mt-2">
                <h1><b>Cellers Paycyps</b></h1>
                @include('cellers._navLinks')
                @include('cellers.paycyps._navLinks')
            </div>
            <div class="row">
                <div class="col">
                    <div class="card bg-light mt-2">
                        <form method="POST" action="{{ route('cellers.paycyps.storeCsv') }}"
                              enctype="multipart/form-data">
                            @csrf
                            <div class="card bg-light mt-2">
                                <div class="card-header">
                                    Importa files .CSV de cobro Paycyps
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
            <div class="row">
                <div class="col-md-7  bg-light mt-2">
                    <form method="POST" action="{{ route('cellers.paycyps.chargebackStore') }}">
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
            </div>
            <div class="row">
                <div class="col-md-8  bg-light mt-2">
                    <form method="POST" action="{{ route('cellers.paycyps.update') }}">
                        <div class="card-header">
                            Agrega fecha de confirmacion de cargo PAYCYPS, inserta numeros de tarjeta para buscar.
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
                            <input type="text" class="form-control" id="bill_date" name="bill_date"
                                   placeholder="Ingresa la fecha de la confirmacion" required>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="btn btn-outline-primary">Registrar</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection
