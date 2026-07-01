<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'combo_id',
        'combo_nombre',
        'cantidad',
        'precio_unitario',
        'subtotal',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function combo()
    {
        return $this->belongsTo(Combo::class);
    }
}
