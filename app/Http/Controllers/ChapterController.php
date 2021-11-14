<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChapterStoreRequest;
use App\Models\Chapter;
use App\Models\ChapterPicture;
use App\Models\Formation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChapterController extends Controller
{
    /**
     * Permet d'afficher le contenu d'un chapitre d'une formation.
     *
     * @param int $formation
     * @param int $chapter
     * @return void
     */
    public function index($formation, $chapter)
    {
        // On récupère la formation courante, si elle n'existe pas on redirige l'utilisateur sur la page des formations
        $formation = Formation::find($formation);
        if (!$formation)
            return redirect()->route('formations-list');

        // On récupère le chapitre courant, s'il n'existe pas on redirige l'utilisateur sur la page de détail de la formation
        $currentChapter = Chapter::find($chapter);
        if (!$currentChapter)
            return view('formations.details', compact('formation'));

        return view('chapters.details', compact(['formation', 'currentChapter']));
    }

    /**
     * Permet d'afficher le formulaire permettant d'ajouter un nouveau chapitre à la formation.
     *
     * @param int $id
     * @return void
     */
    public function add($id)
    {
        // Si l'utilisateur connecté est un administrateur, on le redirige vers la page de détail de la formation
        if (auth()->user()->is_admin)
            return redirect()->route('formation-details', $id);

        // On récupère la formation courante
        $formation = Formation::find($id);

        return view('chapters.add_chapter', compact('formation'));
    }

    /**
     * Permet d'ajouter un nouveau chapitre à une formation.
     *
     * @param int $id
     * @param ChapterStoreRequest $request
     * @return void
     */
    public function store($id, ChapterStoreRequest $request)
    {
        // Si l'utilisateur connecté est un administrateur, on le redirige vers la page de détail de la formation
        if (auth()->user()->is_admin)
            return redirect()->route('formation-details', $id);

        // On récupère les informations entrées par l'utilisateur
        $params = $request->validated();

        $hours = (intval($params['hours']) < 10 ? '0' : '') . $params['hours'];
        $minutes = (intval($params['minutes']) < 10 ? '0' : '') . $params['minutes'];

        $duration = "$hours:$minutes:00";

        // On crée un nouveau chapitre avec les informations récupérées précédemment
        Chapter::create([
            'title'     => $params['title'],
            'duration'  => $duration,
            'content'   => '<html>' . $params['content'] . '</html>',
            'formation' => $id,
        ]);

        return redirect()->route('formation-details', $id);
    }

    /**
     * Permet de modifier les informations concernant un chapitre d'une formation.
     *
     * @param int $formation
     * @param int $chapter
     * @param ChapterStoreRequest $request
     * @return void
     */
    public function update($formation, $chapter, ChapterStoreRequest $request)
    {
        // On récupère les informations entrées par l'utilisateur
        $params = $request->validated();

        $hours = (intval($params['hours']) < 10 ? '0' : '') . $params['hours'];
        $minutes = (intval($params['minutes']) < 10 ? '0' : '') . $params['minutes'];

        $duration = "$hours:$minutes:00";

        // On récupère le chapitre courant et on lui modifie ses informations dans la base de données
        $chapter = Chapter::find($chapter);
        $chapter->update([
            'title'     => $params['title'],
            'duration'  => $duration,
            'content'   => '<html>' . $params['content'] . '</html>',
        ]);

        return back()->with('informationsHasChanged', true);
    }

    /**
     * Permet de supprimer un chapitre d'une formation.
     *
     * @param int $formation
     * @param int $chapter
     * @return void
     */
    public function delete($formation, $chapter)
    {
        // On récupère le chapitre courant et on le supprime de la base de données
        $chapter = Chapter::find($chapter);
        $title = $chapter->title;
        $chapter->delete();

        return redirect()->route('formation-details', $formation)->with('chapterDeleted', $title);
    }

    /**
     * Permet de sauvegarder une image implémentée dans le contenu d'un chapitre.
     *
     * @param Request $request
     * @return void
     */
    public function uploadImage(Request $request)
    {
        $file = Storage::put('public', $request->file('file'));
        $filename = substr($file, 7);

        return response()->json(['location' => "/storage/$filename"]);
    }
}
