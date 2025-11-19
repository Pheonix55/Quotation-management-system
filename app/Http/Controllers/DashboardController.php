<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Quotation;
use App\Models\QuotationTerm;
use App\Models\QuoteRequest;
use App\Models\Terms;
use App\Models\User;
use App\Services\NotificationService;
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
        $user = Auth::user();

        $quotations_not_complete = Quotation::where('created_by', $user->id)
            ->where('is_completed', false)
            ->orderByDesc('created_at')
            ->get();

        if ($user->isCustomer) {
            $quoteRequests = $user->quoteRequests()->with('products')->latest()->paginate(15);
            return view('customer-dashboard', compact('quoteRequests'));
        } else {
            $quoteRequests = QuoteRequest::where('status', '!=', \App\QuotationStatus::Quoted)
                ->with('products', 'user')
                ->latest()
                ->paginate(10);

            return view('dashboard', compact('quotations_not_complete', 'quoteRequests'));
        }
    }

    public function getQuotes(Request $request)
    {
        $query = QuoteRequest::where('user_id', auth()->id());

        if ($search = $request->get('search')) {
            $query->where('status', 'like', "%$search%")
                ->orWhereDate('created_at', $search);
        }

        $quoteRequests = $query->with('products')->latest()->paginate(10);

        return view('customer-dashboard', compact('quoteRequests'));
    }

    public function getQuote($id = null, $quoteRequestId = null)
    {
        if ($id && $quoteRequestId) {
            $selectedCustomer = User::find($id);

            $customers = collect([$selectedCustomer]);
        } else {
            $selectedCustomer = null;
            $customers = User::where('isCustomer', 1)->get();
        }

        return view('quotations.getQuote', compact('customers', 'selectedCustomer', 'quoteRequestId'));
    }


    public function storeQuoteStep1(Request $request, $quoteRequestId = null)
    {
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
        $data['created_by'] = Auth::user()->id;
        $quotation = Quotation::create($data);

        return redirect()
            ->route('quotation.addProducts', ['id' => $quotation->id, 'quoteRequestId' => $quoteRequestId])
            ->with('success', 'Quotation created successfully.');


    }

    public function addProducts($id, $quoteRequestId)
    {
        try {
            $qr = QuoteRequest::find($quoteRequestId);
            $selectedProducts = $qr->products;
            $quotation = Quotation::find($id);

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
            $validated = $request->validate([
                'product_ids' => 'required|array|min:1',
                'product_ids.*' => 'integer|exists:products,id',
                'quantity' => 'required|array|min:1',
                'quantity.*' => 'numeric|min:1',
                'total' => 'required|array|min:1',
                'total.*' => 'numeric|min:0',
            ]);

            $quotation = Quotation::findOrFail($id);

            // Get sale prices
            $salePrices = Product::whereIn('id', $validated['product_ids'])
                ->pluck('sale_price', 'id')
                ->toArray();

            // Sync products to quotation
            $syncData = [];
            foreach ($validated['product_ids'] as $index => $productId) {
                $price = $salePrices[$productId] ?? 0;
                $quantity = $validated['quantity'][$index];
                $total = $validated['total'][$index];

                $syncData[$productId] = [
                    'quantity' => $quantity,
                    'price' => $price,
                    'total' => $total,
                ];
            }

            $quotation->products()->sync($syncData);

            return response()->json([
                'success' => true,
                'message' => 'Products successfully added to quotation.',
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
        $quotation = Quotation::find($id);

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
        $quotation = Quotation::findOrFail($quotationId);

        $quotation->update(['status' => 'completed']);


        return redirect()->route('quotation.view', $quotationId)
            ->with('success', 'Quotation created successfully!');
    }

    public function addTerms($quotationId)
    {
        $quotation = Quotation::findOrFail($quotationId);
        $terms = Terms::where('customer_id', $quotation->customer_id)->get();

        return view('quotations.add-terms', compact('quotation', 'terms'));
    }
    // public function storeTerms(Request $request, $quotationId)
    // {
    //     dd($request->all());
    //     $quotation = Quotation::findOrFail($quotationId);

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
        $quotation = Quotation::with('customer')->findOrFail($quotationId);

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
        NotificationService::push('info', 'Your quote is complete', $quotation->customer);

        return response()->json([
            'success' => true,
            'message' => 'Terms have been added to this quotation successfully!',
            'redirectRoute' => route('quotations.show', $quotationId),
        ]);
    }


    public function show($id)
    {
        $quotation = Quotation::findOrFail($id);
        $prod_ids = json_decode($quotation->product_ids);
        $quotation->load('customer', 'products');
        $quotationTerms = QuotationTerm::where('quotation_id', $quotation->id)->get();
        return view('quotations.quote', compact('quotation', 'quotationTerms', 'id'));
    }
    public function edit($id)
    {
        $quotation = Quotation::find($id);
        return view('quotations.edit', compact('quotation'));
    }

    public function downloadPdf(Quotation $quotation)
    {
        $quotation->load('customer', 'products');
        $prod_ids = json_decode($quotation->product_ids);
        $quotationTerms = QuotationTerm::where('quotation_id', $quotation->id)->get();



        return Pdf::view('quotations.quotation_print', compact('quotation', 'quotationTerms'))
            ->format('a4')
            ->name("quotation-{$quotation->id}.pdf")
            ->download();
    }

    public function viewPdf($id)
    {
        // dd($id);
        $quotation = Quotation::findOrFail($id);
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
    //     $quotation = Quotation::findOrFail($id);
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
