<table>
    <thead>
    <tr>
        <th>tarjeta</th>
        <th>user_id</th>
        <th>fecha</th>
        <th>autorizacion</th>
        <th>monto</th>
    </tr>
    </thead>
    <tbody>
    @foreach($rep as $row)
        <tr>
            <td>{{ $row[0] }}</td>
            <td>{{ $row[1] }}</td>
            <td>{{ $row[2] }}</td>
            <td>{{ $row[5] }}</td>
            <td>{{ $row[8] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
