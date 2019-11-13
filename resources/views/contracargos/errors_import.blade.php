@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            @foreach ($errors->all() as $error)

                <li> {{ $error }}</li>
            @endforeach

        </ul>
    </div>
@endif
@if(session('message'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <li>{{Session('message')}}</li>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
@if(session('message1'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <li>{{Session('message1')}}</li>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
@endif
