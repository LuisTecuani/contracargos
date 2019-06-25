<h1><b>Contracargos mediakey</b></h1>
<div class="alert-primary">Autorización: 6 Digitos</div>
<div class="alert-primary">Terminación Tarjeta: 4 Digitos</div>
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if(session('message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
    <h3>{{Session('message')}}</h3>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
