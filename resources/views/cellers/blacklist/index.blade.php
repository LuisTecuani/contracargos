@extends('layouts.app')
@section('title',"Aliado")
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <ul>
                    @foreach($users as $user1)
                        {{$user1}}
                        @foreach($user1 as $user)

                        <li>{{$user->user_id}},{{$user->tarjeta}},{{$user->fecha}}</li>
                        @endforeach
                        @endforeach
                </ul>

            </div>
        </div>
    </div>

@endsection
