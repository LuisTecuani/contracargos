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
                @include('tdc_verification._textBoxVerify')
            </div>
        </div>
    </div>

@endsection
