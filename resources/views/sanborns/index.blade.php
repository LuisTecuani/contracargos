@extends('layouts.app')
@section('title',"Sanborns")
@section('content')



            <div class="row">
                <div class="col-md-2 bg-light mt-2"></div>
                <div class="col-md-2 mt-2">
                    <h1><b>Sanborns</b></h1>
                </div>
            </div>
            <div class="container">
                <div class="card bg-light mt-3">
                    <div class="card-header">
                        Add file to bonificacion_sanborns table
                    </div>
                    <div class="card-body">
                        <form action="/sanborns/store" method="POST" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="file" multiple class="form-control">
                            <br>
                            <button class="btn btn-success">Add file</button>
                        </form>
                    </div>
                </div>
            </div>
@endsection
