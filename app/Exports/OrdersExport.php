<?php
namespace App\Exports;

use App\Models\Order;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrdersExport implements FromCollection, WithHeadings, WithMapping
{
    protected $type, $month, $year;

    public function __construct($type, $month = null, $year = null)
    {
        $this->type = $type;
        $this->month = $month;
        $this->year = $year;

    }

    public function collection()
    {
        $query = Order::with(['customer.member', 'order_details.product']);

        if ($this->type == 'daily') {
            $query->whereDate('created_at', Carbon::now());
        } elseif ($this->type == 'monthly') {
            $query->whereYear('created_at', $this->year)
                  ->whereMonth('created_at', $this->month);
        } elseif ($this->type == 'year') {
            $query->whereYear('created_at', $this->year);
        }

        return $query->get();
    }

    public function headings(): array
    {
        return ['Nama', 'No telp', 'Produk', 'Tanggal Penjualan', 'Points', 'Total Bayar', 'Total Price', 'Points Digunakan', 'Penanggung Jawab'];
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
