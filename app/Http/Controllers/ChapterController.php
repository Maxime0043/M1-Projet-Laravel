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
    public function index($formation, $chapter)
    {
        $formation = Formation::find($formation);
        if (!$formation)
            return redirect()->route('formations-list');

        $currentChapter = Chapter::find($chapter);
        if (!$currentChapter)
            return view('formations.details', compact('formation'));

        return view('chapters.details', compact(['formation', 'currentChapter']));
    }

    public function add($id)
    {
        if (auth()->user()->is_admin)
            return redirect()->route('formation-details', $id);

        $formation = Formation::find($id);

        return view('chapters.add_chapter', compact('formation'));
    }

    public function store($id, ChapterStoreRequest $request)
    {
        if (auth()->user()->is_admin)
            return redirect()->route('formation-details', $id);

        $params = $request->validated();

        $hours = (intval($params['hours']) < 10 ? '0' : '') . $params['hours'];
        $minutes = (intval($params['minutes']) < 10 ? '0' : '') . $params['minutes'];

        $duration = "$hours:$minutes:00";

        Chapter::create([
            'title'     => $params['title'],
            'duration'  => $duration,
            'content'   => '<html>' . $params['content'] . '</html>',
            'formation' => $id,
        ]);

        return redirect()->route('formation-details', $id);
    }

    public function update($formation, $chapter, ChapterStoreRequest $request)
    {
        $params = $request->validated();

        $hours = (intval($params['hours']) < 10 ? '0' : '') . $params['hours'];
        $minutes = (intval($params['minutes']) < 10 ? '0' : '') . $params['minutes'];

        $duration = "$hours:$minutes:00";

        $chapter = Chapter::find($chapter);
        $chapter->update([
            'title'     => $params['title'],
            'duration'  => $duration,
            'content'   => '<html>' . $params['content'] . '</html>',
        ]);

        return back()->with('informationsHasChanged', true);
    }

    public function delete($formation, $chapter)
    {
        $chapter = Chapter::find($chapter);
        $title = $chapter->title;
        $chapter->delete();

        return redirect()->route('formation-details', $formation)->with('chapterDeleted', $title);
    }

    public function uploadImage(Request $request)
    {
        $file = Storage::put('public', $request->file('file'));
        $filename = substr($file, 7);

        return response()->json(['location' => "/storage/$filename"]);
    }
}
