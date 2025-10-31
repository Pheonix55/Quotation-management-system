<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Throwable;

class CategoryController extends Controller
{
    public function index()
    {
        $terms = Category::latest()->paginate(15);
        return view('category.index', compact('terms'));
    }
    public function create()
    {
        return view('category.create');
    }
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string',
            ]);
            $term = Category::create($data);
            return redirect()->route('category.index')->with('success', 'term created successfully');
        } catch (Throwable $th) {
            dd($th->getMessage());
        }
    }
    public function edit($id)
    {
        try {
            $terms = Category::findOrFail($id);
            return view('category.edit', compact('terms'));
        } catch (Throwable $th) {
            dd($th->getMessage);
        }
    }
    public function update(Request $request, $id)
    {
        try {
            $term = Category::findOrFail($id);
            $data = $request->validate([
                'name' => 'required|string',
            ]);

            // dd($request->all(), $term, $data);
            $term->name = $data['name'];
            $term->save();
            return redirect()->route('category.index')->with('success', 'term updated successfully');
        } catch (Throwable $th) {
            dd($th->getMessage());
        }

    }
    public function destroy(Request $request)
    {

        try {
            $id = $request->validate([
                'id' => 'required'
            ]);
            $term = Category::find($id);
            // dd($term);
            if (empty($term)) {
                return back()->with('errors', 'the specific id does not exist');
            }
            $term[0]->delete();
            return redirect()->route('category.index')->with('success', 'term deleted successfully');
        } catch (Throwable $th) {
            dd($th->getMessage());
        }
    }
}
