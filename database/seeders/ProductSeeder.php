<?php  

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // Data produk (50 item)
        $products = [
            // ======== SEMBAKO ========
            ['uomId' => 1,  'barcode' => '8991002100011', 'name' => 'Beras Ramos 5kg', 'price_cents' => 65000, 'stock_warehouse' => 120],
            ['uomId' => 1,  'barcode' => '8991002100028', 'name' => 'Beras Premium 10kg', 'price_cents' => 130000, 'stock_warehouse' => 80],
            ['uomId' => 1,  'barcode' => '8991002100035', 'name' => 'Gula Pasir 1kg', 'price_cents' => 15500, 'stock_warehouse' => 200],
            ['uomId' => 1,  'barcode' => '8991002100042', 'name' => 'Tepung Terigu 1kg', 'price_cents' => 13500, 'stock_warehouse' => 180],
            ['uomId' => 3,  'barcode' => '8991002100059', 'name' => 'Minyak Goreng 1L', 'price_cents' => 18500, 'stock_warehouse' => 150],
            ['uomId' => 2,  'barcode' => '8991002100066', 'name' => 'Garam Halus 500g', 'price_cents' => 7000, 'stock_warehouse' => 250],
            ['uomId' => 10, 'barcode' => '8991002100073', 'name' => 'Kecap Manis Box 12 Botol', 'price_cents' => 210000, 'stock_warehouse' => 75],
            ['uomId' => 10, 'barcode' => '8991002100080', 'name' => 'Saus Tomat Box 12 Botol', 'price_cents' => 150000, 'stock_warehouse' => 100],
            ['uomId' => 7,  'barcode' => '8991002100097', 'name' => 'Sambal Sachet 24s', 'price_cents' => 16000, 'stock_warehouse' => 110],
            ['uomId' => 4,  'barcode' => '8991002100103', 'name' => 'Cuka Masak 100ml', 'price_cents' => 6000, 'stock_warehouse' => 140],

            // ======== MINUMAN =========
            ['uomId' => 4,  'barcode' => '8991002100110', 'name' => 'Teh Botol 450ml', 'price_cents' => 6000, 'stock_warehouse' => 200],
            ['uomId' => 4,  'barcode' => '8991002100127', 'name' => 'Air Mineral 600ml', 'price_cents' => 5000, 'stock_warehouse' => 500],
            ['uomId' => 5,  'barcode' => '8991002100134', 'name' => 'Air Mineral Dus 24 Botol', 'price_cents' => 60000, 'stock_warehouse' => 90],
            ['uomId' => 5,  'barcode' => '8991002100141', 'name' => 'Susu Kotak Dus 12x1L', 'price_cents' => 165000, 'stock_warehouse' => 40],
            ['uomId' => 4,  'barcode' => '8991002100158', 'name' => 'Jus Jeruk 350ml', 'price_cents' => 12000, 'stock_warehouse' => 120],
            ['uomId' => 3,  'barcode' => '8991002100165', 'name' => 'Soda Botol 1L', 'price_cents' => 15000, 'stock_warehouse' => 90],
            ['uomId' => 4,  'barcode' => '8991002100172', 'name' => 'Sirup Marjan 650ml', 'price_cents' => 18000, 'stock_warehouse' => 130],
            ['uomId' => 6,  'barcode' => '8991002100189', 'name' => 'Minuman Energi Slop 24 Kaleng', 'price_cents' => 216000, 'stock_warehouse' => 160],
            ['uomId' => 7,  'barcode' => '8991002100196', 'name' => 'Kopi Sachet 10s', 'price_cents' => 15000, 'stock_warehouse' => 100],
            ['uomId' => 7,  'barcode' => '8991002100202', 'name' => 'Teh Celup 25s', 'price_cents' => 12000, 'stock_warehouse' => 90],

            // ======== SNACK =========
            ['uomId' => 7,  'barcode' => '8991002100219', 'name' => 'Keripik Singkong Sachet', 'price_cents' => 7500, 'stock_warehouse' => 300],
            ['uomId' => 7,  'barcode' => '8991002100226', 'name' => 'Keripik Kentang Sachet', 'price_cents' => 9500, 'stock_warehouse' => 250],
            ['uomId' => 5,  'barcode' => '8991002100233', 'name' => 'Biskuit Dus 454g', 'price_cents' => 45000, 'stock_warehouse' => 60],
            ['uomId' => 5,  'barcode' => '8991002100240', 'name' => 'Mie Instan Dus 40pcs', 'price_cents' => 115000, 'stock_warehouse' => 60],
            ['uomId' => 5,  'barcode' => '8991002100257', 'name' => 'Wafer Dus 20 Pack', 'price_cents' => 98000, 'stock_warehouse' => 75],
            ['uomId' => 10, 'barcode' => '8991002100264', 'name' => 'Coklat Batang Box 12pcs', 'price_cents' => 65000, 'stock_warehouse' => 80],

            // ======== ROKOK =========
            ['uomId' => 6,  'barcode' => '8991002100271', 'name' => 'Rokok Marlboro Slop', 'price_cents' => 320000, 'stock_warehouse' => 50],
            ['uomId' => 6,  'barcode' => '8991002100288', 'name' => 'Rokok Djarum Super Slop', 'price_cents' => 280000, 'stock_warehouse' => 65],
            ['uomId' => 6,  'barcode' => '8991002100295', 'name' => 'Rokok Sampoerna A Slop', 'price_cents' => 295000, 'stock_warehouse' => 70],
            ['uomId' => 6,  'barcode' => '8991002100301', 'name' => 'Rokok Surya Slop', 'price_cents' => 275000, 'stock_warehouse' => 60],
            ['uomId' => 6,  'barcode' => '8991002100318', 'name' => 'Rokok LA Lights Slop', 'price_cents' => 285000, 'stock_warehouse' => 55],

            // ======== KEBUTUHAN RUMAH =========
            ['uomId' => 11, 'barcode' => '8991002100325', 'name' => 'Tisu Roll', 'price_cents' => 25000, 'stock_warehouse' => 180],
            ['uomId' => 10, 'barcode' => '8991002100332', 'name' => 'Sabun Mandi Box 12pcs', 'price_cents' => 95000, 'stock_warehouse' => 70],
            ['uomId' => 10, 'barcode' => '8991002100349', 'name' => 'Shampoo Sachet Box 12pcs', 'price_cents' => 85000, 'stock_warehouse' => 65],
            ['uomId' => 8,  'barcode' => '8991002100356', 'name' => 'Popok Bayi Bal 40pcs', 'price_cents' => 350000, 'stock_warehouse' => 30],
            ['uomId' => 9,  'barcode' => '8991002100363', 'name' => 'Kabel Listrik per Meter', 'price_cents' => 12000, 'stock_warehouse' => 400],
            ['uomId' => 12, 'barcode' => '8991002100370', 'name' => 'Panci Set', 'price_cents' => 250000, 'stock_warehouse' => 20],
            ['uomId' => 12, 'barcode' => '8991002100387', 'name' => 'Wajan Set', 'price_cents' => 175000, 'stock_warehouse' => 25],

            // ======== KOSMETIK =========
            ['uomId' => 4,  'barcode' => '8991002100394', 'name' => 'Bedak Tabur Botol', 'price_cents' => 22000, 'stock_warehouse' => 150],
            ['uomId' => 7,  'barcode' => '8991002100400', 'name' => 'Sampo Sachet', 'price_cents' => 2500, 'stock_warehouse' => 600],
            ['uomId' => 7,  'barcode' => '8991002100417', 'name' => 'Pasta Gigi Sachet', 'price_cents' => 2000, 'stock_warehouse' => 500],
            ['uomId' => 4,  'barcode' => '8991002100424', 'name' => 'Deodorant Botol', 'price_cents' => 25000, 'stock_warehouse' => 120],
            ['uomId' => 10, 'barcode' => '8991002100431', 'name' => 'Masker Wajah Box 10pcs', 'price_cents' => 50000, 'stock_warehouse' => 100],
        ];

        // Insert dengan SKU 6 digit unik
        foreach ($products as $p) {
            DB::table('products')->updateOrInsert(
                ['barcode' => $p['barcode']], // kondisi unik
                array_merge($p, [
                    'sku' => str_pad(mt_rand(1, 999999), 6, '0', STR_PAD_LEFT),
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }
    }
}
