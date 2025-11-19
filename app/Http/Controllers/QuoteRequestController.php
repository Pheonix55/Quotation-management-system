<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\QuoteRequest;
use App\QuotationStatus;
use App\Services\NotificationService;
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
        $user = auth()->user();
        $request->validate([
            'product_ids' => 'required|array|min:1',
            'terms' => 'nullable|string',
        ]);

        $quoteRequest = QuoteRequest::create([
            'user_id' => $user->id,
            'status' => QuotationStatus::Submitted,
        ]);

        $quoteRequest->products()->sync($request->product_ids);
        // dd($quoteRequest->products);
        NotificationService::push('info', 'new Quotation request from ' . $user->name, adminUser());

        return redirect()->route('quote.request.success')->with('success', 'Your quote request has been submitted.');
    }

    public function show($id)
    {
        $quote = QuoteRequest::with('user', 'products')->where('id', $id)->first();
        return view('customer.quote-show', compact('quote'));
    }
   
    public function success()
    {
        return view('customer.quote-success');
    }
}
