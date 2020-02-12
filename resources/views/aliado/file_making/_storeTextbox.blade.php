<div class="row">
    <div class="col">
        <div class="card bg-light mt-2">
            <form method="POST" action="{{ route('aliado.billing_users.storeTextbox') }}"
                  enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <div class="card-header">
                        Ingresa los id de los usuarios a cobrar.
                        <br>
                        <textarea class="w-100" name="ids" id="ids" pattern="\d"
                                  title="Los user_id deben ingresarse uno por fila, sin ningun caracter especial ni espacios."
                                  rows="10" placeholder="user_id" required></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="procedence" name="procedence"
                           placeholder="Ingresa la procedencia de los usuarios" required>
                </div>
                <button class="btn btn-outline-success">Import Data</button>
            </form>
        </div>
    </div>
</div>
