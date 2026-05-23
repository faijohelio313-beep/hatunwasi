<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Combo extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Relación muchos a muchos con Product.
     */
    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('tipo_uso')->withTimestamps();
    }
}
