@extends('layouts.layout')

@section('title')
    Liste des Types
@endsection

@section('content')
    @php
        $user = Illuminate\Support\Facades\Auth::user();
    @endphp

    <div class="container-lg">
        <h1>Liste des types</h1>

        @if(!empty(session('typeStored')))
            <p class="list-group-item list-group-item-success mt-4 mb-0">Le type "{{ session('typeStored') }}" a été ajouté.</p>
        @elseif(!empty(session('typeDeleted')))
            <p class="list-group-item list-group-item-success mt-4 mb-0">Le type "{{ session('typeDeleted') }}" a bien été supprimé.</p>
        @elseif($errors->any())
            <ul class="list-group mt-4">
                @foreach ($errors->all() as $error)
                    <li class="list-group-item list-group-item-danger">{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <form method="post" action="{{ route('type-store') }}" class="my-4 col-lg-8">
            @csrf

            <div class="form-group">
                <div class="input-group">
                    <input type="text" class="form-control" name="name" placeholder="Type" required />
                    <button type="submit" class="btn btn-primary">Ajouter</button>
                </div>
            </div>
        </form>

        @if (sizeof($types))
            <ul class="list-group col-lg-8">
                @foreach ($types as $type)
                    @if (!$user->is_admin)
                        <li class="list-group-item">{{ $type->name }}</li>
                    @else
                        <li class="list-group-item d-flex flex-row justify-content-between align-items-center">
                            <span>{{ $type->name }}</span>
                            <form method="post" action="{{ route('type-delete', $type->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                            </form>
                        </li>
                    @endif
                @endforeach
            </ul>
        @else
            <p>Il n'y a pas de types présents.</p>
        @endif
    </div>
@endsection
