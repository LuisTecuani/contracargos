@extends('layouts.app')
@section('title',"TDC verification")
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1><b>Tdc verification TOOL</b></h1>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <h3>
                Tarjetas validas
                </h3>
                @foreach($valid as $row)
                    <h5>
{{$row}}
                    </h5>
                @endforeach
            </div>
            <div class="col">
                <h3>
                    Tarjetas  no validas
                </h3>
                @foreach($invalid as $row)
                    <h5>
                        {{$row}}
                    </h5>
                @endforeach
            </div>
        </div>
    </div>

@endsection
