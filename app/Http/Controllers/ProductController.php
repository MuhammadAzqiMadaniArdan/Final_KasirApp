<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::orderBy('id', 'DESC')->get();

        return view('product.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('product.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|string|max:255",
            "price" => "required|integer|min:0",
            "stock" => "required|integer|min:0",
            "image" => "required|image|mimes:jpg,png,jpeg,gif,svg|max:2048"
        ]);

        if ($validator->fails()) {
            return redirect()->route('product.index')->with('failed', $validator->errors());
        }


        $validated = $validator->validated();

        $path = $request->file('image')->store('products', 'public');
        $validated['image'] = $path;

        try {
            Product::create($validated);
        } catch (\Exception $e) {
            return redirect()->route('product.index')->with('failed', $e->getMessage());
        }

        return redirect()->route('product.index')->with('success', 'Berhasil Menambahkan Produk');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('product.edit',compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            "name" => "string|max:255",
            "price" => "integer|min:0",
            "stock" => "integer|min:0",
            "image" => "nullable|image|mimes:jpg,png,jpeg,gif,svg|max:2048"
        ]);

        if ($validator->fails()) {
            return redirect()->route('product.index')->with('failed', $validator->errors());
        }

        $validated = $validator->validated();
        if (isset($validated['image'])) {
            $path = $request->file('image')->store('products', 'public');
            $validated['image'] = $path;
        }

        try {
            $product = Product::findOrFail($id);
            $product->update($validated);
        } catch (\Exception $e) {
            return redirect()->route('product.index')->with('failed', $e->getMessage());
        }

        return redirect()->route('product.index')->with('success', 'Berhasil Mengupdate Produk');
    }

    public function updateStock(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            "stock" => "integer|min:0",
        ]);

        if ($validator->fails()) {
            return redirect()->route('product.index')->with('failed', $validator->errors());
        }

        $validated = $validator->validated();

        try {
            $product = Product::findOrFail($id);
            $product->update($validated);
        } catch (\Exception $e) {
            return redirect()->route('product.index')->with('failed', $e->getMessage());
        }

        return redirect()->route('product.index')->with('success', 'Berhasil Mengupdate Produk');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('product.index')->with('success', 'Berhasil Menghapus Produk');
    }
}
