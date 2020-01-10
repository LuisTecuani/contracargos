@extends('layouts.app')
@section('title',"Mediakey")
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1><b>Blacklist Mediakey</b></h1>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4 mt-2">
                @include('mediakey._navLinks')
            </div>
            <div class="col-md mt-2">
                <form method="POST" action="{{ route('mediakeyBlacklist.store') }}">
                    @csrf
                    <div class="form-group">
    <textarea class="w-100" name="emails" id="email" pattern="\d"
              title="email debe escribirse sin comas o espacios extra, separados unicamente por el salto de linea"
              rows="10" placeholder="Enter emails"
              required></textarea>
                        <button type="submit" class="btn btn-outline-primary">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
