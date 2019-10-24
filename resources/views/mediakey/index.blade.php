@extends('layouts.app')
@section('title',"Mediakey")
@section('content')

    @foreach($role as $r)
        @if($r->role ==1)
            <div class="row">
                <div class="col-md-2 bg-light mt-2"></div>
                <div class="col-md-2 mt-2">
                    <h1><b>Contracargos Mediakey</b></h1>
                    @include('contracargos.errors')
                </div>
                <div class="row">
                    <div class="col-md-2 mt-1">
                        <form method="POST" action="{{ route('mediakey.store') }}">
                            @include('contracargos.admin.input_data')
                        </form>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-light mt-2">
                        <form action="{{ route('importMediakey') }}" method="POST" enctype="multipart/form-data">
                            @include('contracargos.admin.import_rep')
                        </form>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-light mt-2">
                        <form method="POST" action="{{ route('mediakey.banorte') }}" enctype="multipart/form-data">
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
                </div>
            </div>
            <a href="/mediakey/last">ultima consulta</a>
            @include('contracargos.admin.table_results')
        @elseif($r->role == 2)
            <div class="row">
                <div class="col-md-2 bg-light mt-2"></div>
                <div class="col-md-2 mt-2">
                    @include('contracargos.errors')
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-2">
                    <form method="POST" action="{{ route('mediakey.store2') }}">
                        @include('contracargos.user.input_data')
                    </form>
                </div>
                <div class="col-md-3"></div>
                <div class="col-md-2"></div>
            </div>
            @include('contracargos.user.table_results')
        @else
            @include('contracargos.asignation')
        @endif
    @endforeach
@endsection
