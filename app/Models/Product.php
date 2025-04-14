<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'stock',
        'price',
        'image',
    ];

    public function cart(){
        return $this->hasMany(Cart::class);
    }

    public function order_details(){
        return $this->hasMany(Order_detail::class);
    }
}
