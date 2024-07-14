<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $category = Category::orderBy('name')->where('rutin', 1)->get();
        return view('server.category.index', compact('category'));
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
                'rutin' => 1,
                'slug' => strtolower(Str::slug($request->name))
            ]
        );

        if ($request->id) {
            return redirect()->route('category.index')->with('success', 'Success Update Ibadah!');
        } else {
            return redirect()->back()->with('success', 'Success Add Ibadah!');
        }
    }

    public function destroy($id)
    {
        Category::find($id)->delete();
        return redirect()->back()->with('success', 'Success Delete Category!');
    }
}
