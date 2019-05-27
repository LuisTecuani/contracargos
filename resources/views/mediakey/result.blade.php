<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
<h1>Contracargos mediakey</h1>

<div class="row">
    <div class="col-md-8 col-md-offset-2">
        <form method="POST" action="/mediakey/resut">
            @csrf
            <div class="form-group">
                <textarea name="tarjetas" id="tarjetas" cols="30" rows="10" placeholder="Numeros de cuenta"></textarea>

            </div>

            <button type="submit" class="btn btn-outline-primary">Post</button>

        </form>
        <h3>Users</h3>
        @foreach($cards as $ca)

            <article>
                <h4>{{$ca->user->name}}</h4>
                <h4>{{ $ca->user_id }}</h4>
                <div>{{$ca->number }}</div>
            </article>
        @endforeach
    </div>

</div>




</body>
</html>
