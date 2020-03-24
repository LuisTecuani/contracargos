<div class="row">
    <div class="col-md-2 bg-light mt-3"></div>
    <div class="col-md-8">
        <table class="table mt-lg-4">
            <thead class="thead-dark">
            <tr>
                <th scope="col">Email</th>
                <th scope="col">Fecha Contracargo</th>
                <th scope="col">Fecha Consumo</th>
                <th scope="col">Tarjeta</th>
                <th scope="col">Autorizacion</th>
                <th scope="col">User_id</th>

            </tr>
            </thead>
            <tbody>
            @foreach($cards as $c)
                <tr>
                    @if($c->email == null and $c->user_id == null)
                        <td><b><a class="text-danger"> NULL</a></b></td>
                    @elseif($c->email== null and $c->user_id != null)
                        <td><b><a class="text-danger"> Usuario no encontrado en users</a></b></td>
                    @else
                        <td>{{$c->email}}</td>
                    @endif
                    @if($c->fecha_contracargo == null)
                        <td><b><a class="text-danger">Fecha no encontrada</a></b></td>
                    @else
                        <td>{{$c->fecha_contracargo}}</td>
                    @endif
                        @if($c->fecha_consumo == null)
                            <td><b><a class="text-danger">Atorizacion no encontrada</a></b></td>
                        @else
                            <td>{{$c->fecha_consumo}}</td>
                        @endif
                        <td>{{$c->tarjeta}}</td>
                        <td>{{$c->autorizacion}}</td>
                    @if($c->user_id == null)
                        <td><b><a class="text-danger">NULL</a></b></td>
                    @else
                        <td>{{$c->user_id}}</td>
                    @endif

                </tr>
            @endforeach
            </tbody>

        </table>


    </div>
    <div class="col-md-2"></div>
</div>


