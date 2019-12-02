@extends('layouts.app')
@section('title',"Sanborns")
@section('content')

    <div class="row">
        <div class="col-md-1 bg-light"></div>
        <div class="col-md-5 bg-light"><h1><b>Sanborns</b></h1></div>
        <div class="col-md-5"></div>
        <div class="col-md-1 bg-light"></div>
    </div>
    <div class="row">
        <div class="col-md-1 bg-light"></div>
        <div class="col-md-5 mt-1">
            <h3><b>Cobros Y Devoluciones</b></h3>
            <div class="card bg-light mt-1">
                <div class="card-body">
                    <form action="{{ route('sanbornsStoreChargesReturnsImport') }}" method="POST"
                          enctype="multipart/form-data">
                        @include('contracargos.admin.import_sanborns')
                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-1 bg-light"></div>
        <div class="col-md-2 mt-1">
            <h3>Ingrese usuario's</h3>
            <form method="POST" action="{{ route('sanbornsSearch') }}">
                <div class="form-group">
                    @csrf
                    <textarea name="sanborns_id" id="sanborns_id" cols="15" rows="10" placeholder="Ingrese Sanborns_id a buscar"></textarea>
                    <button type="submit" class="btn btn-outline-primary mt-lg-3">Consultar</button>
                </div>
            </form>
        </div>
        <div class="col-md-2 bg-light">@include('contracargos.errors_import')</div>
        <div class="col-md-1 bg-light"></div>
    </div>
        <div class="row">
        <div class="col-md-2 bg-light"></div>
        <div class="col-md-8 bg-light">
            @if(isset($result[0]))
            <table class="table mt-lg-4">
            <thead class="thead-dark">
            <tr>
                <th scope="col">Sanborns_id</th>
                <th scope="col">Total Cobrado</th>
                <th scope="col">Importe Cobros</th>
                <th scope="col">Total Devuelto</th>
                <th scope="col">Importe Devoluciones</th>
                <th scope="col">Detalles cuenta</th>

            </tr>
            </thead>
            <tbody>
            @foreach($result as $result)
            <tr>
                <td>{{ $result[0]['cuenta'] }}</td>
                <td>{{ $result[0]['veces_cobrado'] }}</td>
                <td>{{ $result[0]['total_cobros'] }}</td>
                @if($result[0]['total']['veces_devuelto'] == NULL)
                    <td>NULL</td>
                @else
                    <td>{{ $result[0]['total']['veces_devuelto'] }}</td>
                @endif
                @if($result[0]['total']['total_devoluciones'] == NULL)
                    <td>NULL</td>
                @else
                    <td>{{ $result[0]['total']['total_devoluciones'] }}</td>
                @endif
                <td>
                    <button type="button" class="btn btn-outline-success">      Consultar
                    </button>
                </td>
                </tr>
            @endforeach
        </tbody>
        </table>
            @endif
        </div>
        <div class="col-md-2 bg-light"></div>            
        </div>


@endsection
