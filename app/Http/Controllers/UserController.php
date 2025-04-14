<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    //
    public function dashboard()
    {
        $today = Carbon::today();
        $ordersToday = Order::whereDate('created_at',$today)->count();
        return view('dashboard',compact('ordersToday'));
    }

    public function index()
    {
        $users = User::orderBy('id', 'DESC')->get();

        return view('user.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('user.create');
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
            return redirect()->route('user.index')->with('failed', $validator->errors());
        }


        $validated = $validator->validated();

        $password = Hash::make($request->password);
        $validated['password'] = $password;

        try {
            User::create($validated);
        } catch (\Exception $e) {
            return redirect()->route('user.index')->with('failed', $e->getMessage());
        }

        return redirect()->route('user.index')->with('success', 'Berhasil Menambahkan Produk');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('user.edit',compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            "name" => "string|max:255",
            "email" => "email:dns|max:255",
            "role" => "string|in:Admin,Employee",
            "password" => "",
        ]);

        if ($validator->fails()) {
            return redirect()->route('user.index')->with('failed', $validator->errors());
        }

        $validated = $validator->validated();
        $user = User::findOrFail($id);
        if ($validated['password'] == null) {
            $password = $user['password'];
            $validated['password'] = $password;
        }else{
            $password = Hash::make($request->password);
            $validated['password'] = $password;
        }

        try {
            $user->update($validated);
        } catch (\Exception $e) {
            dd($e->getMessage());
            return redirect()->route('user.index')->with('failed', $e->getMessage());
        }

        return redirect()->route('user.index')->with('success', 'Berhasil Mengupdate Produk');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('user.index')->with('success', 'Berhasil Menghapus Produk');
    }
}
