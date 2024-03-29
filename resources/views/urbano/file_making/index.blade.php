@extends('layouts.app')
@section('title',"Urbano")
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4 bg-light mt-2">
                <h1><b>Creacion de archivos Urbano para el cobro</b></h1>
                @include('urbano._navLinks')
                @include('urbano.file_making._navLinks')
            </div>
            <div class="col mt-5">
                <h2><b>Herramienta para preparar el cobro del dia</b></h2>
                <div class="row">
                    <div class="col">
                        <div class="card bg-light mt-2">
                            <form method="POST" action="{{ route('urbano.billing_users.storeFtp') }}"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <div class="card-header">
                                        Obtiene los usuarios a cobrar de un archivo .FTP
                                        <input type="file" name="file" accept=".ftp"
                                               class="btn btn-secondary btn-lg btn-block" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="procedence" name="procedence"
                                           placeholder="Ingresa la procedencia de los usuarios" required>
                                </div>
                                <button class="btn btn-outline-success">Import Data</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="card bg-light mt-2">
                            <form method="POST" action="{{ route('urbano.billing_users.storeRejectedProsa') }}"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <div class="card-header">
                                        Selecciona para ingresar los usuarios rechazados por PROSA en los ultimos tres dias de cobro.
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="procedence" name="procedence"
                                           placeholder="Ingresa la procedencia de los usuarios" required>
                                </div>
                                <button class="btn btn-outline-success">Import Data</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div id="collapseTen"  aria-labelledby="headingTen" >
                            <div class="card-body">
                                @include('urbano.file_making._rejectedToBanorte')
                            </div>
                            <div class="pt-4">
                                <ul class="nav flex-column">
                                    <li class="nav-item pt-2">
                                        <a href="/urbano/file_making/exportBanorte" class="btn btn-outline-danger">crear CSV para cobro banorte</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="card bg-light mt-2">
                            <form method="POST" action="{{ route('urbano.billing_users.storeTextbox') }}"
                                  enctype="multipart/form-data">
                                @csrf
                                <div class="form-group">
                                    <div class="card-header">
                                        Ingresa los id de los usuarios a cobrar.
                                        <br>
                                        <textarea class="w-100" name="ids" id="ids" pattern="\d"
                                                  title="Los user_id deben ingresarse uno por fila, sin ningun caracter especial ni espacios."
                                                  rows="10" placeholder="user_id" required></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="procedence" name="procedence"
                                           placeholder="Ingresa la procedencia de los usuarios" required>
                                </div>
                                <button class="btn btn-outline-success">Import Data</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col">

            </div>
        </div>
    </div>

@endsection
