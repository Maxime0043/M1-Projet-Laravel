<?php

namespace App\Http\Controllers;

use App\Http\Requests\TypeAndCatgoryStoreRequest;
use App\Models\Category;
use App\Models\FormationCategory;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        return view('categories.list', compact('categories'));
    }

    public function store(TypeAndCatgoryStoreRequest $request)
    {
        $params = $request->validated();
        Category::create($params);
        return back()->with('categoryStored', $params['name']);
    }

    public function delete($id)
    {
        if (!auth()->user()->is_admin)
            return redirect()->route('formations-list');

        $category = Category::find($id);
        $categoryName = $category->name;

        $formations_categories = FormationCategory::where('category', $id);
        $formations_categories->delete();

        $category->delete();

        return back()->with('categoryDeleted', $categoryName);
    }
}
