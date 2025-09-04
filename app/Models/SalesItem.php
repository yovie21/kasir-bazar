<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesItem extends Model
{
    use HasFactory;

    protected $table = 'sales_item'; // ✅ wajib karena defaultnya Laravel cari 'sales_items'

    protected $fillable = [
        'sale_id',
        'product_id',
        'qty',
        'price_cents',
        'subtotal_cents',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // accessor subtotal (Rupiah sudah diformat)
    public function getSubtotalAttribute()
    {
        return $this->subtotal_cents / 100;
    }
    public function uom()
{
    return $this->belongsTo(Uom::class, 'uom_id', 'uomId');
}

}
