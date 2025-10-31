<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\quotations;
use App\Models\QuotationTerm;
use App\Models\Terms;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Spatie\LaravelPdf\Facades\Pdf;
use Throwable;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }
    public function getQuote()
    {
        $customers = User::where('isCustomer', 1)->get();
        return view('quotations.getQuote', compact('customers'));
    }
    public function storeQuoteStep1(Request $request)
    {
        try {
            $data = $request->validate([
                'customer_id' => 'required|integer',
                'quotation_date' => 'nullable|date',
                'quotation_time' => 'nullable|date_format:H:i',
                'validity_date' => 'nullable|date|after_or_equal:quotation_date',
                'notes' => 'nullable|string|max:1000',
                'total' => 'nullable|numeric|min:0',
                'is_completed' => 'nullable|boolean',
            ]);
            $data['quantity'] = 0;
            $quotation = Quotations::create([
                ...$data,
            ]);

            return redirect()->route('quotation.addProducts', ['id' => $quotation->id])->with('success', 'success');

        } catch (Throwable $th) {
            dd($th->getMessage());
        }
    }

    public function addProducts($id)
    {
        $quotation = Quotations::find($id);
        $customer = User::where('id', $quotation->customer_id)->first();
        $products = Product::all();
        if ($quotation->status === 'completed') {
            return redirect()->route('quotation.view', $id);
        }

        return view('quotations.add-products', compact('quotation', 'customer', 'products', 'id'));
    }
    public function saveQuotationProducts(Request $request, $id)
    {

        $quantity = $request->quantity;
        $quotation = Quotations::find($id);
        $quotation->update([
            'product_ids' => json_encode($request->product_ids),
            'total' => json_encode($request->total),
        ]);
        $salePrice = [];
        foreach ($request->product_ids as $Pid) {
            $salePrice[] = Product::where('id', $Pid)->pluck('sale_price');
        }

        $quotation->price = json_encode($salePrice);
        $quotation->quantity = json_encode($quantity);
        $quotation->save();

        return response()->json([
            'success' => true,
            'redirectRoute' => route('quotation.completeView', $id)
        ]);
    }
    public function completeQuotationView($id)
    {
        $terms = Terms::all();
        $quotation = Quotations::find($id);
        return view('quotations.add-terms', compact('terms', 'quotation'));
    }
    public function completeQuotation(Request $request)
    {
        $quotationId = session('quotation_id');
        $quotation = Quotations::findOrFail($quotationId);

        $quotation->update(['status' => 'completed']);


        return redirect()->route('quotation.view', $quotationId)
            ->with('success', 'Quotation created successfully!');
    }

    public function addTerms($quotationId)
    {
        $quotation = Quotations::findOrFail($quotationId);
        $terms = Terms::where('customer_id', $quotation->customer_id)->get();

        return view('quotations.add-terms', compact('quotation', 'terms'));
    }
    public function storeTerms(Request $request, $quotationId)
    {
        $quotation = Quotations::findOrFail($quotationId);

        $validated = $request->validate([
            'term_ids' => 'required|array',
            'term_ids.*' => 'exists:terms,id',
        ]);

        $quotation->terms()->detach();

        foreach ($validated['term_ids'] as $termId) {
            $quotation->terms()->attach($termId, [
                'custom_text' => $request->input("custom_terms.$termId")
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Terms have been added to this quotation successfully!',
            'redirectRoute' => route('quotations.show', $quotationId),
        ]);
    }

    public function show($id)
    {
        $quotation = Quotations::findOrFail($id);
        $prod_ids = json_decode($quotation->product_ids);
        $quotation->load('customer');
        $quotationTerms = QuotationTerm::where('quotation_id', $quotation->id)->get();
        $total = is_string($quotation->total)
            ? json_decode($quotation->total, true)
            : $quotation->total;
        foreach ($prod_ids as $id) {
            $newproducts[] = Product::where('id', $id)->first();
        }
        $iteration = 0;
        $products = [];

        foreach ($newproducts as $prod) {
            $products[] = [
                'product' => $prod,
                'total' => $total[$iteration]
            ];
            $iteration++;
        }
        return view('quotations.quotation_print', compact('quotation', 'products', 'quotationTerms'));
    }

    public function downloadPdf(quotations $quotation)
    {
        $quotation->load('customer');
        $prod_ids = json_decode($quotation->product_ids);
        $quotationTerms = QuotationTerm::where('quotation_id', $quotation->id)->get();

        foreach ($prod_ids as $id) {
            $products[] = Product::where('id', $id)->first();
        }

        $total = is_string($quotation->total)
            ? json_decode($quotation->total, true)
            : $quotation->total;
        foreach ($prod_ids as $id) {
            $newproducts[] = Product::where('id', $id)->first();
        }
        $iteration = 0;
        $products = [];

        foreach ($newproducts as $prod) {
            $products[] = [
                'product' => $prod,
                'total' => $total[$iteration]
            ];
            $iteration++;
        }

        return Pdf::view('quotations.quotation_print', compact('quotation', 'products', 'quotationTerms'))
            ->format('a4')
            ->name("quotation-{$quotation->id}.pdf")
            ->download();
    }

    public function viewPdf(quotations $quotation)
    {
        $quotation->load('customer');
        $prod_ids = json_decode($quotation->product_ids);
        $quotationTerms = QuotationTerm::where('quotation_id', $quotation->id)->get();

        foreach ($prod_ids as $id) {
            $products[] = Product::where('id', $id)->first();
        }
        return Pdf::view('quotations.quotation_print', compact('quotation', 'products', 'quotationTerms'))
            ->format('a4')
            ->name("quotation-{$quotation->id}.pdf")
            ->inline();
    }


}
