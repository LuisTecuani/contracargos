@extends('layouts.app')
@section('title',"Users by bank")
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-4 bg-light mt-2">
                <h1><b>Usuarios por banco</b></h1>
                @include('tools._navLinks')
            </div>
            <div class="col-md-6 pt-4">
                <form method="POST" action="{{ route('usersBank.show') }}">
                    @csrf
                    <div class="form-group">
                        <label for="platform">Plataforma</label>
                        <select id="platform" name="platform" class="custom-select custom-select-lg mb-3" required>
                            @foreach($platforms as $platform)
                                <option value="{{ $platform }}">{{ $platform }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="initial_date">Fecha inicial</label>
                        <textarea class="form-control" id="initial_date" name="initial_date" rows="1" placeholder="2021-03% o 2021-11-02"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="final_date">Fecha final</label>
                        <textarea class="form-control" id="final_date" name="final_date" rows="1" placeholder="2021-03% o 2021-11-02"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="bank">Banco</label>
                        <textarea class="form-control" id="bank" name="bank" rows="1"></textarea>
                    </div>
                    <div class="col-auto my-1">
                        <button type="submit" class="btn btn-primary">Buscar</button>
                    </div>
                </form>
            </div>
        </div>

        @if(isset($results))

            <div class="row">
                <div class="col-md-2 bg-light mt-3"></div>
                <div class="col-md-8">
                    <table class="table mt-lg-4">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">Plataforma</th>
                            <th scope="col">Banco</th>
                            <th scope="col">Pasarela</th>
                            <th scope="col">Respuesta</th>
                            <th scope="col">Cantidad</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($results as $row)
                            <tr>
                                <td>{{$row->platform}}</td>
                                <td>{{$row->bank}}</td>
                                <td>{{$row->pasarela}}</td>
                                <td>{{$row->response}}</td>
                                <td>{{$row->amount}}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="col-md-2"></div>
            </div>

        @endif

    </div>

@endsection
