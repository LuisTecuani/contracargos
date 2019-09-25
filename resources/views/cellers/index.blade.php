@extends('layouts.app')
@section('title',"Cellers")
@section('content')

    @foreach($role as $r)
        @if($r->role ==1)
            <div class="row">
                <div class="col-md-2 bg-light mt-2"></div>
                <div class="col-md-2 mt-2">
                    <h1><b>Contracargos Cellers</b></h1>
                    @include('contracargos.errors')
                </div>
                <div class="row">
                    <div class="col-md-2 mt-1">
                        <form method="POST" action="{{ route('cellers.store') }}">
                            @include('contracargos.admin.input_data')
                        </form>
                    </div>
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-3">
                    <div class="card bg-light mt-2">
                        <form action="{{ route('importCellers') }}" method="POST" enctype="multipart/form-data">
                            @include('contracargos.admin.import_rep')
                        </form>
                    </div>
                </div>
                <a href="/cellers/last">ultima consulta</a>
            </div>
            @include('contracargos.admin.table_results')
        @elseif($r->role == 2)
            <div class="row">
                <div class="col-md-2 bg-light mt-2"></div>
                <div class="col-md-2 mt-2">
                    @include('contracargos.errors')
                </div>
                <div class="col-md-1"></div>
                <div class="col-md-2">
                    <form method="POST" action="{{ route('cellers.store2') }}">
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
