<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Auth;
use Illuminate\Http\Request;
use Storage;
use Throwable;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->with('category')->paginate(15);
        return view('product.index', compact('products'));
    }
    public function create()
    {
        $category = Category::all();
        return view('product.create', compact('category'));
    }
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string',
                'category_id' => 'required',
                'sale_price' => 'required',
                'cost_price' => 'required',
                'gst' => 'required',
                'bar_code' => 'required'

            ]);

            $data['customer_id'] = Auth::user()->id;
            $product = Product::create($data);
            // dd($request->all(), $data, $product);
            return redirect()->route('product.index')->with('success', 'product created successfully');
        } catch (Throwable $th) {
            dd($th->getMessage());
        }
    }
    public function edit($id)
    {
        try {
            $product = Product::findOrFail($id);
            return view('product.edit', compact('product'));
        } catch (Throwable $th) {
            dd($th->getMessage);
        }
    }
    public function update(Request $request, $id)
    {
        try {
            $product = Product::findOrFail($id);
            $data = $request->validate([
                'name' => 'required|string',
                'category_id' => 'required',
                'sale_price' => 'required',
                'cost_price' => 'required',
                'gst' => 'required',
                'bar_code' => 'required'
            ]);
            $product->name = $data['name'];
            $product->category_id = $data['category_id'];
            $product->sale_price = $data['sale_price'];
            $product->cost_price = $data['cost_price'];
            $product->gst = $data['gst'];
            $product->bar_code = $data['bar_code'];
            $product->save();
            return redirect()->route('product.index')->with('success', 'product updated successfully');
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
            $product = Product::find($id);

            $product[0]->delete();
            return redirect()->route('product.index')->with('success', 'product deleted successfully');
        } catch (Throwable $th) {
            dd($th->getMessage());
        }
    }
    public function search(Request $request)
    {
        $query = $request->get('q');
        if (!$query) {

            return response()->json(Product::all());
        }

        $products = Product::query()
            ->where('name', 'LIKE', "%{$query}%")
            ->orWhere('bar_code', 'LIKE', "%{$query}%")
            ->select('id', 'name', 'sale_price', 'gst', 'bar_code')
            ->limit(10)
            ->get();

        return response()->json($products);
    }
}
