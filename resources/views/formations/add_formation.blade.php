@extends('layouts.layout')

@section('title')
    Création de formation
@endsection

@section('content')
    <div class="container-lg">
        <h1>Créer une nouvelle formation</h1>

        <a class="btn btn-secondary my-4" href="{{ route('formations-list') }}">Revenir aux formations</a>

        <ul class="list-group mt-4">
            <li class="list-group-item list-group-item-info">Votre formation ne sera visible par les utilisateurs seulement à partir du moment où vous aurez créé au moins 1 chapitre.</li>
        </ul>

        @if($errors->any())
            <ul class="list-group mt-4">
                @foreach ($errors->all() as $error)
                    <li class="list-group-item list-group-item-danger">{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form action="{{ route('formation-store') }}" method="post" enctype="multipart/form-data" class="mt-3">
            @csrf

            <div class="form-group">
                <label class="form-label">Titre de la formation</label>
                <input type="text" class="form-control" name="title" value="{{ old('title') }}" required/>
            </div>

            <div class="row">
                <div class="form-group mt-3 col-4">
                    <label class="form-label">Prix</label>
                    <div class="input-group">
                        <input type="text" class="form-control" name="price" value="{{ old('price') }}" required/>
                        <span class="input-group-text">€</span>
                    </div>
                    <div class="form-text">
                        Le prix doit être inférieur à 1 000.00€.
                    </div>
                    <div class="form-text">
                        Exemples: 25.95 ou 25,95 ou 25 €.
                    </div>
                </div>

                <div class="form-group mt-3 col">
                    <label class="form-label">Image de couverture</label>
                    <input type="file" class="form-control" name="picture" accept="image/png, image/jpeg, image/jpg" required/>
                </div>
            </div>

            <div class="form-group mt-3">
                <label class="form-label">Description</label>
                <textarea class="form-control" name="description" required>{{ old('description') }}</textarea>
            </div>

            <div class="form-group mt-3">
                <div class="form-group mt-3">
                    <label class="form-label">Types</label>

                    <div>
                        @foreach($types as $type)
                            <div class="form-check form-check-inline">
                                <input type="checkbox" class="form-check-input" id="check-type-{{ $type->id }}" name="checkboxTypes[{{ $type->id }}]" value="{{ $type->id }}" />
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
                                <input type="checkbox" class="form-check-input" id="check-category-{{ $category->id }}" name="checkboxCategories[{{ $category->id }}]" value="{{ $category->id }}" />
                                <label for="check-category-{{ $category->id }}" class="form-check-label">{{ $category->name }}</label>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            {{-- <div class="form-group mt-3">
                <label class="form-label">Types</label>
                <div class="d-flex flex-row flex-wrap justify-content-start align-items-center">
                    <select name="selectTypes[]" class="me-2 mb-2">
                        @foreach($types as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="button" id="add-type" class="btn btn-warning btn-sm">Ajouter</button>
            </div>

            <div class="form-group mt-3">
                <label class="form-label">Catégories</label>
                <div class="d-flex flex-row flex-wrap justify-content-start align-items-center">
                    <select name="selectCategories[]" class="me-2 mb-2">
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <button type="button" id="add-category" class="btn btn-warning btn-sm">Ajouter</button>
            </div> --}}

            <button type="submit" class="btn btn-primary mt-4">Ajouter la formation</button>
        </form>
    </div>
@endsection

@section('js')
    <script>
        $("#add-type").on("click", function() {
            const elem = $(this).prev().children("select:first-child").clone();

            elem.children("option").prop("selected", false);
            elem.children("option:first-child").prop("selected", true);

            $(this).prev().append(elem);
        });

        $("#add-category").on("click", function() {
            const elem = $(this).prev().children("select:first-child").clone();

            elem.children("option").prop("selected", false);
            elem.children("option:first-child").prop("selected", true);

            $(this).prev().append(elem);
        });
    </script>
@endsection
