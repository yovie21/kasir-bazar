<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Uom extends Model
{
    use HasFactory;

    protected $table = 'uoms';
    protected $primaryKey = 'uomId';   // ✅ konsisten
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'uomKode',
        'uomName',
        'konvPcs',
    ];

    /** Relasi ke produk (satu UOM bisa dipakai banyak produk) */
    public function products()
    {
        return $this->hasMany(Product::class, 'uomId', 'uomId');
    }

    /** Relasi ke sales item */
    public function salesItems()
    {
        return $this->hasMany(SalesItem::class, 'uomId', 'uomId');
    }
}
