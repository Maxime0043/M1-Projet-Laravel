@extends('layouts.layout')

@section('title')
    Page d'Inscription
@endsection

@section('content')
    <div class="container-lg">
        <div class="col-6 d-flex flex-column mx-auto">
            <h1>Demande d'inscription</h1>

            <div>
                <p class="mt-4">Ce formulaire vous permet d'envoyer une demande d'inscription aux administrateurs de la plateforme.</p>
                <p>Si votre demande a été accepté, vous recevrez par mail les différentes informations necéssaires pour vous connecter.</p>
            </div>

            @if(isset($requestSent))
                <p class="list-group-item list-group-item-success mt-3 mb-0">Votre demande d'inscription a bien été prise en compte.</p>
            @elseif ($errors->any())
                <ul class="list-group mt-3">
                    @foreach ($errors->all() as $error)
                        <li class="list-group-item list-group-item-danger">{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <form action="{{ route('register') }}" method="post" class="mt-3">
                @csrf

                <div class="form-group mt-3">
                    <label>Email</label>
                    <input type="email" class="form-control" name="email" value="{{ old('email') }}" required/>
                </div>

                <div class="row">
                    <div class="col form-group mt-3">
                        <label>Nom</label>
                        <input type="text" class="form-control" name="lastname" value="{{ old('lastname') }}" required/>
                    </div>

                    <div class="col form-group mt-3">
                        <label>Prénom</label>
                        <input type="text" class="form-control" name="firstname" value="{{ old('firstname') }}" required/>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary mt-4">Envoyer la demande</button>
            </form>
        </div>
    </div>
@endsection
