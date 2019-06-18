@extends('layouts.app')
@section('title',"Mediakey")
@section('content')
    {{$var=2}}

    <div class="row">
        <div class="col-md-2 bg-light mt-2"></div>
        <div class="col-md-2">
            @include('contracargos.errors')
        </div>
        @if($var==1)
            <div class="row">
                <div class="col-md-2 mt-1">
                    <form method="POST" action="/mediakey">
                        @csrf
                        @include('contracargos.admin.input_data')
                    </form>
                </div>
            </div>
            <div class="col-md-1"></div>
            <div class="col-md-3">
                <div class="card bg-light mt-2">
                    <form action="{{ route('importMediakey') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @include('contracargos.admin.import_rep')
                        </form>
                    </div>
                </div>
            </div>
    @include('contracargos.admin.table_results')
    {{$cards->render()}}
        @elseif($var==2)
            <div class="col-md-1"></div>
            <div class="col-md-2">
                <form method="POST" action="{{ route('mediakey.index2') }}">
                    @csrf
                    @include('contracargos.user.input_data')
                </form>
            </div>
            <div class="col-md-3"></div>
        @endif
        <div class="col-md-2"></div>

    </div>
    @include('contracargos.admin.table_results')
    {{$cards->render()}}

@endsection
