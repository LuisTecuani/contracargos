@extends('layouts.app')
@section('title',"Thx")
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1><b>Respuestas de cobro Thx</b></h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mt-2">
                @include('thx._navLinks')
            </div>
        </div>
        <div class="row">
            <div class="col-md">
                <div class="card bg-light mt-2">
                    <form action="{{ route('thx.responses.storeReps') }}" method="POST" enctype="multipart/form-data">
                        @include('contracargos.admin.import_rep')
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
