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
                <form method="POST" action="{{ route('bins.store') }}">
                    @csrf
                    <div class="form-group">
                        <textarea class="w-100" name="data" id="data" pattern="\d"
                                  title="se debe ingresar banco, tipo de tarjeta, pais y numero de BIN, separados por comas.
                                   ejm. (American Express,American Express,mexico,370700)"
                                  rows="10" placeholder="Enter BINs"
                                  required></textarea>
                        <button type="submit" class="btn btn-outline-primary">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <table class="table mt-lg-4">
                    <thead class="thead-dark">
                    <tr>
                        <th scope="col">Banco</th>
                        <th scope="col">Network</th>
                        <th scope="col">Country</th>
                        <th scope="col">BIN</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($bins as $c)
                        <tr>
                            <td>{{$c->bank}}</td>
                            <td>{{$c->network}}</td>
                            <td>{{$c->country}}</td>
                            <td>{{$c->bin}}</td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>


            </div>
        </div>



    </div>

@endsection
