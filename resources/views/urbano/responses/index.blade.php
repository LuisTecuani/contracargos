@extends('layouts.app')
@section('title',"Urbano")
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1><b>Respuestas de cobro Urbano</b></h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mt-2">
                @include('urbano._navLinks')
            </div>
        </div>
        <div class="row">
            <div class="col-md">
                <div class="card bg-light mt-2">
                    <form action="{{ route('urbano.responses.storeReps') }}" method="POST" enctype="multipart/form-data">
                        @include('contracargos.admin.import_rep')
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
