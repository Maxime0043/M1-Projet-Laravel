@extends('layouts.layout')

@section('title')
    Liste des Catégories
@endsection

@section('content')
    @php
        $user = Illuminate\Support\Facades\Auth::user();
    @endphp

    <div class="container-lg">
        <h1>Liste des catégories</h1>

        @if(!empty(session('categoryStored')))
            <p class="list-group-item list-group-item-success mt-4 mb-0">La catégorie "{{ session('categoryStored') }}" a été ajouté.</p>
        @elseif(!empty(session('categoryDeleted')))
            <p class="list-group-item list-group-item-success mt-4 mb-0">La catégorie "{{ session('categoryDeleted') }}" a bien été supprimé.</p>
        @elseif($errors->any())
            <ul class="list-group mt-4">
                @foreach ($errors->all() as $error)
                    <li class="list-group-item list-group-item-danger">{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form method="post" action="{{ route('category-store') }}" class="my-4 col-lg-8">
            @csrf

            <div class="form-group">
                <div class="input-group">
                    <input type="text" class="form-control" name="name" placeholder="Catégorie" required />
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </div>
            </div>
        </form>

        @if (sizeof($categories))
            <ul class="list-group col-lg-8">
                @foreach ($categories as $category)
                    @if (!$user->is_admin)
                        <li class="list-group-item">{{ $category->name }}</li>
                    @else
                        <li class="list-group-item d-flex flex-row justify-content-between align-items-center">
                            <span>{{ $category->name }}</span>
                            <form method="post" action="{{ route('category-delete', $category->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                            </form>
                        </li>
                    @endif
                @endforeach
            </ul>
        @else
            <p>Il n'y a pas de catégories présents.</p>
        @endif
    </div>
@endsection
