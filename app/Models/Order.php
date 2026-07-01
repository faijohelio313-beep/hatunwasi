<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'customer_name',
        'customer_phone',
        'customer_email',
        'total',
        'status',
        'notas',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'pendiente'   => 'Pendiente',
            'confirmado'  => 'Confirmado',
            'entregado'   => 'Entregado',
            'cancelado'   => 'Cancelado',
            default       => ucfirst($this->status),
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            'pendiente'  => 'yellow',
            'confirmado' => 'blue',
            'entregado'  => 'green',
            'cancelado'  => 'red',
            default      => 'gray',
        };
    }
}
