<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesItem extends Model
{
    use HasFactory;

    protected $table = 'sales_item'; // ✅ sesuai tabel
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'sale_id',
        'product_id',
        'uomId',         // konsisten dengan Uom & Product
        'qty',
        'price_cents',
        'subtotal_cents',
    ];

    /** Relasi ke Sale */
    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id', 'id');
    }

    /** Relasi ke Product */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }

    /** Relasi ke UOM (satuan) */
    public function uom()
    {
        return $this->belongsTo(Uom::class, 'uomId', 'uomId');
    }

    /** Accessor subtotal (angka murni) */
    public function getSubtotalAttribute()
    {
        return $this->subtotal_cents;
    }
}
    