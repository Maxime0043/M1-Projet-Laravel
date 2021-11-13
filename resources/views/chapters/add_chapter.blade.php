@extends('layouts.layout')

@section('title')
    Création de chapitre
@endsection

@section('content')
    <div class="container-lg">
        <h1>{{ $formation->title }}</h1>

        <a class="btn btn-secondary my-4" href="{{ route('formation-details', $formation->id) }}">Revenir à la formation</a>

        <div class="mt-3">
            <h2>Créer un nouveau chapitre</h2>

            @if($errors->any())
                <ul class="list-group mt-4">
                    @foreach ($errors->all() as $error)
                        <li class="list-group-item list-group-item-danger">{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <form method="post" action="{{ route('chapter-store', $formation->id) }}">
                @csrf

                <div class="row">
                    <div class="form-group col-lg">
                        <label class="form-label">Titre</label>
                        <input type="text" class="form-control" name="title" required/>
                    </div>

                    <div class="form-group col-lg-3">
                        <label class="form-label">Durée</label>

                        <div class="row">
                            <div class="input-group col">
                                <input type="text" class="form-control" name="hours" required/>
                                <label class="input-group-text">h</label>
                            </div>

                            <div class="input-group col">
                                <input type="text" class="form-control" name="minutes" required/>
                                <label class="input-group-text">min</label>
                            </div>
                        </div>

                        <div class="form-text">
                            Exemples: 2h4min, 12h4min, 2h0min, 0h32min.
                        </div>
                    </div>
                </div>

                <div class="form-group mt-3">
                    <label class="form-label">Contenu du chapitre</label>
                    <textarea class="form-control tinymce" name="content" required></textarea>
                </div>

                <button type="submit" class="btn btn-primary mt-4">Créer</button>
            </form>
        </div>
    </div>
@endsection

@section('js')
    @include('components.tinymce-config', ['token' => csrf_token()])
@endsection
