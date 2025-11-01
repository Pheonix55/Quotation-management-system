<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\QuoteRequest;
use Illuminate\Http\Request;

class QuoteRequestController extends Controller
{
    public function create()
    {
        $products = auth()->user()->products;
        if ($products == null) {
            $category = Category::all();
            // TODO:: need to select categories with select2
            return view('customer.quote', compact('products', 'category'));

        }
        return view('customer.quote', compact('products'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'product_ids' => 'required|array|min:1',
            'terms' => 'nullable|string',
        ]);

        $quoteRequest = QuoteRequest::create([
            'user_id' => auth()->id(),
            'status' => 'submitted',
        ]);

        $quoteRequest->products()->sync($request->product_ids);

        return redirect()->route('quote.success')->with('success', 'Your quote request has been submitted.');
    }
}
