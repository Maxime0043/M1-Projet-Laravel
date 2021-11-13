@extends('layouts.layout')

@section('title')
    Page de Connexion
@endsection

@section('content')
    <div class="container-lg">
        <div class="col-6 d-flex flex-column mx-auto">
            <h1>Connexion</h1>

            <form action="{{ route('login') }}" method="post" class="mt-3">
                @csrf

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" class="form-control" name="email" value="{{ old('email') }}" required/>
                </div>

                <div class="form-group mt-3">
                    <label>Mot de passe</label>
                    <input type="password" class="form-control" name="password" required/>
                </div>

                <button type="submit" class="btn btn-primary mt-4">Se Connecter</button>
            </form>
        </div>
    </div>
@endsection
