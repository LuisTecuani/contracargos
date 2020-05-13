<div class="pt-4">
    <div class="card bg-light mt-2">
        <form method="POST" action="{{ route('find_user.show') }}"
              enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <div class="card-header">
                    Ingresa user_id o email y la plataforma.
                </div>
            </div>
            <div class="form-group">
                <input type="text" class="form-control" id="procedence" name="procedence"
                       value="para 3918" required>
            </div>
            <button class="btn btn-outline-success">Find Data</button>
        </form>
    </div>
</div>
