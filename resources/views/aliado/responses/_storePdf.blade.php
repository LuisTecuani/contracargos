<div class="col-md">
    <form method="POST" action="{{ route('aliado.responses.storePdf') }}" enctype="multipart/form-data">
        @csrf
        <div class="card bg-light mt-2">
            <div class="card-header">
                Importa respuestas de cobro de banorte desde (.PDF)
            </div>
            <div class="card-body">
                <input type="file" multiple="true" name="files[]" accept=".pdf"
                       class="btn btn-secondary btn-lg btn-block">
                <br>
                <button class="btn btn-outline-primary">Import Data</button>
            </div>
        </div>
    </form>
</div>
