<?php

namespace App\Http\Controllers;

use App\Models\Terms;
use Auth;
use Illuminate\Http\Request;
use Throwable;
use Illuminate\Support\Facades\DB;

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


    public function removeDuplicateTerms()
    {
        $duplicates = Terms::select(
            DB::raw('LOWER(statements) as normalized_statement'),
            'customer_id',
            DB::raw('COUNT(*) as count')
        )
            ->groupBy('normalized_statement', 'customer_id')
            ->having('count', '>', 1)
            ->get();

        $deletedCount = 0;

        foreach ($duplicates as $dup) {
            $idsToDelete = Terms::whereRaw('LOWER(statements) = ?', [$dup->normalized_statement])
                ->where('customer_id', $dup->customer_id)
                ->orderBy('id', 'asc')
                ->pluck('id')
                ->slice(1); // Skip first record (keep one unique copy)

            if ($idsToDelete->isNotEmpty()) {
                Terms::whereIn('id', $idsToDelete)->delete();
                $deletedCount += $idsToDelete->count();
            }
        }

        return response()->json([
            'success' => true,
            'message' => "Removed $deletedCount duplicate term(s) (case-insensitive)."
        ]);
    }

}
