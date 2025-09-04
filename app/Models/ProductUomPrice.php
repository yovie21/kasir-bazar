<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductUomPrice extends Model
{
    protected $fillable = [
        'product_id',
        'uom_id',
        'price_cents',
        'konv_to_base',
        'is_base',
        'stock', 
    ];

    // Relasi ke Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Relasi ke Uom
    public function uom()
    {
        return $this->belongsTo(Uom::class, 'uom_id', 'uomId');
    }

    // Method untuk konversi ke base UOM
    public function convertToBase($quantity)
    {
        return $quantity * $this->konv_to_base;
    }

    // Method untuk konversi dari base UOM
    public function convertFromBase($baseQuantity)
    {
        return $baseQuantity / $this->konv_to_base;
    }
}