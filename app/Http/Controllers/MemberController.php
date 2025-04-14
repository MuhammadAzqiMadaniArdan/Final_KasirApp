<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Customer;
use App\Models\Member;
use App\Models\Order;
use App\Models\Order_detail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($id)
    {
        $member = Member::where('id',$id)->first();
        $customer = Customer::where('id',$member['customer_id'])->first();
        $carts = Cart::where('user_id',Auth::user()->id)->get();
        return view('order.member',compact('member','carts','customer'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request,$id)
    {
        $validator = Validator::make($request->all(),[
            "name" => "required|string|max:255",
            "cost" => "required|numeric|min:0",
            "total_price" => "required|numeric|min:0",
            "points_used" => "numeric",
            "points_checked" => "numeric",
            "products" => "required|array",
            "products.*.product_id" => "required|exists:products,id",
            "products.*.qty" => "required|min:0",
            "products.*.price" => "required|min:0",
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('failed', $validator->errors());
        }
        
        $validated = $validator->validated();
        
        DB::beginTransaction();
        try{
            $member = Member::where('id',$id)->first();
            $customer = Customer::where('id',$member['customer_id'])->first();

            $pointsUsed = 0;
            
            if($member->status == "new")
            {
                $member->status = "old";
            }
            if($validated['points_checked'] == true){
                $pointsUsed = $member->points;
                $member->points = 0;
            }
            
            $member->name = $validated['name'];
            
            
            $order = Order::create([
                "customer_id" => $customer['id'],
                "user_id" => Auth::user()->id,
                "invoice" => 0,
                "total_price" => $validated['total_price'],
                "cost" => $validated['cost'],
                "points_used" => $pointsUsed,
            ]);
            
            $order->invoice = $order['id'];
            $order->save();
            
            foreach ($validated['products'] as $product) {
                Order_detail::create([
                    "order_id" => $order['id'],
                    "product_id" => $product['product_id'],
                    "qty" => $product['qty'],
                ]);
                
                $myProduct = Product::where('id',$product['product_id'])->first();
                $myProduct['stock'] = $myProduct['stock'] - $product['qty'];
                $myProduct->save();
            }
            
            $member->save();
            DB::commit();
            // dd("halo",$member['customer_id'],$customer->id);
        }
        catch(\Exception $e){
            DB::rollBack();
            dd($e->getMessage());
            return redirect()->back()->with('failed', $e->getMessage());
        }
        
        
        return redirect()->route('order.receipt',['id' => $order->id])->with('success','Berhasil Menambahkan Order');
    }

    /**
     * Display the specified resource.
     */
    public function show(Member $member)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Member $member)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Member $member)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member)
    {
        //
    }
}
