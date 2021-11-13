@extends('layouts.layout')

@section('title')
    Dashboard
@endsection

@section('content')
    <div class="container-lg">
        <h1 class="mb-4">Gestion du compte de {{ $user->firstname }} {{ $user->lastname }}</h1>

        <a class="btn btn-secondary my-4" href="{{ route('user-list') }}">Revenir à la liste utilisateurs</a>

        <div class="mt-4">
            @if ($user->picture == null)
                <img src="{{ asset('icones/unkown-user.svg') }}" class="rounded-circle" alt="Image Utilisateur" width="100" height="100" />
            @else
                @php
                    $file = $user->picture;

                    if(!filter_var($user->picture, FILTER_VALIDATE_URL)) $file = "storage/" . $file;
                @endphp

                <img src="{{ asset($file) }}" class="rounded-circle" alt="Image Utilisateur" width="100" height="100" />
            @endif
        </div>

        <form method="post" action="{{ route('user-delete-image', $user->id) }}" class="mt-3 ms-1">
            @csrf
            @method('PUT')

            <button type="submit" class="btn btn-danger btn-sm">Supprimer l'image</button>
        </form>

        <div class="row mt-4">
            <h2>Informations</h2>

            @if(!empty(session('informationsHasChanged')))
                <p class="list-group-item list-group-item-success mt-3 ms-3 mb-0">Les informations ont bien été changé.</p>
            @elseif(!empty(session('emailHasChanged')))
                <p class="list-group-item list-group-item-success mt-3 ms-3 mb-0">L'email a bien été changé.</p>
            @elseif(!empty(session('passwordHasChanged')))
                <p class="list-group-item list-group-item-success mt-3 ms-3 mb-0">Le mot de passe a bien été changé.</p>
            @elseif($errors->any())
                <ul class="list-group mt-4 ms-3">
                    @foreach ($errors->all() as $error)
                        <li class="list-group-item list-group-item-danger">{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <div class="col-md">
                <form method="post" action="{{ route('user-update', $user->id) }}" class="mt-3 ms-3">
                    @csrf
                    @method('PUT')

                    <div class="row">
                        <div class="form-group col-md mt-3">
                            <label class="form-label">Nom</label>
                            <input type="text" class="form-control" name="lastname" value="{{ $user->lastname }}" required />
                        </div>

                        <div class="form-group col-md mt-3">
                            <label class="form-label">Prénom</label>
                            <input type="text" class="form-control" name="firstname" value="{{ $user->firstname }}" required />
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary mt-4">Modifier</button>
                </form>

                <form method="post" action="{{ route('user-update-email', $user->id) }}" class="mt-3 ms-3">
                    @csrf
                    @method('PUT')

                    <div class="form-group mt-3">
                        <label class="form-label">Email</label>
                        <input type="text" class="form-control" name="email" value="{{ $user->email }}" required />
                    </div>

                    <button type="submit" class="btn btn-primary mt-4">Modifier</button>
                </form>
            </div>

            <form method="post" action="{{ route('user-update-password', $user->id) }}" class="col-md mt-3 ms-3">
                @csrf
                @method('PUT')

                <div class="form-group mt-3">
                    <label class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" name="password" required />
                </div>

                <div class="form-group mt-3">
                    <label class="form-label">Confirmation mot de passe</label>
                    <input type="password" class="form-control" name="password_confirmation" required />
                </div>

                <button type="submit" class="btn btn-primary mt-4">Modifier</button>
            </form>
        </div>
    </div>
@endsection
