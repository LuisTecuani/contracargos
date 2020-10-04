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
                        <td>{{$user->id}}</td>
                    </tr>
                    <tr>
                        <td>{{$user->email}}</td>
                    </tr>
                    <tr>
                        <td>{{$user->name}}</td>
                    </tr>
                    <tr>
                        <td>{{$user->created_at}}</td>
                    </tr>
                    <tr>
                        <td>{{$user->cancelled_at}}</td>
                    </tr>
                        <td>{{count($charges)}}</td>
                    </tr>
                        @foreach($charges as $row)
                            <td>{{$row->fecha}}</td>
                    <tr>
                    @endforeach
                    </tbody>

                </table>

            </div>
        </div>
    </div>

@endsection
