<div class="row">
    <div class="col">
        <div class="card bg-light mt-2">
            <form method="POST" action="{{ route('aliado.billing_users.storeFtp') }}"
                  enctype="multipart/form-data">
                @csrf
                <div class="form-group">
                    <div class="card-header">
                        Obtiene los usuarios a cobrar de un archivo .FTP
                        <input type="file" name="file" accept=".ftp"
                               class="btn btn-secondary btn-lg btn-block" required>
                    </div>
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" id="procedence" name="procedence"
                           value="dashboard" required>
                </div>
                <button class="btn btn-outline-success">Import Data</button>
            </form>
        </div>
    </div>
</div>
