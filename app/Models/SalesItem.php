<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesItem extends Model
{
    use HasFactory;

    protected $table = 'sales_item';

    protected $fillable = [
        'sale_id',
        'product_id',
        'qty',
        'price_cents',
    ];

    public function sale()
    {
        return $this->belongsTo(Sale::class, 'sale_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    // subtotal otomatis
    public function getSubtotalAttribute()
    {
        return $this->qty * ($this->price_cents / 100);
    }
}
