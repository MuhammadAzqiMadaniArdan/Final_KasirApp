<?php
namespace App\Exports;

use App\Models\Order;
use App\Models\Product;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrdersExportYear implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        $startOfYear = Carbon::now()->startOfYear();
        $endOfYear = Carbon::now()->endOfYear();
        return Order::with(['customer.member', 'order_details','order_details.product'])->whereBetween('created_at',[$startOfYear,$endOfYear])->get();
    }

    public function headings(): array
    {
        return ['Nama', 'No telp','Produk','tanggal_penjualan', 'Points','Total Bayar', 'Total Price','Points yang kepake','penanggung jawab'];
    }

    public function map($order): array
    {
        $isMember = $order->customer->status == "member";
        $name = $isMember ? $order->customer->member->name : "Bukan Member";
        $phoneNumber = $isMember ? $order->customer->member->phone_number : "-";
        $points = $isMember ? $order->customer->member->points : "-";
        $products = $order->order_details->map(function ($item) {
            return $item->product->name . " (qty: " . $item->qty . ")";
        })->implode(', '); 
        $user = $order->user->name;
        
        return [
            $name,
            $phoneNumber,
            $products,
            $order->created_at,
            $points,
            $order->cost,
            $order->total_price,
            $order->points_used,
            $user,
        ];
    }
}
