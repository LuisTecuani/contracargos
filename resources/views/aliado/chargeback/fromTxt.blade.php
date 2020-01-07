@extends('layouts.app')
@section('title',"Aliado")
@section('content')
    <div class="row">
        <div class="col">
            <h3>
                Autorizacion
            </h3>
            @foreach($chargebacks[0] as $row)
                <h6>
                    {{$row}}
                </h6>
            @endforeach
        </div>
        <div class="col">
            <h3>
                Tarjeta
            </h3>
            @foreach($chargebacks[1] as $row)
                <h6>
                    {{$row}}
                </h6>
            @endforeach
        </div>
        <div class="col">
            <h3>
                Fecha del cargo
            </h3>
            @foreach($chargebacks[2] as $row)
                <h6>
                    {{$row}}
                </h6>
            @endforeach
        </div>
    </div>
@endsection
