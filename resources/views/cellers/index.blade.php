
@extends('layouts.app')

@section('title')
    Cellers
@endsection

@section('content')
    <body>
    <h1>Contracargos cellers</h1>

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <form method="POST" action="/cellers">
                @csrf
                <div class="form-group">
                    <textarea name="tarjetas" id="tarjetas" cols="30" rows="10" placeholder="Numeros de cuenta"></textarea>

                </div>

                <button type="submit" class="btn btn-outline-primary">Post</button>

            </form>

        </div>

    </div>




    </body>

@endsection
