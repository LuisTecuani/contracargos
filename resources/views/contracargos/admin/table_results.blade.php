<div class="row">
    <div class="col-md-2 bg-light mt-3"></div>
    <div class="col-md-8">
<table class="table mt-lg-4">
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
    </div>
    <div class="col-md-2"></div>
</div>
