@extends('layouts.layout')

@section('title')
    Dashboard
@endsection

@section('content')
    <div class="container-lg">
        <h1 class="mb-4">Gestion des Utilisateurs</h1>

        @if(!empty(session('userDeleted')))
            <p class="list-group-item list-group-item-success mb-4">L'utilisateur {{ session('userDeleted')['firstname'] }} {{ session('userDeleted')['lastname'] }} a été supprimé.</p>
        @endif

        @if(sizeof($users))
            <div class="accordion col-md" id="accordion">
                @foreach ($users as $user)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button collapsed d-flex flex-row justify-content-between align-items-top" type="button" data-bs-toggle="collapse" data-bs-target="#collapseUser{{ $user->id }}" aria-expanded="false" aria-controls="collapseUser{{ $user->id }}">
                                <span class="col-md-8">{{ $user->firstname }} {{ $user->lastname }}</span>
                                <span class="col-md-3 text-end">{{ sizeof($user->formations) }} Formation(s)</span>
                            </button>
                        </h2>

                        <div id="collapseUser{{ $user->id }}" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordion">
                            <div class="accordion-body p-0">
                                @foreach ($user->formations as $formation)
                                    <a href="{{ route('formation-details', $formation->id) }}" class="d-flex flex-row justify-content-between align-items-top list-group-item list-group-item-action">
                                        <div class="col-md-9 d-flex flex-row align-items-top px-0 py-2">
                                            <span class="d-block text-break text-decoration-none mb-0">{{ $formation->title }}</span>
                                        </div>

                                        <span class="col-md-3 text-end mb-0 px-0 py-2">{{ sizeof($formation->chapters) }} Chapitre(s)</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        <div class="d-flex flex-row mx-4 my-2">
                            <a href="{{ route('user-details', $user->id) }}" class="btn btn-warning btn-sm me-2">Modifier</a>

                            <form method="post" action="{{ route('user-delete', $user->id) }}">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Supprimer</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p>Aucun utilisateur ne s'est inscrit pour le moment.</p>
        @endif
    </div>
@endsection
