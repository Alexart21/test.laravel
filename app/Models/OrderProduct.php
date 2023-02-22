<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;
use App\Models\Order;

class OrderProduct extends Model
{
    use HasFactory;

    protected $table = 'order_products';

    protected $guarded = [];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
