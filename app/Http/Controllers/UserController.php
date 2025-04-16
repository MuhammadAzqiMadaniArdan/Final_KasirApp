<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Exports\UserExport;
use App\Models\Order_detail;
use App\Models\User;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Excel;

class UserController extends Controller
{
    //

    public function dashboard()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        // 1. Order hari ini
        $today = Carbon::today();
        $ordersToday = Order::whereDate('created_at', $today)->count();

        // $today - Carbon::today();
        // $ordersToday = Order::whereDate('created_at',$today)->count();

        // 🔹 Penjualan per hari (bar chart)]
        $dailySales = order_detail::whereBetween('created_at',[$startOfMonth,$endOfMonth])->selectRaw('DATE(created_at) as date,SUM(qty) as total')->groupBy('date')->orderBy('date','ASC')->get();

        $dates = $dailySales->pluck('date')->map(function($date){
            return Carbon::parse($date)->format('d F Y');
        })->toArray();

        $totals = $dailySales->pluck('total')->toArray();

        $barChart = (new LarapexChart)->setType('bar')->setTitle('Jumlah Penjualan')->setXAxis($dates)->setDataset([
            [
                'name' => 'Jumlah Penjualan',
                'data' => $totals,
            ],
        ])->setColors(['#4f4f']);
        
        $barChart = (new LarapexChart)->setType('bar')
            ->setTitle('Jumlah Penjualan')
            ->setXAxis($dates)
            ->setDataset([
                [
                    'name' => 'Jumlah Penjualan',
                    'data' => $totals,
                ],
            ])
            ->setColors(['#4FADF7']);

        // 🔹 Pie chart produk terjual
        $productSales = order_detail::whereBetween('created_at', [$startOfMonth, $endOfMonth])
            ->with('product')
            ->get()
            ->groupBy('product.name')
            ->map(function ($items) {
                return $items->sum('qty');
            });

        $pieChart = (new LarapexChart)->setType('pie')
            ->setTitle('Persentase Penjualan Produk')
            ->setLabels($productSales->keys()->toArray())
            ->setDataset($productSales->values()->toArray());

        return view('dashboard', compact('barChart', 'pieChart', 'ordersToday'));
    }
    

    public function index()
    {
        $users = User::orderBy('id', 'DESC')->get();

        return view('user.index', compact('users'));
    }

    public function exportUser()
    {

        return Excel::download(new UserExport, "User.xlsx");
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
