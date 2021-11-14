<?php

namespace App\Http\Controllers;

use App\Http\Requests\TypeAndCatgoryStoreRequest;
use App\Models\FormationType;
use App\Models\Type;
use Illuminate\Http\Request;

class TypeController extends Controller
{
    public function index()
    {
        $types = Type::all();
        return view('types.list', compact('types'));
    }

    public function store(TypeAndCatgoryStoreRequest $request)
    {
        $params = $request->validated();
        Type::create($params);
        return back()->with('typeStored', $params['name']);
    }

    public function delete($id)
    {
        if (!auth()->user()->is_admin)
            return redirect()->route('formations-list');

        $type = Type::find($id);
        $typeName = $type->name;

        $formations_categories = FormationType::where('type', $id);
        $formations_categories->delete();

        $type->delete();

        return back()->with('typeDeleted', $typeName);
    }
}
