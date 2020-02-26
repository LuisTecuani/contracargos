<div class="row">
    <div class="col">
        <div class="card bg-light mt-2">
            <form method="POST" action="{{ route('binsHistoric.import') }}"
                  enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <div class="card-header">
                        Agrega cobros por medio de .CSV
                        <input type="file" name="file" accept=".csv"
                               class="btn btn-secondary btn-lg btn-block" required>
                    </div>
                </div>
                <button class="btn btn-outline-success">Import Data</button>
            </form>
        </div>
    </div>
</div>
