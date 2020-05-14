@extends('layouts.app')
@section('title',"Find user")
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1><b>FIND USERS TOOL</b></h1>
            </div>
        </div>
        <div class="row">
            <div class="col">
                @include('find_user._textBoxFindUser')
            </div>
        </div>
        <div class="row">
            <div class="col">
                <table class="table mt-lg-4">
                    <tbody>
                    <tr>
                        <td><b>User_id</b></td>
                        <td>{{$user->id}}</td>
                    </tr>
                    <tr>
                        <td><b>Email</b></td>
                        <td>{{$user->email}}</td>
                    </tr>
                    <tr>
                        <td><b>Nombre</b></td>
                        <td>{{$user->name}}</td>
                    </tr>
                    <tr>
                        <td><b>Created_at</b></td>
                        <td>{{$user->created_at}}</td>
                    </tr>
                    <tr>
                        <td><b>Cancelled_at</b></td>
                        <td>{{$user->cancelled_at}}</td>
                    </tr>
                        @foreach($charges as $row)
                    <tr>
                            <td><b>Cargo</b></td>
                            <td>{{$row->fecha}}</td>
                            <td>{{$row->tarjeta}}</td>
                    <tr>
                    @endforeach
                        <td><b>Numero de cargos</b></td>
                        <td>{{count($charges)}}</td>
                    </tr>
                    </tbody>

                </table>

            </div>
        </div>
    </div>

@endsection
