@extends('layouts.app')
@section('title',"Bins")
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1><b>BINs</b></h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mt-2">
                @include('bins._navLinks')
            </div>
            <div class="col-md mt-2">
                <h2>Historico de bins aliado</h2>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <table class="table mt-lg-4">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">BIN</th>
                        <th scope="col">Banco</th>
                        <th scope="col">Country</th>
                        <th scope="col">Network</th>
                        <th scope="col">Aprobados</th>
                        <th scope="col">Rechazados</th>
                        <th scope="col">Porcentaje</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($bins as $c)
                        <tr>
                            <td>{{$c->bin}}</td>
                            <td>{{$c->bank}}</td>
                            <td>{{$c->country}}</td>
                            <td>{{$c->network}}</td>
                            <td>{{$c->aproved}}</td>
                            <td>{{$c->rejected}}</td>
                            <td>{{$c->percent}}</td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>


            </div>
        </div>



    </div>

@endsection
