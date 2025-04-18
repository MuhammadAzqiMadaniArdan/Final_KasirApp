<?php

namespace App\Http\Controllers;

use App\Exports\OrdersExport;
use App\Exports\OrdersExportMonth;
use App\Exports\OrdersExportYear;
use App\Models\Cart;
use App\Models\Customer;
use App\Models\Member;
use App\Models\Order;
use App\Models\Order_detail;
use App\Models\Product;
use Carbon\Carbon;
use PDF;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Excel;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $limit = $request->input('limit', 10);
        $date = $request->input('date', 'daily');
        $month = $request->input('month', 1);
        $year = $request->input('year', now()->year);
        $today = Carbon::today();
        $startOfMonth = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endOfMonth = Carbon::createFromDate($year, $month, 31)->endOfMonth();
        $startOfYear = Carbon::createFromDate($year, 1, 1)->startOfYear();
        $endOfYear = Carbon::createFromDate($year, 12, 31)->endOfYear();
        
        $months = [
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        ];
        // $monthy = Carbon::create()->month(1);
        // dd(Order::whereBetween('created_at',)->get(),now()->year,$year);
        if ($date == 'daily') {
            $orders = Order::whereDate('created_at', $today)->orderBy('id', 'DESC')->paginate($limit);
        }elseif($date == 'monthly'){
            $orders = Order::whereBetween('created_at', [$startOfMonth,$endOfMonth])->WhereBetween('created_at',[$startOfYear,$endOfYear])->orderBy('id', 'DESC')->paginate($limit);
        }else{
            $orders = Order::whereBetween('created_at', [$startOfYear,$endOfYear])->orderBy('id', 'DESC')->paginate($limit);
        }
        return view('order.index', compact('orders','date','months','year','month','limit'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function cart()
    {
        $products = Product::orderBy('id', 'ASC')->get();
        return view('order.cart', compact('products'));
    }

    public function create()
    {
        $carts = Cart::where('user_id', Auth::user()->id)->orderBy('id', 'ASC')->get();

        return view('order.create', compact('carts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function cartPost(Request $request)
    {
        $carts = Cart::where('user_id', Auth::user()->id)->get();

        if ($carts->count() > 0) {
            foreach ($carts as $cart) {
                $cart->delete();
            }
        }

        try {

            foreach ($request->qty as $index => $qty) {
                Cart::create([
                    "user_id" => Auth::user()->id,
                    "product_id" => $request->id[$index],
                    'qty' => $qty
                ]);
            }
        } catch (\Exception $e) {
            return redirect()->route('order.index')->with('failed', 'Gagal Menambah Order');
        }

        return redirect()->route('order.create')->with('success', 'Data Cart Berhasil ditambahkan');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "cost" => "required|numeric|min:0|max:99999999999999999999",
            "total_price" => "required|numeric|min:0",
            "phone_number" => "max:13",
            "status" => "required|string|in:member,non-member",
            "products" => "required|array",
            "products.*.product_id" => "required|exists:products,id",
            "products.*.qty" => "required|min:0",
            "products.*.price" => "required|min:0",
        ]);

        if ($validator->fails()) {
            return redirect()->route('order.index')->with('failed', $validator->errors());
        }


        $validated = $validator->validated();

        // dd($validator->errors());
        DB::beginTransaction();

        try {
            if ($validated['phone_number'] != null || $validated['status'] == "member") {
                $hasMember = Member::where('phone_number', $validated['phone_number'])->first();

                $points = $validated['total_price'] * 0.01;
                if ($hasMember) {
                    $member = $hasMember;
                    $customer = Customer::where('id', $member['customer_id'])->first();
                    $customer->total_payment = $validated['cost'];
                    $customer->save();

                    $member->points += $points;
                    $member->save();
                } else {
                    $customer = Customer::create([
                        "status" => $validated['status'],
                        "total_payment" => $validated['cost']
                    ]);
                    $member = Member::create([
                        "customer_id" => $customer['id'],
                        "name" => null,
                        "phone_number" => $validated['phone_number'],
                        "points" => $points,
                        "status" => "new"
                    ]);
                }
                DB::commit();

                return redirect()->route('order.member', ['id' => $member->id])->with('success', 'Berhasil Menambahkan Member');
            }

            $customer = Customer::create([
                "status" => $validated['status'],
                "total_payment" => $validated['cost']
            ]);

            $order = Order::create([
                "customer_id" => $customer['id'],
                "user_id" => Auth::user()->id,
                "invoice" => 0,
                "total_price" => $validated['total_price'],
                "cost" => $validated['cost'],
                "point_used" => 0,
            ]);

            $order->invoice = $order['id'];
            $order->save();

            foreach ($validated['products'] as $product) {
                Order_detail::create([
                    "order_id" => $order['id'],
                    "product_id" => $product['product_id'],
                    "qty" => $product['qty'],
                ]);

                $myProduct = Product::where('id', $product['product_id'])->first();
                $myProduct['stock'] = $myProduct['stock'] - $product['qty'];
                $myProduct->save();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return redirect()->route('order.index')->with('failed', $e->getMessage());
        }

        return redirect()->route('order.receipt', ['id' => $order->id])->with('success', 'Berhasil Menambahkan Order');
    }

    /**
     * Display the specified resource.
     */
    public function receipt($id)
    {
        $order = Order::where('id', $id)->first();
        // dd($order);
        return view('order.receipt', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function downloadPdf($id)
    {
        $order = Order::with('customer', 'customer.member', 'order_details.product', 'user')->findOrFail($id)->toArray();

        // dd($order);
        view()->share('order', $order);

        $pdf = PDF::loadView('order.download-pdf', $order);

        return $pdf->download('receipt.pdf');
    }

    public function exportExcel(Request $request)
    {
        $type = $request->type;    // 'daily', 'monthly', 'yearly'
        $month = $request->month;  // 1-12 atau null
        $year = $request->year;    // misal 2024
    
        return Excel::download(
            new OrdersExport($type, $month, $year),
            "Laporan-Penjualan.xlsx"
        );
    }
    
    public function exportExcelMonthly(Request $request)
    {
        $month = $request->month;
        $year = $request->year;
    
        return Excel::download(
            new OrdersExportMonth($month, $year),
            "pembelian-bulanan-{$month}-{$year}.xlsx"
        );
    }
    
    public function exportExcelYear()
    {
        $file_name = "data pembelian" . ".xslx";

        return Excel::download(new OrdersExportYear, "pembelian Tahunan.xlsx");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        //
    }
}
