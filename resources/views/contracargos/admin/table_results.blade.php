<div class="row">
    <div class="col-md-2 bg-light mt-3"></div>
    <div class="col-md-8">
        <table class="table mt-lg-4">
            <thead class="thead-dark">
            <tr>
                <th scope="col">User_id</th>
                <th scope="col">Email</th>
                <th scope="col">Autorizacion</th>
                <th scope="col">Tarjeta</th>
                <th scope="col">Fecha Rep</th>
                <th scope="col">Fecha Registro</th>

            </tr>
            </thead>
            <tbody>
            @foreach($cards as $c)
                <tr>
                    @if($c->user_id == null)
                        <td><b><a class="text-danger">NULL</a></b></td>
                    @else
                        <td>{{$c->user_id}}</td>
                    @endif
                    @if($c->email == null and $c->user_id == null)
                        <td><b><a class="text-danger"> NULL</a></b></td>
                    @elseif($c->email== null and $c->user_id != null)
                        <td><b><a class="text-danger"> Usuario no encontrado en users</a></b></td>
                    @else
                        <td>{{$c->email}}</td>
                    @endif
                        <td>{{$c->autorizacion}}</td>
                        <td>{{$c->tarjeta}}</td>
                    @if($c->fecha_rep == null)
                        <td><b><a class="text-danger">Atorizacion no encontrada</a></b></td>
                    @else
                        <td>{{$c->fecha_rep}}</td>
                    @endif
                    <td>{{$c->created_at}}</td>

                </tr>
            @endforeach
            </tbody>

        </table>


    </div>
    <div class="col-md-2"></div>
</div>


