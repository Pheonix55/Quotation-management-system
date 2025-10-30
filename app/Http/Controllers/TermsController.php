<?php

namespace App\Http\Controllers;

use App\Models\Terms;
use Auth;
use Illuminate\Http\Request;
use Storage;
use Throwable;

class TermsController extends Controller
{
    public function index()
    {
        $terms = Terms::latest()->paginate(15);
        return view('terms.index', compact('terms'));
    }
    public function create()
    {
        return view('terms.create');
    }
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'statements' => 'required|string',
            ]);
            $data['customer_id'] = Auth::user()->id;
            $term = Terms::create($data);
            // dd($request->all(), $data, $term);
            return redirect()->route('terms.index')->with('success', 'term created successfully');
        } catch (Throwable $th) {
            dd($th->getMessage());
        }
    }
    public function edit($id)
    {
        try {
            $terms = Terms::findOrFail($id);
            return view('terms.edit', compact('terms'));
        } catch (Throwable $th) {
            dd($th->getMessage);
        }
    }
    public function update(Request $request, $id)
    {
        try {
            $term = Terms::findOrFail($id);
            $data = $request->validate([
                'statements' => 'required|string',
            ]);

            // dd($request->all(), $term, $data);
            $term->statements = $data['statements'];
            $term->save();
            return redirect()->route('terms.index')->with('success', 'term updated successfully');
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
            $term = Terms::find($id);
            // dd($term);
            if (empty($term)) {
                return back()->with('errors', 'the specific id does not exist');
            }
            $term[0]->delete();
            return redirect()->route('terms.index')->with('success', 'term deleted successfully');
        } catch (Throwable $th) {
            dd($th->getMessage());
        }
    }
}
