<div class="col-md">
    <div class="card bg-light mt-2">
        <form action="{{ route('aliado.responses.storeReps') }}" method="POST" enctype="multipart/form-data">
            @include('contracargos.admin.import_rep')
        </form>
    </div>
</div>
