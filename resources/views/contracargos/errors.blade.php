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
