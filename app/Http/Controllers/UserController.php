<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //
    public function dashboard()
    {
        return view('dashboard');
    }

    public function index()
    {
        $orders = Order::orderBy('id', 'DESC')->get();

        return view('order.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('order.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "name" => "required|string|max:255",
            "email" => "required|email:dns",
            "role" => "required|in:Admin,Employee",
            "password" => "required|string|min:0",
        ]);

        if ($validator->fails()) {
            return redirect()->route('order.index')->with('failed', $validator->errors());
        }


        $validated = $validator->validated();

        $path = $request->file('image')->store('orders', 'public');
        $validated['image'] = $path;

        try {
            Order::create($validated);
        } catch (\Exception $e) {
            return redirect()->route('order.index')->with('failed', $e->getMessage());
        }

        return redirect()->route('order.index')->with('success', 'Berhasil Menambahkan Produk');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $order = Order::findOrFail($id);
        return view('order.edit',compact('order'));
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
            return redirect()->route('order.index')->with('failed', $validator->errors());
        }

        $validated = $validator->validated();
        if (isset($validated['image'])) {
            $path = $request->file('image')->store('orders', 'public');
            $validated['image'] = $path;
        }

        try {
            $order = Order::findOrFail($id);
            $order->update($validated);
        } catch (\Exception $e) {
            return redirect()->route('order.index')->with('failed', $e->getMessage());
        }

        return redirect()->route('order.index')->with('success', 'Berhasil Mengupdate Produk');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return redirect()->route('order.index')->with('success', 'Berhasil Menghapus Produk');
    }
}
