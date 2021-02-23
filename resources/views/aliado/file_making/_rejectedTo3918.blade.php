<div class="row">
    <div class="col">
        <div class="card bg-light mt-2">
            <form method="POST" action="{{ route('aliado.billing_users.storeTo3918') }}"
                  enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <div class="card-header">
                        Selecciona para ingresar los usuarios rechazados por banorte.
                    </div>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="procedence" name="procedence"
                           value="inbursa" required>
                </div>
                <button class="btn btn-outline-success">Import Data</button>
            </form>
        </div>
    </div>
</div>
