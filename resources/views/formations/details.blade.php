@extends('layouts.layout')

@section('title')
    Détail de la Formation
@endsection

@section('content')
    @php
        $isUser = Illuminate\Support\Facades\Auth::check();
        $admin = false;

        if($isUser) {
            if(Illuminate\Support\Facades\Auth::user()->is_admin) {
                $admin = Illuminate\Support\Facades\Auth::user();
            }
        }
    @endphp

    <div class="container-lg">
        <h1>{{ $formation->title }}</h1>

        @if($admin)
            <a class="btn btn-secondary my-4" href="{{ route('user-list') }}">Revenir à la gestion des utilisateurs</a>
        @else
            <a class="btn btn-secondary my-4" href="{{ route('formations-list') }}">Revenir aux formations</a>
        @endif


        @if (!$isUser)
            <div>
                <p><strong>Prix:</strong> {{ $formation->price }} euros</p>

                @if (sizeof($formation->types) > 0)
                    <div class="d-flex flex-row align-items-baseline {{ sizeof($formation->categories) > 0 ? "mb-1" : "" }}">
                        <p class="me-2"><strong>Type(s):</strong></p>
                        <div class="d-flex flex-row flex-wrap">
                            @foreach ($formation->types as $type)
                            <span class="badge bg-primary me-1 mb-1">{{ $type->name }}</span>
                            @endforeach
                        </div>
                    </div>
                @else
                    <p><strong>Type(s):</strong> Aucun type présent.</p>
                @endif

                @if (sizeof($formation->categories) > 0)
                    <div class="d-flex flex-row align-items-baseline">
                        <p class="me-2"><strong>Catégorie(s):</strong></p>
                        <div class="d-flex flex-row flex-wrap">
                            @foreach ($formation->categories as $type)
                                <span class="badge bg-primary me-1 mb-1">{{ $type->name }}</span>
                            @endforeach
                        </div>
                    </div>
                @else
                    <p><strong>Catégorie(s):</strong> Aucune catégorie présente.</p>
                @endif
            </div>

            <div class="row">
                <div class="col-md mt-3">
                    <h2>Présentation</h2>

                    <p class="mt-3">{{ $formation->description }}</p>
                </div>

                <div class="col-md mt-4 mb-5">
                    <h2 class="mb-3">Chapitres</h2>

                    @php
                        $count = 1;
                    @endphp

                    @if (sizeof($formation->chapters))
                        <div class="list-group w-80">
                            @foreach ($formation->chapters as $chapter)
                                <a href="{{ route('formation-chapter', [$formation->id, $chapter->id]) }}" class="row d-flex flex-row justify-content-between align-items-top list-group-item list-group-item-action">
                                    <div class="col-9 d-flex flex-row align-items-top px-0 py-2">
                                        <span class="me-2"><strong>{{ $count }}</strong>.</span>
                                        <span class="d-block text-break text-decoration-none mb-0">{{ $chapter->title }}</span>
                                    </div>

                                    <span class="col-3 text-end mb-0 px-0 py-2">{{ $chapter->formatedDuration() }}</span>
                                </a>

                                @php
                                    $count++;
                                @endphp
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @else
            <form method="post" action="{{ route('formation-delete', $formation->id) }}" class="mb-3">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">Supprimer la formation</button>
            </form>

            <div class="row">
                <h2>Présentation</h2>

                @if(!empty(session('informationsHasChanged')))
                    <p class="list-group-item list-group-item-success mt-3 mb-2">Les information de la formation ont été mis à jour.</p>
                @elseif(!empty(session('pictureHasChanged')))
                    <p class="list-group-item list-group-item-success mt-3 mb-2">L'image de couverture de la formation a été mis à jour.</p>
                @elseif(!empty(session('chapterDeleted')))
                    <p class="list-group-item list-group-item-success mt-3 mb-2">Le chapitre "{{ session('chapterDeleted') }}" a été supprimé.</p>
                @elseif($errors->any())
                    <ul class="list-group mt-2 mb-4">
                        @foreach ($errors->all() as $error)
                            <li class="list-group-item list-group-item-danger">{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                <div class="col-md-6 my-4">
                    <form action="{{ route('formation-update', $formation->id) }}" method="post">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label class="form-label">Titre de la formation</label>
                            <input type="text" class="form-control" name="title" value="{{ $formation->title }}" required/>
                        </div>

                        <div class="form-group mt-3 col-6">
                            <label class="form-label">Prix</label>
                            <div class="input-group">
                                <input type="text" class="form-control" name="price" value="{{ $formation->price }}" required/>
                                <span class="input-group-text">€</span>
                            </div>
                            <div class="form-text">
                                Le prix doit être inférieur à 1 000.00€.
                            </div>
                            <div class="form-text">
                                Exemples: 25.95 ou 25,95 ou 25 €.
                            </div>
                        </div>

                        <div class="form-group mt-3">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" required>{{ $formation->description }}</textarea>
                        </div>

                        <div class="form-group mt-3">
                            <div class="form-group mt-3">
                                <label class="form-label">Types</label>

                                <div>
                                    @foreach($types as $type)
                                        <div class="form-check form-check-inline">
                                            <input type="checkbox" class="form-check-input" id="check-type-{{ $type->id }}" name="checkboxTypes[{{ $type->id }}]" value="{{ $type->id }}" @if($formation->types->contains('id', $type->id)) checked @endif />
                                            <label for="check-type-{{ $type->id }}" class="form-check-label">{{ $type->name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>


                            <div class="form-group mt-3">
                                <label class="form-label">Catégories</label>

                                <div>
                                    @foreach ($categories as $category)
                                        <div class="form-check form-check-inline">
                                            <input type="checkbox" class="form-check-input" id="check-category-{{ $category->id }}" name="checkboxCategories[{{ $category->id }}]" value="{{ $category->id }}" @if($formation->categories->contains('id', $category->id)) checked @endif />
                                            <label for="check-category-{{ $category->id }}" class="form-check-label">{{ $category->name }}</label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary mt-4">Modifier les informations</button>
                    </form>
                </div>

                <div class="col-md-6 my-4">
                    @php
                        $file = $formation->picture;

                        if(!filter_var($formation->picture, FILTER_VALIDATE_URL)) $file = "storage/" . $file;
                    @endphp

                    <img class="card-img-top"
                        src="{{ asset($file) }}"
                        alt="Card image cap"
                        style="object-fit: cover"
                        height="250"
                    />

                    <form action="{{ route('formation-update-picture', $formation->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-group mt-3 col">
                            <label class="form-label">Image de couverture</label>
                            <input type="file" class="form-control" name="picture" accept="image/png, image/jpeg, image/jpg" required/>
                        </div>

                        <button type="submit" class="btn btn-primary mt-4">Modifier l'image de couverture</button>
                    </form>
                </div>
            </div>

            <div class="my-4">
                <h2 class="mb-4">Chapitres</h2>

                @if (!$admin)
                    <a class="btn btn-primary mb-4" href="{{ route('chapter-add', $formation->id) }}">Créer un chapitre</a>
                @endif

                @php
                    $count = 1;
                @endphp

                @if (sizeof($formation->chapters))
                    <div class="list-group w-80 mt-4">
                        @foreach ($formation->chapters as $chapter)
                            <a href="{{ route('formation-chapter', [$formation->id, $chapter->id]) }}" class="row d-flex flex-row justify-content-between align-items-top list-group-item list-group-item-action">
                                <div class="col-9 d-flex flex-row align-items-top px-0 py-2">
                                    <span class="me-2"><strong>{{ $count }}</strong>.</span>
                                    <span class="d-block text-break text-decoration-none mb-0">{{ $chapter->title }}</span>
                                </div>

                                <span class="col-3 d-flex flex-row flex-wrap justify-content-end align-items-center mb-0 px-0 py-2">
                                    <span>{{ $chapter->formatedDuration() }}</span>

                                    <form method="post" action="{{ route('chapter-delete', [$formation->id, $chapter->id]) }}" class="ms-1">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                                    </form>
                                </span>
                            </a>

                            @php
                                $count++;
                            @endphp
                        @endforeach
                    </div>
                @endif
            </div>
        @endif
    </div>
@endsection
