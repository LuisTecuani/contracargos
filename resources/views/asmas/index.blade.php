

@extends('layouts.app')

@section('title')
 Asmas
@endsection

@section('content')
    <body>
    <h1>Contracargos Asmas</h1>

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <form method="POST" action="/asmas">
                @csrf
                <div class="form-group">
                    <textarea name="autorizaciones" id="autorizaciones" cols="30" rows="10" placeholder="Autorizaciones"></textarea>

                </div>

                <button type="submit" class="btn btn-outline-primary">Buscar</button>

            </form>

        </div>

    </div>




    </body>

@endsection
