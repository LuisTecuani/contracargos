@extends('layouts.app')
@section('title',"Cellers")
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1><b>Cobranza Cellers</b></h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mt-2">
                @include('cellers._navLinks')
            </div>
        </div>
    </div>

@endsection
