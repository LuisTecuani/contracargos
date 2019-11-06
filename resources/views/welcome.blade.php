
@extends('layouts.app')

@section('title')
Contracargos
@endsection

@section('content')
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .top-right {
            position: absolute;
            right: 10px;
            top: 18px;
        }

        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links > a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }
    </style>
    <body>
    <div class="flex-center position-ref full-height">


        <div class="content">
            <div class="title m-b-md">
                Contracargos
            </div>
                @guest

                    <h1>Please log in</h1>
                @else
            <div class="links">
                <a href="/mediakey">Mediakey</a>
                <a href="/cellers">Cellers</a>
                <a href="/sanborns">Sanborns</a>
                <a href="/aliado">Aliado</a>
                <a href="/file_cobro">Generar archivo de cobro</a>

            </div>
                @endguest
        </div>
    </div>
    </body>
@endsection
