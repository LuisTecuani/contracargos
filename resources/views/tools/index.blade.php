@extends('layouts.app')
@section('title',"Herramientas")
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1><b>Herramientas compartidas</b></h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mt-2">
                @include('tools._navLinks')
            </div>
        </div>
        <div class="row">
            <div class="col-md">
                <div class="card bg-light mt-2">
                    <form action="{{ route('fileProcessor.store') }}" method="POST" enctype="multipart/form-data">
                        @include('contracargos.admin.import_rep')
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
