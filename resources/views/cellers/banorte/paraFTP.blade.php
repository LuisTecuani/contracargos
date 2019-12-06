@extends('layouts.app')
@section('title',"Cellers")
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4 bg-light mt-2">
                <h1><b>Cobro cellers</b></h1>
                @include('cellers.banorte._navLinks')
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <h3>Usuarios a cobrar en FTP</h3>

                @foreach($verified as $row)
                    <h6>'{{$row->id}}',</h6>
                @endforeach
            </div>

        </div>
    </div>

@endsection
