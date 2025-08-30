<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'uomId',           // foreign key ke master uom
        'barcode',
        'sku',
        'price_cents',
        'stock_warehouse',
    ];

    public function uom()
    {
         return $this->belongsTo(Uom::class, 'uomId', 'uomId');
    }
}