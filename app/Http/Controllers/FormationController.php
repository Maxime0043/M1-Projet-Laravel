<?php

namespace App\Http\Controllers;

use App\Http\Requests\FormationFilterRequest;
use App\Http\Requests\FormationStoreRequest;
use App\Http\Requests\FormationUpdatePictureRequest;
use App\Http\Requests\FormationUpdateRequest;
use App\Models\Category;
use App\Models\Chapter;
use App\Models\Formation;
use App\Models\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FormationController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            if (auth()->user()->is_admin) {
                return redirect()->route('user-list');
            }

            $formations = Formation::where('user_id', Auth::user()->id)->get();
        } else {
            $formations = Formation::all();
        }

        return view('formations.list', compact('formations'));
    }

    public function filter(FormationFilterRequest $request)
    {
        $params = $request->validated();

        $isFiltered = true;

        if (sizeof($params) == 0) {
            $formations = Formation::all();
        } else {
            if ($params['title'] == null && $params['types'] == null && $params['categories'] == null) {
                return back();
            }

            $filters = $params;

            if (Auth::check()) {
                if (auth()->user()->is_admin) {
                    return redirect()->route('user-list');
                }

                $formations = Formation::where('user_id', Auth::user()->id)->get();
            } else {
                $formations = Formation::all();
            }

            if ($params['title'] != null) {
                $formationTitles = Formation::where('title', 'like', "%" . $params['title'] . "%")->get();
                $formations = $formations->intersect($formationTitles);
            }

            if ($params['types'] != null) {
                $formationsToAdd = array();
                $types = explode(',', $params['types']);

                foreach ($formations as $formation) {
                    $canMerged = false;

                    foreach ($types as $type) {
                        if ($formation->types->contains('name', trim($type))) {
                            $canMerged = true;
                            break;
                        }
                    }

                    if ($canMerged) {
                        array_push($formationsToAdd, $formation);
                    }
                }

                $formations = $formations->intersect($formationsToAdd);
            }

            if ($params['categories'] != null) {
                $formationsToAdd = array();
                $categories = explode(',', $params['categories']);

                foreach ($formations as $formation) {
                    $canMerged = false;

                    foreach ($categories as $category) {
                        if ($formation->categories->contains('name', trim($category))) {
                            $canMerged = true;
                            break;
                        }
                    }

                    if ($canMerged) {
                        array_push($formationsToAdd, $formation);
                    }
                }

                $formations = $formations->intersect($formationsToAdd);
            }
        }

        if (!empty($filters))
            return view('formations.list', compact(['formations', 'filters', 'isFiltered']));

        return view('formations.list', compact(['formations', 'isFiltered']));
    }

    public function details($id)
    {
        if (Auth::check()) {
            if (!auth()->user()->is_admin) {
                $userFormations = Formation::where('user_id', Auth::user()->id)->get();

                $belongsToUser = false;

                foreach ($userFormations as $userFormation) {
                    if ($userFormation->id == $id) {
                        $belongsToUser = true;
                        break;
                    }
                }

                if (!$belongsToUser)
                    return back();
            }
        }

        $formation = Formation::find($id);

        if (!Auth::check()) {
            if (!sizeof($formation->chapters)) {
                return back();
            }
        }

        if (!$formation)
            return redirect()->route('formations-list');

        $types = Type::all();
        $categories = Category::orderBy('name')->get();

        return view('formations.details', compact(['formation', 'types', 'categories']));
    }

    public function add()
    {
        if (auth()->user()->is_admin)
            return redirect()->route('user-list');

        $types = Type::all();
        $categories = Category::orderBy('name')->get();

        return view('formations.add_formation', compact(['types', 'categories']));
    }

    public function store(FormationStoreRequest $request)
    {
        if (auth()->user()->is_admin)
            return redirect()->route('user-list');

        $params = $request->validated();

        if (empty($params['checkboxTypes']) && empty($params['checkboxCategories'])) {
            return back()
                ->withInput($params)
                ->withErrors(['needTypesOrCategories' => 'Vous devez sélectionner au moins un type ou une catégorie.']);
        }

        if (preg_match('/^[1-9]\d{0,2}$/', $params['price'])) {
            $params['price'] = $params['price'] . '.00';
        }

        $params['price'] = str_replace(',', '.', $params['price']);
        $params['user_id'] = Auth::user()->id;

        $file = Storage::put('public', $params['picture']);
        $params['picture'] = substr($file, 7);

        $formation = Formation::create($params);

        if (!empty($params['checkboxTypes'])) {
            $formation->types()->attach($params['checkboxTypes']);
        }

        if (!empty($params['checkboxCategories'])) {
            $formation->categories()->attach($params['checkboxCategories']);
        }

        return redirect()->route('formation-details', $formation->id);
    }

    public function delete($id)
    {
        $formation = Formation::find($id);
        $title = $formation->title;

        if ($formation->picture != null) {
            if (!filter_var($formation->picture, FILTER_VALIDATE_URL)) {
                if (Storage::exists("public/$formation->picture")) {
                    Storage::delete("public/$formation->picture");
                }
            }
        }

        $formation->chapters()->delete();
        $formation->categories()->detach();
        $formation->types()->detach();

        $formation->delete();

        return redirect()->route('formations-list')->with('formationDeleted', $title);
    }

    public function update($id, FormationUpdateRequest $request)
    {
        $params = $request->validated();

        if (empty($params['checkboxTypes']) && empty($params['checkboxCategories'])) {
            return back()
                ->withInput($params)
                ->withErrors(['needTypesOrCategories' => 'Vous devez sélectionner au moins un type ou une catégorie.']);
        }

        if (preg_match('/^[1-9]\d{0,2}$/', $params['price'])) {
            $params['price'] = $params['price'] . '.00';
        }

        $params['price'] = str_replace(',', '.', $params['price']);

        $formation = Formation::find($id);
        $formation->update($params);

        $formation->types()->detach();
        $formation->categories()->detach();

        if (!empty($params['checkboxTypes'])) {
            $formation->types()->attach($params['checkboxTypes']);
        }

        if (!empty($params['checkboxCategories'])) {
            $formation->categories()->attach($params['checkboxCategories']);
        }

        return back()->with('informationsHasChanged', true);
    }

    public function updatePicture($id, FormationUpdatePictureRequest $request)
    {
        $params = $request->validated();

        $formation = Formation::find($id);

        if ($formation->picture != null) {
            if (!filter_var($formation->picture, FILTER_VALIDATE_URL)) {
                if (Storage::exists("public/$formation->picture")) {
                    Storage::delete("public/$formation->picture");
                }
            }
        }

        $file = Storage::put('public', $params['picture']);
        $params['picture'] = substr($file, 7);

        $formation->update($params);

        return back()->with('pictureHasChanged', true);
    }
}
