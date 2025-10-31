<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\quotations;
use App\Models\QuotationTerm;
use App\Models\Terms;
use App\Models\User;
use Auth;
use Dotenv\Exception\ValidationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
                'customer_id' => 'required|exists:users,id',
                'quotation_date' => 'required|date',
                'quotation_time' => 'required|date_format:H:i',
                'validity_date' => 'required|date|after_or_equal:quotation_date',
                'notes' => 'nullable|string|max:1000',
                'total' => 'nullable|numeric|min:0',
                'is_completed' => 'nullable|boolean',
            ]);

            $data['quantity'] = 0;

            $quotation = Quotations::create($data);

            return redirect()
                ->route('quotation.addProducts', ['id' => $quotation->id])
                ->with('success', 'Quotation created successfully.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()
                ->withErrors($e->validator)
                ->withInput();
        } catch (\Throwable $th) {
            \Log::error('Quote Step 1 Error: ' . $th->getMessage());

            return back()
                ->with('error', 'Something went wrong while saving the quotation. Please try again.')
                ->withInput();
        }
    }

    public function addProducts($id)
    {
        try {
            $quotation = Quotations::find($id);

            if (!$quotation) {
                return redirect()
                    ->route('dashboard')
                    ->with('error', 'Quotation not found.');
            }

            $customer = User::find($quotation->customer_id);
            if (!$customer) {
                return redirect()
                    ->route('dashboard')
                    ->with('error', 'Customer not found for this quotation.');
            }

            if ($quotation->is_completed) {
                return redirect()
                    ->route('quotations.show', $id)
                    ->with('info', 'This quotation has already been completed.');
            }

            $products = Product::all();

            return view('quotations.add-products', compact('quotation', 'customer', 'products', 'id'));

        } catch (\Throwable $th) {
            \Log::error('Add Products Error: ' . $th->getMessage());

            return redirect()
                ->route('dashboard')
                ->with('error', 'Something went wrong while loading products. Please try again.');
        }
    }

    public function saveQuotationProducts(Request $request, $id)
    {
        try {
            // Validate input
            $validated = $request->validate([
                'product_ids' => 'required|array|min:1',
                'product_ids.*' => 'integer|exists:products,id',
                'quantity' => 'required|array|min:1',
                'quantity.*' => 'numeric|min:1',
                'total' => 'required|array|min:1',
                'total.*' => 'numeric|min:0',
            ]);

            $quotation = Quotations::findOrFail($id);

            $salePrices = Product::whereIn('id', $validated['product_ids'])
                ->pluck('sale_price', 'id')
                ->toArray();

            $orderedPrices = array_map(fn($pid) => $salePrices[$pid] ?? 0, $validated['product_ids']);

            $quotation->update([
                'product_ids' => json_encode($validated['product_ids']),
                'quantity' => json_encode($validated['quantity']),
                'total' => json_encode($validated['total']),
                'price' => json_encode($orderedPrices),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Products have been added to the quotation successfully.',
                'redirectRoute' => route('quotation.completeView', $id),
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors' => $e->getMessage(),
            ], 422);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Quotation not found.',
            ], 404);

        } catch (Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function completeQuotationView($id)
    {
        $quotation = Quotations::find($id);

        if ($quotation->is_completed) {
            return redirect()
                ->route('quotations.show', $id)
                ->with('info', 'This quotation has already been completed.');
        }
        $terms = Terms::all();
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
    // public function storeTerms(Request $request, $quotationId)
    // {
    //     dd($request->all());
    //     $quotation = Quotations::findOrFail($quotationId);

    //     $validated = $request->validate([
    //         'term_ids' => 'required|array',
    //         'term_ids.*' => 'exists:terms,id',
    //     ]);

    //     $quotation->terms()->detach();

    //     foreach ($validated['term_ids'] as $termId) {
    //         $quotation->terms()->attach($termId, [
    //             'custom_text' => $request->input("custom_terms.$termId")
    //         ]);
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Terms have been added to this quotation successfully!',
    //         'redirectRoute' => route('quotations.show', $quotationId),
    //     ]);
    // }


    public function storeTerms(Request $request, $quotationId)
    {
        $quotation = Quotations::findOrFail($quotationId);

        $validated = $request->validate([
            'term_ids' => 'required|array',
            'term_ids.*' => 'exists:terms,id',
            'custom_terms' => 'array',
            'custom_terms.*' => 'string|nullable',
            'custom_terms_new' => 'array',
            'custom_terms_new.*' => 'string|nullable',
        ]);

        $quotation->terms()->detach();

        foreach ($validated['term_ids'] as $termId) {
            $quotation->terms()->attach($termId, [
                'custom_text' => $request->input("custom_terms.$termId")
            ]);
        }

        if (!empty($validated['custom_terms_new'])) {
            foreach ($validated['custom_terms_new'] as $text) {
                $trimmedText = trim($text);
                if ($trimmedText === '')
                    continue;

                $newTerm = Terms::create([
                    'customer_id' => Auth::user()->id,
                    'statements' => $trimmedText,
                    'is_custom' => true,
                ]);

                $quotation->terms()->attach($newTerm->id, [
                    'custom_text' => $trimmedText,
                ]);
            }
        }

        $quotation->is_completed = true;
        $quotation->save();

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
        foreach ($prod_ids as $pid) {
            $newproducts[] = Product::where('id', $pid)->first();
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
        return view('quotations.quote', compact('quotation', 'products', 'quotationTerms', 'id'));
    }

    public function downloadPdf(quotations $quotation)
    {
        $quotation->load('customer');
        $prod_ids = json_decode($quotation->product_ids);
        $quotationTerms = QuotationTerm::where('quotation_id', $quotation->id)->get();

        foreach ($prod_ids as $Pid) {
            $products[] = Product::where('id', $Pid)->first();
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

    public function viewPdf($id)
    {
        // dd($id);
        $quotation = Quotations::findOrFail($id);
        $prod_ids = json_decode($quotation->product_ids);
        $quotation->load('customer');
        $quotationTerms = QuotationTerm::where('quotation_id', $quotation->id)->get();
        $total = is_string($quotation->total)
            ? json_decode($quotation->total, true)
            : $quotation->total;
        foreach ($prod_ids as $Pid) {
            $newproducts[] = Product::where('id', $Pid)->first();
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
        //     return view('quotations.quote', compact('quotation', 'products', 'quotationTerms', 'id'));
        // }
        return Pdf::view('quotations.quotation_print', compact('quotation', 'products', 'quotationTerms', 'id'))
            ->format('a4')
            ->name("quotation-{$quotation->id}.pdf")
            ->inline();
    }

    // public function ViewPdf($id)
    // {
    //     $quotation = Quotations::findOrFail($id);
    //     $prod_ids = json_decode($quotation->product_ids);
    //     $quotation->load('customer');
    //     $quotationTerms = QuotationTerm::where('quotation_id', $quotation->id)->get();
    //     $total = is_string($quotation->total)
    //         ? json_decode($quotation->total, true)
    //         : $quotation->total;
    //     foreach ($prod_ids as $id) {
    //         $newproducts[] = Product::where('id', $id)->first();
    //     }
    //     $iteration = 0;
    //     $products = [];

    //     foreach ($newproducts as $prod) {
    //         $products[] = [
    //             'product' => $prod,
    //             'total' => $total[$iteration]
    //         ];
    //         $iteration++;
    //     }
    //     return view('quotations.quotation_print', compact('quotation', 'products', 'quotationTerms', 'id'));
    // }


}
