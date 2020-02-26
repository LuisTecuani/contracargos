<div class="col-md">
    <form method="POST" action="{{ route('aliadoChargeback.storeImage') }}" enctype="multipart/form-data">
        @csrf
        <div class="card bg-light mt-2">
            <div class="card-header">
                Importa texto de imagenes de contracargos.
            </div>
            <div class="card-body">
                <input type="file" multiple="true" name="files[]"
                       class="btn btn-secondary btn-lg btn-block">
                <br>
                <button class="btn btn-outline-primary">Selecciona imagenes</button>
            </div>
        </div>
    </form>
</div>
