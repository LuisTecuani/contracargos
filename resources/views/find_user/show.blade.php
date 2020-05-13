@extends('layouts.app')
@section('title',"Bins")
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1><b>FIND USERS TOOL</b></h1>
            </div>
        </div>
        <div class="row">
            <div class="col">
                @include('bins._textBoxFindUser')
            </div>
        </div>
        <div class="row">
            <div class="col">

            </div>
        </div>
    </div>

@endsection
