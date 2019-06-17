@extends('layouts.app')
@section('title')
    Mediakey
@endsection
@include('inc.navbar')
{{$var=2}}
<body>
<br>
<div class="row">
    <div class="col-md-2 bg-light mt-2"></div>
    <div class="col-md-2">
        <h1><b>Contracargos mediakey</b></h1>
        <div class="alert-primary">Autorización: 6 Digitos</div>
        <div class="alert-primary">Terminación Tarjeta: 4 Digitos</div>
    @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
    @if($var==1)
        <div class="row">
            <div class="col-md-2 mt-1">
                <form method="POST" action="/mediakey">
                    @csrf
                    <div class="form-group">
                        <textarea name="autorizaciones" id="autorizaciones" pattern="\d" title="Username should only contain lowercase letters. e.g. john"
                                  cols="30" rows="10" placeholder="Autorización, terminación tarjeta" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-outline-primary" >Registrar</button>
                </form>
            </div>
        </div>
    <div class="col-md-1"></div>
    <div class="col-md-3">
        <div class="card bg-light mt-2">
            <div class="card-header">
                Importa respuestas de cobranza (REP)
            </div>
            <div class="card-body">
                <form action="{{ route('importMediakey') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" multiple="true" name="files[]" accept=".rep" class="btn btn-secondary btn-lg btn-block">
                    <br>
                    <button class="btn btn-outline-success">Import User Data</button>
                </form>
            </div>
        </div>
    </div>

@elseif($var==2)
        <div class="col-md-2">
                <form method="POST" action="{{ route('index2') }}">
                    @csrf
                    <input class="form-control form-control-lg mt-lg-2" type="text" placeholder="Autorización 6 Digitos" name="autorizacion" id="autorizacion">
                    <input class="form-control form-control-lg mt-lg-2" type="text" placeholder="Terminación Tarjeta 4 Digitos" name="terminacion" id="terminacion">
                    <button type="submit" class="btn btn-outline-primary mt-3">Registrar</button>
                </form>
        </div>
        <div class="col-md-4"></div>
@endif
    <div class="col-md-2"></div>

</div>


<div class="row">
    <div class="col-md-2 bg-light mt-3"></div>
    <div class="col-md-8">
        <table class="table">
            <thead class="thead-dark">
            <tr>
                <th scope="col">User_id</th>
                <th scope="col">Email</th>
                <th scope="col">Autorizacion excel</th>
                <th scope="col">Autorizacion rep</th>
                <th scope="col">Tarjeta excel</th>
                <th scope="col">Tarjeta DB</th>
                <th scope="col">Fecha Rep</th>
                <th scope="col">Fecha Registro</th>

            </tr>
            </thead>
            <tbody>
            @foreach($cards as $c)
                <tr>
                    <td>{{$c->user_id}}</td>
                    @if($c->email == null and $c->aut1 == null)
                        <td><b><a class="text-danger"> Usuario no encontrado en reps</a></b></td>
                        @elseif($c->email== null and $c->aut1 != null)
                            <td><b><a class="text-danger"> Usuario no encontrado en users</a></b></td>
                        @else
                        <td>{{$c->email}}</td>
                    @endif
                    <td>{{$c->aut2}}</td>
                    @if(is_null($c->aut1))
                        <td><b><a class="text-danger">Autorización no encontrada</a></b></td>
                    @else
                        <td>{{$c->aut1}}</td>
                    @endif
                    <td>{{$c->t2}}</td>
                    <td>{{$c->t1}}</td>
                    <td>{{$c->fecha}}</td>
                    <td>{{$c->creacion}}</td>

                </tr>
            @endforeach
            </tbody>
        </table>
         {{$cards->render()}}
    </div>
    <div class="col-md-2"></div>
</div>

</body>
<footer></footer>

