@extends('layouts.layout')

@section('title')
    Dashboard
@endsection

@section('content')
    @php
        $user = Illuminate\Support\Facades\Auth::user();
    @endphp

    <div class="container-lg">
        <h1>Mon Compte</h1>

        <div class="ms-4 mt-5">
            <div>
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

            <div class="mt-4">
                <h2>Mes Informations Personnelles</h2>

                <div class="col-6 ms-4">
                    <div class="row mt-4">
                        <div class="col">
                            <p><strong>Nom</strong></p>
                        </div>
                        <div class="col-8">
                            <p>{{ $user->lastname }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <p><strong>Prénom</strong></p>
                        </div>
                        <div class="col-8">
                            <p>{{ $user->firstname }}</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <p><strong>Email</strong></p>
                        </div>
                        <div class="col-8">
                            <p>{{ $user->email }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6 mt-4">
                <h2>Modifier mes Informations</h2>

                <div class="ms-4">
                    @if(!empty(session('pictureHasChanged')))
                        <p class="list-group-item list-group-item-success mt-3 mb-0">Votre photo de profil a bien été changée.</p>
                    @elseif(!empty(session('passwordHasChanged')))
                        <p class="list-group-item list-group-item-success mt-3 mb-0">Votre mot de passe a bien été changé.</p>
                    @elseif($errors->any())
                        <ul class="list-group mt-4">
                            @foreach ($errors->all() as $error)
                                <li class="list-group-item list-group-item-danger">{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif

                    <form action="{{ route('update-picture') }}" method="post" class="mt-4" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="picture" class="form-label">Photo de profil</label>
                            <input type="file" name="picture" class="form-control" id="picture" accept="image/png, image/jpeg, image/jpg" />
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">Modifier</button>
                    </form>

                    <form action="{{ route('update-password') }}" method="post" class="mt-4">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label class="form-label">Mot de passe</label>
                            <input type="password" class="form-control" name="password" required/>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Confirmation mot de passe</label>
                            <input type="password" class="form-control" name="password_confirmation" required/>
                        </div>

                        <button type="submit" class="btn btn-primary mt-3">Modifier</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
