<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\OrderProduct;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'phone',
        'email',
        'address',
        'total'
    ];

    public function orderProducts()
    {
        return $this->hasMany(OrderProduct::class);
    }
}
