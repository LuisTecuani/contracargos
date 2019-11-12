@extends('layouts.app')
@section('title',"Sanborns")
@section('content')

            <div class="row">
                <div class="col-md-2 bg-light mt-2"></div>
                <div class="col-md-10 mt-10">
                    <h2><b>Registro de Cobros y Devoluciones Sanborns</b></h2>
                </div>
            </div>
            <div class="container">
                <div class="card bg-light mt-3">
                    <div class="card-header">
                        Add file to bonificacion_sanborns table
                    </div>
                    <div class="card-body">
                        <form action="{{ route('sanbornsCobroImport') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            @include('contracargos.admin.import_sanborns')
                        </form>
                    </div>
                </div>
            </div>
            <div class="container">
                <div class="col-md-2 mt-2">
                </div>
                <div class="col-md-10 mt-10">
            @include('contracargos.errors_import')
            </div>
            </div>
@endsection
