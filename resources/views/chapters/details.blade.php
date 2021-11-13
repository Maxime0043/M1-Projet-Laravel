@extends('layouts.layout')

@section('title')
    Chapitre - {{ $currentChapter->title }}
@endsection

@section('content')
    @php
        $isUser = Illuminate\Support\Facades\Auth::check();
    @endphp

    <div class="container-lg">
        <h1>Chapitre - {{ $currentChapter->title }}</h1>

        <a class="btn btn-secondary my-4" href="{{ route('formation-details', $formation->id) }}">Revenir à la formation</a>

        <div class="accordion col-6" id="accordionExample">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                        Chapitres
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                    <div class="accordion-body p-0">
                        @php
                            $count = 1;
                        @endphp

                        @foreach ($formation->chapters as $chapter)
                            <a href="{{ route('formation-chapter', [$formation->id, $chapter->id]) }}" class="d-flex flex-row justify-content-between align-items-top list-group-item list-group-item-action {{ $currentChapter->id == $chapter->id ? "active" : "" }}">
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
                </div>
            </div>
        </div>

        <div class="col mt-3 mt-4 mb-5">
            <h2>Contenu du Chapitre</h2>

            <div class="ms-4 mt-5">
                @if(!$isUser)
                    {!! $currentChapter->content !!}
                @else
                    @if(!empty(session('informationsHasChanged')))
                        <p class="list-group-item list-group-item-success mb-4">Les information du chapitre ont été mis à jour.</p>
                    @elseif($errors->any())
                        <ul class="list-group mt-2 mb-4">
                            @foreach ($errors->all() as $error)
                                <li class="list-group-item list-group-item-danger">{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif

                    <form method="post" action="{{ route('chapter-update', [$formation->id, $chapter->id]) }}">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="form-group col-lg">
                                <label class="form-label">Titre</label>
                                <input type="text" class="form-control" name="title" value="{{ $chapter->title }}" required/>
                            </div>

                            <div class="form-group col-lg-3">
                                <label class="form-label">Durée</label>

                                <div class="row">
                                    @php
                                        $duration = explode(' ', $chapter->duration)[1];
                                        $duration = explode(':', $duration);
                                        $hours = intval($duration[0]);
                                        $minutes = intval($duration[1]);
                                    @endphp

                                    <div class="input-group col">
                                        <input type="text" class="form-control" name="hours" value="{{ $hours }}" required/>
                                        <label class="input-group-text">h</label>
                                    </div>

                                    <div class="input-group col">
                                        <input type="text" class="form-control" name="minutes" value="{{ $minutes }}" required/>
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
                            <textarea class="form-control tinymce" name="content" required>{!! $currentChapter->content !!}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary mt-4">Modifier</button>
                    </form>

                    <form method="post" action="{{ route('chapter-delete', [$formation->id, $chapter->id]) }}" class="mt-2">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Supprimer le chapitre</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
@endsection

@section('js')
    @include('components.tinymce-config', ['token' => csrf_token()])
@endsection
