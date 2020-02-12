<div class="row">
    <div class="col">
        <div class="col-md mt-2">
            <div class="card-header">
                Ingresa los emails para agregar a la blacklist.
                <br>
                <form method="POST" action="{{ route('aliadoBlacklist.store') }}">
                    @csrf
                    <div class="form-group">
                        <textarea class="w-100" name="emails" id="email" pattern="\d"
                                  title="email debe escribirse sin comas o espacios extra, separados unicamente por el salto de linea"
                                  rows="10" placeholder="Enter emails"
                                  required>
                        </textarea>
                        <button type="submit" class="btn btn-outline-primary">Registrar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
