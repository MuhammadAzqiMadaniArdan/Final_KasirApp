<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'status',
        'points',
        'phone_number'
    ];

    public function customer(){
        return $this->belongsTo(Customer::class);
    }

}
