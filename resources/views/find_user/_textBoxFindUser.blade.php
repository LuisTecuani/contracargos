<div class="pt-4">
    <div class="card bg-light mt-2">
        <form method="POST" action="{{ route('find_user.show') }}"
              enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <div class="card-header">
                    Ingresa user_id o email y la plataforma.
                </div>
                <label for="email">Escribe email o user_id</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="name@example.com">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" id="user_id" name="user_id" placeholder="122334">
            </div>
            <div class="form-group">
                <label for="platform">Selecciona una plataforma</label>
                <select class="form-control" id="platform" name="platform" required>
                    <option>-SEECCIONA UNO-</option>
                    <option>aliado</option>
                    <option>cellers</option>
                    <option>mediakey</option>
                </select>
            </div>
            <button class="btn btn-outline-success">Find Data</button>
        </form>
    </div>
</div>
