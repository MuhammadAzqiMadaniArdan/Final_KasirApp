<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'total_payment',
        'status',
    ];

    public function member(){
        return $this->hasOne(Member::class);
    }

    public function order(){
        return $this->hasMany(Order::class);
    }
}
