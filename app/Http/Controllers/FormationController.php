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
    /**
     * Permet d'afficher les formations présentes dans la base de données.
     *
     * @return void
     */
    public function index()
    {
        // Si un utilisateur est connecté
        if (Auth::check()) {
            // S'il est administrateur, on le redirige vers la page de gestion des utilisateurs
            if (auth()->user()->is_admin) {
                return redirect()->route('user-list');
            }

            // Sinon on recupère toutes les formations qui lui sont liées
            $formations = Formation::where('user_id', Auth::user()->id)->get();
        } else {
            // Sinon on récupère toutes les formations
            $formations = Formation::all();
        }

        return view('formations.list', compact('formations'));
    }

    /**
     * Permet de filtrer l'affichage des formations.
     *
     * @param FormationFilterRequest $request
     * @return void
     */
    public function filter(FormationFilterRequest $request)
    {
        // On récupères les filtres entrés par l'utilisateur
        $params = $request->validated();

        $isFiltered = true;

        // Si aucun filtre n'a été donné, on récupère toutes les formations
        if (sizeof($params) == 0) {
            $formations = Formation::all();
        } else {
            // Sinon, si tous les filtres sont null, on redirige l'utilisateur sur la page où il était avant d'effectuer la requête
            if ($params['title'] == null && $params['types'] == null && $params['categories'] == null) {
                return back();
            }

            $filters = $params;

            // Si un utilisateur est connecté
            if (Auth::check()) {
                // S'il est administrateur, on le redirige vers la page de gestion des utilisateurs
                if (auth()->user()->is_admin) {
                    return redirect()->route('user-list');
                }

                // Sinon on recupère toutes les formations qui lui sont liées
                $formations = Formation::where('user_id', Auth::user()->id)->get();
            } else {
                // Sinon on récupère toutes les formations
                $formations = Formation::all();
            }

            // Si un titre de formation a été donné, on va garder toutes les formations qui contiennent ce filtre comme titre
            if ($params['title'] != null) {
                $formationTitles = Formation::where('title', 'like', "%" . $params['title'] . "%")->get();
                $formations = $formations->intersect($formationTitles);
            }

            // Si des types ont été donné, on va garder toutes les formations possédant au moins un type du filtre donné
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

            // Si des catégories ont été donné, on va garder toutes les formations possédant au moins une catégorie du filtre donné
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

        // On affiche les formations filtrées en fonction de si des filtres ont été entré
        if (!empty($filters))
            return view('formations.list', compact(['formations', 'filters', 'isFiltered']));

        return view('formations.list', compact(['formations', 'isFiltered']));
    }

    /**
     * Permet d'afficher le détail d'une formation.
     *
     * @param int $id
     * @return void
     */
    public function details($id)
    {
        // Si un utilisateur est connecté
        if (Auth::check()) {
            // si l'utilisateur n'est pas un administrateur, on va vérifier que la formation à afficher appartient bien à l'utilisateur connecté
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

        // On récupère la formation courante
        $formation = Formation::find($id);

        // Si la formation n'existe pas, on redirige l'utilisateur sur la page des formations
        if (!$formation)
            return redirect()->route('formations-list');

        // Si l'utilisateur n'est pas connecté
        if (!Auth::check()) {
            // Si la formation ne possède pas de chapitres, on redirige l'utilisateur sur la page où il était avant d'effectuer la requête
            if (!sizeof($formation->chapters)) {
                return back();
            }
        }

        // On récupère les types et les catégories
        $types = Type::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('formations.details', compact(['formation', 'types', 'categories']));
    }

    /**
     * Permet d'afficher le formulaire de création de formations.
     *
     * @return void
     */
    public function add()
    {
        // Si l'utilisateur connecté est un administrateur, on le redirige vers la page de gestion des utilisateurs
        if (auth()->user()->is_admin)
            return redirect()->route('user-list');

        // On récupère les types et les catégories
        $types = Type::orderBy('name')->get();
        $categories = Category::orderBy('name')->get();

        return view('formations.add_formation', compact(['types', 'categories']));
    }

    /**
     * Permet d'ajouter une formation dans la base de données.
     *
     * @param FormationStoreRequest $request
     * @return void
     */
    public function store(FormationStoreRequest $request)
    {
        // Si l'utilisateur connecté est un administrateur, on le redirige vers la page de gestion des utilisateurs
        if (auth()->user()->is_admin)
            return redirect()->route('user-list');

        // On récupère les informations entrées par l'utilisateur
        $params = $request->validated();

        // Si l'utilisateur n'a pas renseigner de types et de catégories, on lui indique
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

        // On sauvegarde l'image de couverture de la formation
        $file = Storage::put('public', $params['picture']);
        $params['picture'] = substr($file, 7);

        // On crée la formation avec les informations précédentes
        $formation = Formation::create($params);

        // Si des types ont été sélectionnés, on les lies à la formation
        if (!empty($params['checkboxTypes'])) {
            $formation->types()->attach($params['checkboxTypes']);
        }

        // Si des catégories ont été sélectionnés, on les lies à la formation
        if (!empty($params['checkboxCategories'])) {
            $formation->categories()->attach($params['checkboxCategories']);
        }

        return redirect()->route('formation-details', $formation->id);
    }

    /**
     * Permet de supprimer une formation de la base de données.
     *
     * @param int $id
     * @return void
     */
    public function delete($id)
    {
        // On récupère la formation courante
        $formation = Formation::find($id);
        $title = $formation->title;

        // Si la formation possède une image de couverture et que ce n'est pas une URL, alors on supprime l'image
        if ($formation->picture != null) {
            if (!filter_var($formation->picture, FILTER_VALIDATE_URL)) {
                if (Storage::exists("public/$formation->picture")) {
                    Storage::delete("public/$formation->picture");
                }
            }
        }

        // On supprime tous les chapitres de la formation
        $formation->chapters()->delete();
        // On supprime les liens entre la formation et les types et catégories
        $formation->categories()->detach();
        $formation->types()->detach();

        // On supprime la formation
        $formation->delete();

        return redirect()->route('formations-list')->with('formationDeleted', $title);
    }

    /**
     * Permet de modifier les informations d'une formation.
     *
     * @param int $id
     * @param FormationUpdateRequest $request
     * @return void
     */
    public function update($id, FormationUpdateRequest $request)
    {
        // On récupère les informations entrées par l'utilisateur
        $params = $request->validated();

        // Si l'utilisateur n'a pas renseigner de types et de catégories, on lui indique
        if (empty($params['checkboxTypes']) && empty($params['checkboxCategories'])) {
            return back()
                ->withInput($params)
                ->withErrors(['needTypesOrCategories' => 'Vous devez sélectionner au moins un type ou une catégorie.']);
        }

        if (preg_match('/^[1-9]\d{0,2}$/', $params['price'])) {
            $params['price'] = $params['price'] . '.00';
        }

        $params['price'] = str_replace(',', '.', $params['price']);

        // On récupère la formation courante et on met à jour ses informations dans la base de données
        $formation = Formation::find($id);
        $formation->update($params);

        // On supprimes tous les liens entre la formation et les types / catégories
        $formation->types()->detach();
        $formation->categories()->detach();

        // Si des types ont été sélectionnés, on les lies à la formation
        if (!empty($params['checkboxTypes'])) {
            $formation->types()->attach($params['checkboxTypes']);
        }

        // Si des catégories ont été sélectionnés, on les lies à la formation
        if (!empty($params['checkboxCategories'])) {
            $formation->categories()->attach($params['checkboxCategories']);
        }

        return back()->with('informationsHasChanged', true);
    }

    /**
     * Permet de modifier l'image de couverture d'une formation dans la base de données.
     *
     * @param int $id
     * @param FormationUpdatePictureRequest $request
     * @return void
     */
    public function updatePicture($id, FormationUpdatePictureRequest $request)
    {
        // On récupère les informations entrées par l'utilisateur
        $params = $request->validated();

        // On récupère la formation courante
        $formation = Formation::find($id);

        // Si la formation possède une image de couverture et que ce n'est pas une URL, alors on supprime l'image
        if ($formation->picture != null) {
            if (!filter_var($formation->picture, FILTER_VALIDATE_URL)) {
                if (Storage::exists("public/$formation->picture")) {
                    Storage::delete("public/$formation->picture");
                }
            }
        }

        // On sauvegarde l'image de couverture de la formation
        $file = Storage::put('public', $params['picture']);
        $params['picture'] = substr($file, 7);

        // On met à jour les informations de la formation dans la base de données
        $formation->update($params);

        return back()->with('pictureHasChanged', true);
    }
}
