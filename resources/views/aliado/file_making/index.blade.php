@extends('layouts.app')
@section('title',"Aliado")
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4 bg-light mt-2">
                <h1><b>Creacion de archivos Aliado para el cobro</b></h1>
                @include('aliado._navLinks')
                @include('aliado.file_making._navLinks')
            </div>
            <div class="col mt-5">
                <h2><b>Herramienta para realizar los cobros del dia</b></h2>
                <div class="row">
                    <div class="col">
                        <div class="accordion" id="accordionExample">
                            <div class="card">
                                <div class="card-header" id="headingOne">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                            Actualizar base de datos
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <a href="https://www.dropbox.com/sh/eg9ekf78gfut6yo/AACVGOILfiTpY713qJ6cofjVa?dl=0">Dumps</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" id="headingTwo">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                            Hacer los contracargos
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <a href="https://docs.google.com/spreadsheets/d/1DXFXaVIMbutG75KeiWgP8lg2WKzEuUENevw_wXdS7LU/edit?pli=1#gid=0">Prosa</a>
                                        <br>
                                        <a href="https://docs.google.com/spreadsheets/d/1Axi5TxmYobmfMmoguZQ8UtqrLvupbamtAhbPN9P05E0/edit?ts=5e05185f#gid=0">Banorte</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" id="headingThree">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                            Actualizar blacklist
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapseThree" class="collapse" aria-labelledby="headingThree" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <div>
                                            <a href="https://docs.google.com/spreadsheets/d/1gfEnD1kJmvBblV3IMkjZwUbh_4VqENuVtW5yTKSjseQ/edit?ts=5e1f93a6#gid=108792894">Spreadsheet callcenter</a>
                                        </div>
                                        @include('aliado.blacklist._store')
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" id="headingFour">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                            Obtener FTP del dashboard
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapseFour" class="collapse" aria-labelledby="headingFour" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <a href="https://aliadoeticket.com/admin/billing">aliadoEticket</a>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" id="headingFive">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                            Obtener usuarios rechazados por PROSA en los ultimos tres dias de cobro.
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapseFive" class="collapse" aria-labelledby="headingFive" data-parent="#accordionExample">
                                    <div class="card-body">
                                        @include('aliado.file_making._rejectedProsa')
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" id="headingSix">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                            Obtener usuarios FTP
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapseSix" class="collapse" aria-labelledby="headingSix" data-parent="#accordionExample">
                                    <div class="card-body">
                                        @include('aliado.file_making._storeFtp')
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" id="headingSeven">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                                            Agregar usuarios extra en caso de ser necesario.
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven" data-parent="#accordionExample">
                                    <div class="card-body">
                                        @include('aliado.file_making._storeTextbox')
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" id="headingEight">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                                            Hacer ftp para cobro por prosa 0897.
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapseEight" class="collapse" aria-labelledby="headingEight" data-parent="#accordionExample">
                                    <div class="card-body">
                                        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" id="headingNine">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseNine" aria-expanded="false" aria-controls="collapseNine">
                                            Añadir a la tabla repsaliado la respuesta del cobro .REP.
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapseNine" class="collapse" aria-labelledby="headingNine" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <div class="row">
                                            @include('aliado.responses._storeRep')
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" id="headingTen">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTen" aria-expanded="false" aria-controls="collapseTen">
                                            Hacer csv para cobro banorte con rechazos.
                                            <br>
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapseTen" class="collapse" aria-labelledby="headingTen" data-parent="#accordionExample">
                                    <div class="card-body">
                                        @include('aliado.file_making._rejectedToBanorte')
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" id="headingEleven">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseEleven" aria-expanded="false" aria-controls="collapseEleven">
                                            Añadir a la tabla respuestas_banorte_aliado la respuesta del cobro .PDF.
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapseEleven" class="collapse" aria-labelledby="headingEleven" data-parent="#accordionExample">
                                    <div class="card-body">
                                        @include('aliado.responses._storePdf')
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" id="headingTwelve">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseTwelve" aria-expanded="false" aria-controls="collapseTwelve">
                                            Hacer ftp con rechazados para prosa 3918.
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapseTwelve" class="collapse" aria-labelledby="headingTwelve" data-parent="#accordionExample">
                                    <div class="card-body">
                                        @include('aliado.file_making._rejectedTo3918')
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" id="headingThirteen">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseThirteen" aria-expanded="false" aria-controls="collapseThirteen">
                                            Añadir a la tabla repsaliado la respuesta del cobro .REP.
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapseThirteen" class="collapse" aria-labelledby="headingThirteen" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <div class="row">
                                            @include('aliado.responses._storeRep')
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" id="headingFourteen">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseFourteen" aria-expanded="false" aria-controls="collapseFourteen">
                                            Subir a la spreadsheet los datos de las respuestas.
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapseFourteen" class="collapse" aria-labelledby="headingFourteen" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <a href="https://docs.google.com/spreadsheets/d/1gWsqZcsf-VjZo-2dKhk2Mjqqn3aRBotb9n90BMKCi-c/edit#gid=1864749622">
                                            DATA - Liquidaciones bancarias AS+ y ETK
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" id="headingFifteen">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseFifteen" aria-expanded="false" aria-controls="collapseFifteen">
                                            Subir al dropbox los files de respuesta.
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapseFifteen" class="collapse" aria-labelledby="headingFifteen" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <a href="https://www.dropbox.com/home/Respuestas%20de%20cobranza/Aliado%20Eticket">
                                            Respuestas de cobranza Aliado E Ticket
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="card">
                                <div class="card-header" id="headingSixteen">
                                    <h2 class="mb-0">
                                        <button class="btn btn-link collapsed" type="button" data-toggle="collapse" data-target="#collapseSixteen" aria-expanded="false" aria-controls="collapseSixteen">
                                            Enviar a doug los files de respuesta para el registro.
                                        </button>
                                    </h2>
                                </div>
                                <div id="collapseSixteen" class="collapse" aria-labelledby="headingSixteen" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <a href="https://mail.google.com/mail/u/0/#inbox?compose=CllgCJvqJqMnJDsFPDjkzqpndvCXCGNttZKFlBZfnNwGqJkdMQnDTTKtZPkzqTPWhTzKnsMJpsV">
                                            Gmail
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

