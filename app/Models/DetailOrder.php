<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'detail_product_id',
        'color_id',
        'topping_id',
        'quantity',
    ];

    public function detail_product(){
        return $this->belongsTo(DetailProduct::class);
    }

    public function color(){
        return $this->belongsTo(Color::class);
    }

    public function topping(){
        return $this->belongsTo(Topping::class);
    }

    public function order(){
        return $this->belongsTo(Order::class);
    }
}
