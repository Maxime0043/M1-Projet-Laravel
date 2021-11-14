<?php

namespace App\Http\Controllers;

use App\Http\Requests\TypeAndCatgoryStoreRequest;
use App\Models\FormationType;
use App\Models\Type;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    /**
     * Permet d'afficher l'ensemble des types présents dans la base de données.
     *
     * @return void
     */
    public function index()
    {
        $types = Type::all();
        return view('types.list', compact('types'));
    }

    /**
     * Permet d'ajouter un type dans la base de données.
     *
     * @param TypeAndCatgoryStoreRequest $request
     * @return void
     */
    public function store(TypeAndCatgoryStoreRequest $request)
    {
        $params = $request->validated();
        Type::create($params);
        return back()->with('typeStored', $params['name']);
    }

    /**
     * Permet de supprimer un type présent dans la base de données.
     *
     * @param int $id
     * @return void
     */
    public function delete($id)
    {
        // Si l'utilisateur connecté n'est pas un administrateur, on le redirige vers la page des formations
        if (!auth()->user()->is_admin)
            return redirect()->route('formations-list');

        // On récupère le type courant
        $type = Type::find($id);
        $typeName = $type->name;

        // On supprime les liens avec toutes les formations auxquelles il est relié
        $formations_categories = FormationType::where('type', $id);
        $formations_categories->delete();

        // On supprime le type courant de la base de données
        $type->delete();

        return back()->with('typeDeleted', $typeName);
    }
}
