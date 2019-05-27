<nav class="navbar navbar-expand-md navbar-light navbar-laravel">
    <div class="container">
        <a class="navbar-brand" href="{{ url('/') }}">
            {{ config('app.name', 'Laravel') }}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a href="{{route('aliado.index')}}" class="nav-link">Aliado</a>
                </li>
                <li class="nav-item">
                    <a href="{{route('asmas.index')}}" class="nav-link">Asmas</a>
                </li>
                <li class="nav-item">
                    <a href="{{route('cellers.index')}}" class="nav-link">Cellers</a>
                </li>
                <li class="nav-item">
                    <a href="{{route('mediakey.index')}}" class="nav-link">Mediakey</a>
                </li>

            </ul>
            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
            </ul>
        </div>
    </div>
</nav>
