<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $fillable = [
        'cashier_id',
        'no_trans',
        'subtotal_cents',
        'discount_cents',
        'total_cents',
        'paid_cents',
        'change_cents',
    ];

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function items()
    {
        return $this->hasMany(SalesItem::class, 'sale_id');
    }

    // ✅ Accessors biar langsung rupiah
    public function getTotalAttribute()
    {
        return $this->total_cents / 100;
    }

    public function getPaidAttribute()
    {
        return $this->paid_cents / 100;
    }

    public function getChangeAttribute()
    {
        return $this->change_cents / 100;
    }
}
