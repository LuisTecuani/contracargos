@extends('layouts.app')
@section('title',"Aliado")
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4 bg-light mt-2">
                <h1><b>Aliado banorte results</b></h1>
            @include('aliado._navLinks')
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <h3>Usuarios a cobrar</h3>
                @foreach($users as $user)

                    <h6>'{{$user}}',</h6>

                @endforeach
                <h6>Total {{count($users)}}</h6>

            </div>

        </div>
    </div>

@endsection
