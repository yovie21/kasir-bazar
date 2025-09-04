<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products'; // pastikan sesuai nama tabel
    protected $primaryKey = 'id';  // default Laravel (kalau bukan, sesuaikan)

    protected $fillable = [
        'name',
        'uomId',           // foreign key ke master uom
        'barcode',
        'sku',
        'price_cents',
        'stock_warehouse',
    ];

    /**
     * Relasi ke UOM (satuan utama)
     */
    public function uom()
    {
        return $this->belongsTo(Uom::class, 'uomId', 'uomId');
    }

    /**
     * Relasi ke daftar harga berdasarkan UOM
     */
    public function uomPrices()
    {
        return $this->hasMany(ProductUomPrice::class, 'product_id', 'id');
    }

    /**
     * Relasi ke harga dasar (base UOM)
     */
    public function baseUomPrice()
    {
        return $this->hasOne(ProductUomPrice::class, 'product_id', 'id')
                    ->where('is_base', true);
    }

    /**
     * Mendapatkan harga berdasarkan UOM tertentu
     */
    public function getPriceByUom($uomId)
    {
        $price = $this->uomPrices()
            ->where('uom_id', $uomId)
            ->first();

        return $price ? $price->price_cents : 0;
    }

    /**
     * Helper: tampilkan nama UOM dengan aman
     */
    public function getUomNameAttribute()
    {
        return $this->uom ? $this->uom->uomName : '-';
    }
}
