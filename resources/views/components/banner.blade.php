<nav class="navbar navbar-expand-md navbar-dark bg-dark mb-4 py-2">
    <div class="container-fluid align-items-baseline">
        <p class="navbar-brand">Sigma Formation</p>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-between w-100" id="navbarNav">
            @php
                $isUser = Illuminate\Support\Facades\Auth::check();
                $isAdmin = false;

                if($isUser)
                    $isAdmin = Illuminate\Support\Facades\Auth::user()->is_admin;
            @endphp

            <ul class="navbar-nav">
                @if ($isAdmin)
                    <li class="nav-item">
                        <a class="nav-link {{ $routeName == "signup-request-list" ? "active" : "" }}" href="{{ route('signup-request-list') }}">Demandes d'Inscription</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ $routeName == "user-list" ? "active" : "" }}" href="{{ route('user-list') }}">Utilisateurs</a>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link {{ $routeName == "formations-list" ? "active" : "" }}" href="{{ route('formations-list') }}">Formations</a>
                    </li>
                @endif
            </ul>

            <ul class="navbar-nav">
                @if ($isUser)
                    <li class="nav-item">
                        <a class="nav-link {{ $routeName == "dashboard" ? "active" : "" }}" href="{{ route('dashboard') }}">Mon compte</a>
                    </li>

                    <li class="nav-item">
                        <form method="post" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="nav-link" style="background-color: transparent; border: none; outline: none;">Se déconnecter</button>
                        </form>
                    </li>
                @else
                    <li class="nav-item">
                        <a class="nav-link {{ $routeName == "login" ? "active" : "" }}" href="{{ route('login') }}">Se connecter</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link {{ $routeName == "register" ? "active" : "" }}" href="{{ route('register') }}">Inscription</a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</nav>




                {{-- <div class="d-flex flex-row justify-content-between w-100">
                    <div class="d-flex flex-row">
                        <li class="nav-item">
                            <a class="nav-link {{ $routeName == "formations-list" ? "active" : "" }}" href="{{ route('formations-list') }}">Formations</a>
                        </li>
                    </div>

                    <div class="d-flex flex-row align-items-center">
                        @if (Illuminate\Support\Facades\Auth::check())
                            <li class="nav-item">
                                <a class="nav-link {{ $routeName == "dashboard" ? "active" : "" }}" href="{{ route('dashboard') }}">Mon compte</a>
                            </li>
                            <li class="nav-item">
                                <form method="post" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="nav-link" style="background-color: transparent; border: none; outline: none;">Se déconnecter</button>
                                </form>
                            </li>
                        @else
                            <li class="nav-item">
                                <a class="nav-link {{ $routeName == "login" ? "active" : "" }}" href="{{ route('login') }}">Se connecter</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $routeName == "register" ? "active" : "" }}" href="{{ route('register') }}">Inscription</a>
                            </li>
                        @endif
                    </div>
                </div> --}}
