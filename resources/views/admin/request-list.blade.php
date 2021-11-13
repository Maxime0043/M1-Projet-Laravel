@extends('layouts.layout')

@section('title')
    Dashboard
@endsection

@section('content')
    <div class="container-lg">
        <h1 class="mb-4">Demandes d'Inscription</h1>

        @if(!empty(session('userAdded')))
            <p class="list-group-item list-group-item-success mb-4">L'utilisateur {{ session('userAdded')['firstname'] }} {{ session('userAdded')['lastname'] }} a été créé.</p>
        @elseif(!empty(session('userDeleted')))
            <p class="list-group-item list-group-item-success mb-4">La demande d'inscription de {{ session('userDeleted')['firstname'] }} {{ session('userDeleted')['lastname'] }} a été supprimée.</p>
        @endif

        @if (sizeof($signup_requests) > 0)
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th scope="col-md">Email</th>
                            <th scope="col-md">Nom</th>
                            <th scope="col-md">Prénom</th>
                            <th scope="col-md">Date de la demande</th>
                            <th scope="col-md">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($signup_requests as $request)
                        <tr>
                            <td scope="row">{{ $request->email }}</td>
                            <td>{{ $request->lastname }}</td>
                            <td>{{ $request->firstname }}</td>
                            <td>{{ $request->created_at }}</td>
                            <td class="d-flex flex-row">
                                <form method="post" action="{{ route('accept-registration', $request->id) }}">
                                    @csrf
                                    <button class="btn btn-success btn-sm">Accepter</button>
                                </form>
                                <form method="post" action="{{ route('delete-registration', $request->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger btn-sm">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <p>Aucune requête d'inscription présente actuellement.</p>
        @endif
    </div>
@endsection
