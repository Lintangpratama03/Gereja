<?php

namespace App\Http\Controllers;


use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class CategoryAcaraController extends Controller
{
    public function index()
    {
        $category = Category::orderBy('name')->where('rutin', 2)->get();
        return view('server.category.index2', compact('category'));
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);

        Category::updateOrCreate(
            [
                'id' => $request->id
            ],
            [
                'name' => $request->name,
                'harga' => $request->harga,
                'rutin' => 2,
                'slug' => strtolower(Str::slug($request->name))
            ]
        );

        if ($request->id) {
            return redirect()->route('acara.index')->with('success', 'Success Update Acara!');
        } else {
            return redirect()->back()->with('success', 'Success Add Acara!');
        }
    }

    public function destroy($id)
    {
        Category::find($id)->delete();
        return redirect()->back()->with('success', 'Success Delete Acara!');
    }
}
