@extends('layouts.app')
@section('title',"Bins")
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1><b>BINs HISTORIC</b></h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mt-2">
                @include('bins._navLinks')
            </div>
            <div class="col-md mt-2">
                @include('bins.historic._import')
            </div>
        </div>
        <div class="row">
            <div class="col">
                <table class="table mt-lg-4">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">BIN</th>
                        <th scope="col">Banco</th>
                        <th scope="col">Network</th>
                        <th scope="col">Country</th>
                        <th scope="col">Cobros Aceptados</th>
                        <th scope="col">Cobros Rechazados</th>
                        <th scope="col">Cobros Totales</th>
                        <th scope="col">Porcentaje rechazo</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($bins as $c)
                        <tr>
                            <td>{{$c->bin}}</td>
                            <td>{{$c->bins['bank'] ?? 'EMPTY'}}</td>
                            <td>{{$c->bins['network'] ?? 'EMPTY'}}</td>
                            <td>{{$c->bins['country'] ?? 'EMPTY'}}</td>
                            <td>{{$c->a}}</td>
                            <td>{{$c->r}}</td>
                            <td>{{$c->t}}</td>
                            <td>{{$c->r/($c->t)*100}}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
