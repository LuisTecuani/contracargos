

@extends('layouts.app')

@section('title')
    Mediakey
@endsection

@section('content')

    <body>
    <h1>Contracargos mediakey</h1>

    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <form method="POST" action="/mediakey">
                @csrf
                <div class="form-group">
                    <textarea name="autorizaciones" id="autorizaciones" cols="30" rows="10" placeholder="Autorizaciones"></textarea>

                </div>

                <button type="submit" class="btn btn-outline-primary">Post</button>

            </form>

        </div>

    </div>

    <div class="container">
        <div class="card bg-light mt-3">
            <div class="card-header">
                Importa usuarios autorizados en los .rep a la tabla repsmediakey
            </div>
            <div class="card-body">
                <form action="{{ route('importMediakey') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="files[]" multiple class="form-control">
                    <br>
                    <button class="btn btn-success">Import User Data</button>
                    <a class="btn btn-warning" href="{{ route('export') }}">Export User Data</a>
                </form>
            </div>
        </div>
    </div>


    </body>

@endsection
