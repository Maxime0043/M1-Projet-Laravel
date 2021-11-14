<?php

namespace App\Http\Controllers;

use App\Http\Requests\TypeAndCatgoryStoreRequest;
use App\Models\Category;
use App\Models\FormationCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Permet d'afficher l'ensemble des catégories de la base de données.
     *
     * @return void
     */
    public function index()
    {
        $categories = Category::all();
        return view('categories.list', compact('categories'));
    }

    /**
     * Permet d'ajouter une nouvelle catégorie dans la base de données.
     *
     * @param TypeAndCatgoryStoreRequest $request
     * @return void
     */
    public function store(TypeAndCatgoryStoreRequest $request)
    {
        $params = $request->validated();
        Category::create($params);
        return back()->with('categoryStored', $params['name']);
    }

    /**
     * Permet de supprimer une catégorie de la base de données.
     *
     * @param [type] $id
     * @return void
     */
    public function delete($id)
    {
        // On vérifie que l'utilisateur est un administrateur, si ce n'est pas le cas on le redirige vers la liste des formations
        if (!auth()->user()->is_admin)
            return redirect()->route('formations-list');

        // On récupère la catégorie à supprimer
        $category = Category::find($id);
        $categoryName = $category->name;

        // On supprime la liaison entre la catégories et les formations
        $formations_categories = FormationCategory::where('category', $id);
        $formations_categories->delete();

        // On supprime la catégorie
        $category->delete();

        return back()->with('categoryDeleted', $categoryName);
    }
}
