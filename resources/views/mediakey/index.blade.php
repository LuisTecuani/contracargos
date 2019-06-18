@extends('layouts.app')
@section('title',"Mediakey")
@section('content')
    {{$var=1}}

    <div class="row">
        <div class="col-md-2 bg-light mt-2"></div>
        <div class="col-md-2">
            @include('contracargos.errors')
        </div>
        @if($var==1)
            <div class="row">
                <div class="col-md-2 mt-1">
                    <form method="POST" action="{{ route('mediakey.store') }}">
                        @include('contracargos.admin.input_data')
                    </form>
                </div>
            </div>
            <div class="col-md-1"></div>
            <div class="col-md-3">
                <div class="card bg-light mt-2">
                    <form action="{{ route('importMediakey') }}" method="POST" enctype="multipart/form-data">
                        @include('contracargos.admin.import_rep')
                    </form>
                </div>
            </div>
    </div>
    @include('contracargos.admin.table_results')
    @elseif($var==2)
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
    @endif
@endsection
