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

    // Tambahkan relasi baru untuk UOM Prices
    public function uomPrices()
    {
        return $this->hasMany(ProductUomPrice::class);
    }

    public function baseUomPrice()
    {
        return $this->hasOne(ProductUomPrice::class)->where('is_base', true);
    }

    // Method untuk mendapatkan harga berdasarkan UOM
    public function getPriceByUom($uomId)
    {
        return $this->uomPrices()
            ->where('uom_id', $uomId)
            ->first()
            ?->price_cents ?? 0;
    }
}