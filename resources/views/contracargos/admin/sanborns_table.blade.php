<div class="col-md-8 bg-light">
    @if(isset($SearchedData))
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
            @foreach($SearchedData as $result)
                <tr>
                    <td>{{ $result->sanborns_id }}</td>
                    <td>{{ $result->charges->veces_cobrado }}</td>
                    <td>{{ $result->charges->total_cobros }}</td>
                    @if(isset($result->returns->veces_devuelto))
                        <td>{{ $result->returns->veces_devuelto }}</td>
                    @else
                        <td>NULL</td>
                    @endif
                    @if(isset($result->returns->total_devoluciones))
                        <td>{{ $result->returns->total_devoluciones }}</td>
                    @else
                        <td>NULL</td>
                    @endif
                    <td>
                        <form action="{{ route('searchDetails', ['sanborns_id' => $result->sanborns_id]) }}" method="POST">
                            @csrf
                            <button class="btn btn-primary btn-block" type="submit">
                                Ver
                            </button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
</div>
