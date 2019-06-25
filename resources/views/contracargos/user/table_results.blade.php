<div class="row">
    <div class="col-md-2 bg-light mt-3"></div>
    <div class="col-md-8">
        <table class="table mt-lg-4">
            <thead class="thead-dark">
            <tr>
                <th scope="col">User_id</th>
                <th scope="col">Email</th>
                <th scope="col">Autorizacion</th>
                <th scope="col">Autorizacion rep</th>
                <th scope="col">Tarjeta</th>
                <th scope="col">Tarjeta DB</th>
                <th scope="col">Fecha Rep</th>
                <th scope="col">Fecha Registro</th>
            </tr>
            </thead>
            <tbody>
            @foreach($cards2 as $c2)
                <tr>
                    @if($c2->user_id == null)
                        <td><b><a class="text-danger">NULL</a></b></td>
                    @else
                        <td>{{$c2->user_id}}</td>
                    @endif
                    @if($c2->email == null and $c2->aut1 == null)
                        <td><b><a class="text-danger"> NULL</a></b></td>
                    @elseif($c2->email== null and $c2->aut1 != null)
                        <td><b><a class="text-danger"> Usuario no encontrado en users</a></b></td>
                    @else
                        <td>{{$c2->email}}</td>
                    @endif
                    <td>{{$c2->aut2}}</td>
                    @if(is_null($c2->aut1))
                        <td><b><a class="text-danger">Autorización no encontrada</a></b></td>
                    @else
                        <td>{{$c2->aut1}}</td>
                    @endif
                    <td>{{$c2->t2}}</td>
                    @if($c2->t1 == null)
                        <td><b><a class="text-danger">NULL</a></b></td>
                    @else
                        <td>{{$c2->t1}}</td>
                    @endif
                    @if($c2->fecha == null)
                        <td><b><a class="text-danger">NULL</a></b></td>
                    @else
                        <td>{{$c2->fecha}}</td>
                    @endif
                    <td>{{$c2->creacion}}</td>

                </tr>
            @endforeach
            </tbody>
            {{$cards2->render()}}
        </table>
        {{$cards2->render()}}

    </div>
    <div class="col-md-2"></div>
</div>


