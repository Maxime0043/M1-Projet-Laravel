@extends('layouts.layout')

@section('title')
    Formations
@endsection

@section('content')
    <div class="container-lg">
        @php
            $isUser = Illuminate\Support\Facades\Auth::check();
        @endphp

        @if($isUser)
            <h1>Vos formations</h1>

            <a class="btn btn-primary my-4" href="{{ route('formation-add') }}">Créer une formation</a>
        @else
            <h1>Liste des formations</h1>
        @endif

        @if(!empty(session('formationDeleted')))
            <p class="list-group-item list-group-item-success mb-4">La formation "{{ session('formationDeleted') }}" a été supprimée.</p>
        @endif

        <form method="post" action="{{ route('formations-filter') }}" class="mt-4">
            @csrf
            @method('GET')

            <div class="row d-flex flex-row align-items-start">
                <div class="form-group me-2 mb-3 col-md-3">
                    <input type="text" name="title" class="form-control" @if(!empty($filters)) value="{{ $filters['title'] }}" @endif placeholder="Nom de la formation" />
                </div>

                <div class="form-group me-2 mb-3 col-md-3">
                    <input type="text" name="types" class="form-control" @if(!empty($filters)) value="{{ $filters['types'] }}" @endif placeholder="Types" />
                    <div class="form-text">
                        Exemple: débutant,M1,...
                    </div>
                </div>

                <div class="form-group me-2 mb-3 col-md-3">
                    <input type="text" name="categories" class="form-control" @if(!empty($filters)) value="{{ $filters['categories'] }}" @endif placeholder="Catégories" />
                    <div class="form-text">
                        Exemple: HTML,Laravel,...
                    </div>
                </div>

                <div class="form-group col-md">
                    <button type="submit" class="btn btn-primary">Rechercher</button>
                </div>
            </div>
        </form>

        <a href="{{ route('formations-list') }}" class="btn btn-danger">Supprimer le filtre</a>

        @if(sizeof($formations) > 0)

            <div class="row mt-5">
                @foreach($formations as $formation)
                    @if($isUser || (!$isUser && sizeof($formation->chapters)))
                        <div class="col-md-4 d-flex align-items-stretch mb-4">
                            <div class="card w-100">
                                @php
                                    $file = $formation->picture;

                                    if(!filter_var($formation->picture, FILTER_VALIDATE_URL)) $file = "storage/" . $file;
                                @endphp

                                <img class="card-img-top"
                                    src="{{ asset($file) }}"
                                    alt="Card image cap"
                                    style="object-fit: cover"
                                    height='200'
                                />

                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title">{{ $formation->title }}</h5>

                                    <p>{{ $formation->price }}€</p>
                                    <p>Créée par {{ $formation->user->firstname }} {{ $formation->user->lastname }}</p>

                                    @if (sizeof($formation->types) > 0)
                                        <div class="d-flex flex-row align-items-baseline mb-3">
                                            <small>Type(s)</small>
                                            <div class="d-flex flex-row flex-wrap ms-2">
                                                @foreach ($formation->types as $type)
                                                    <span class="badge bg-secondary me-1 mb-1">{{ $type->name }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    @if (sizeof($formation->categories) > 0)
                                        <div class="d-flex flex-row align-items-baseline mb-3">
                                            <small>Catégorie(s)</small>
                                            <div class="d-flex flex-row flex-wrap ms-2">
                                                @foreach ($formation->categories as $category)
                                                    <span class="badge bg-secondary me-1 mb-1">{{ $category->name }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    <div class="d-flex justify-content-end mt-auto">
                                        <a class="stretched-link btn btn-warning btn-sm" href="{{ route('formation-details', $formation->id) }}">{{ $isUser ? "Modifier" : "Voir" }}</a>
                                        @if ($isUser)
                                            <form method="post" action="{{ route('formation-delete', $formation->id) }}" style="z-index: 1;">
                                                @csrf
                                                @method('DELETE')
                                                <button class="btn btn-danger btn-sm ms-1">Supprimer</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                @endforeach
            </div>
        @else
            @if (empty($isFiltered))
                @if($isUser)
                    <p>Vous n'avez pas encore créé de formations.</p>
                @else
                    <p>Il n'y a aucune formation pour le moment.</p>
                @endif
            @else
                <p>La recherche effectuée n'a renvoyé aucun résultat.</p>
            @endif
        @endif
    </div>
@endsection
