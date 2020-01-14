@extends('layouts.app')
@section('title',"Sanborns")
@section('content')


    <div class="row">
        <div class="col-md-1 bg-light"></div>
        <div class="col-md-5 bg-light"><h1><b>Detalles de cuenta </b></h1></div>
        <div class="col-md-5"></div>
        <div class="col-md-1 bg-light"></div>
    </div>
    <div class="row">
        <div class="col-md-2 bg-light"></div>
        <div class="col-md-8 bg-light">
            @if(isset($details))
                <table class="table mt-lg-4">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">Cuenta</th>
                        <th scope="col">Fecha</th>
                        <th scope="col">Importe</th>
                        <th scope="col">Respuesta</th>
                        <th scope="col">Referencia</th>
                        <th scope="col">Source</th>
                        <th scope="col">Tipo</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($details as $detail)
                        <tr>
                            <td>{{ $detail->cuenta }}</td>
                            <td>{{ $detail->fecha }}</td>
                            <td>{{ $detail->importe }}</td>
                            @if($detail->respuesta == 00)
                                <td class="text-success">{{ $detail->respuesta }}</td>
                            @elseif($detail->respuesta != 00)
                                <td class="text-danger">{{ $detail->respuesta }}</td>
                            @else
                                <td>NULL</td>
                            @endif
                            <td>{{ $detail->referencia }}</td>
                            <td>{{ $detail->source }}</td>
                            @if($detail->tipo == 'Cobro')
                                <td class="text-success">{{ $detail->tipo }}</td>
                            @else
                                <td class="text-danger">{{ $detail->tipo }}</td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
        <div class="col-md-2 bg-light"></div>
    </div>
    <div class="row">
        <div class="col-md-9 bg-light"></div>
        <div class="col-md-2 bg-light">
            <button type="button" class="btn btn-primary btn-lg" >REGRESAR</button>
        </div>
        <div class="col-md-1 bg-light"></div>
    </div>


@endsection


