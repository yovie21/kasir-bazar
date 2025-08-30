<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            // ======== SEMBAKO ========
            ['uomId' => 4,  'barcode' => '8991002100011', 'name' => 'Beras Ramos 5kg', 'sku' => 'BRM5KG', 'price_cents' => 65000, 'stock_warehouse' => 120],
            ['uomId' => 4,  'barcode' => '8991002100028', 'name' => 'Beras Premium 10kg', 'sku' => 'BRP10KG', 'price_cents' => 130000, 'stock_warehouse' => 80],
            ['uomId' => 26, 'barcode' => '8991002100035', 'name' => 'Gula Pasir 1kg', 'sku' => 'GLP1KG', 'price_cents' => 15500, 'stock_warehouse' => 200],
            ['uomId' => 26, 'barcode' => '8991002100042', 'name' => 'Tepung Terigu 1kg', 'sku' => 'TPTR1KG', 'price_cents' => 13500, 'stock_warehouse' => 180],
            ['uomId' => 26, 'barcode' => '8991002100059', 'name' => 'Minyak Goreng 1L', 'sku' => 'MYK1L', 'price_cents' => 18500, 'stock_warehouse' => 150],
            ['uomId' => 26, 'barcode' => '8991002100066', 'name' => 'Garam Halus 500g', 'sku' => 'GRM500', 'price_cents' => 7000, 'stock_warehouse' => 250],
            ['uomId' => 12, 'barcode' => '8991002100073', 'name' => 'Kecap Manis Botol 600ml', 'sku' => 'KCP600', 'price_cents' => 21000, 'stock_warehouse' => 75],
            ['uomId' => 12, 'barcode' => '8991002100080', 'name' => 'Saus Tomat Botol 340ml', 'sku' => 'SST340', 'price_cents' => 15000, 'stock_warehouse' => 100],
            ['uomId' => 12, 'barcode' => '8991002100097', 'name' => 'Sambal Botol 200ml', 'sku' => 'SBL200', 'price_cents' => 16000, 'stock_warehouse' => 110],
            ['uomId' => 12, 'barcode' => '8991002100103', 'name' => 'Cuka Masak Botol 100ml', 'sku' => 'CKM100', 'price_cents' => 6000, 'stock_warehouse' => 140],

            // ======== MINUMAN =========
            ['uomId' => 12, 'barcode' => '8991002100110', 'name' => 'Teh Botol 450ml', 'sku' => 'THB450', 'price_cents' => 6000, 'stock_warehouse' => 200],
            ['uomId' => 12, 'barcode' => '8991002100127', 'name' => 'Air Mineral Botol 600ml', 'sku' => 'AM600', 'price_cents' => 5000, 'stock_warehouse' => 500],
            ['uomId' => 2,  'barcode' => '8991002100134', 'name' => 'Air Mineral Karton 24 Botol', 'sku' => 'AMK24', 'price_cents' => 60000, 'stock_warehouse' => 90],
            ['uomId' => 30, 'barcode' => '8991002100141', 'name' => 'Susu Kotak Dus 12x1L', 'sku' => 'SSK12L', 'price_cents' => 165000, 'stock_warehouse' => 40],
            ['uomId' => 12, 'barcode' => '8991002100158', 'name' => 'Jus Jeruk Botol 350ml', 'sku' => 'JJR350', 'price_cents' => 12000, 'stock_warehouse' => 120],
            ['uomId' => 12, 'barcode' => '8991002100165', 'name' => 'Soda Botol 1L', 'sku' => 'SDB1L', 'price_cents' => 15000, 'stock_warehouse' => 90],
            ['uomId' => 28, 'barcode' => '8991002100172', 'name' => 'Sirup Marjan 650ml', 'sku' => 'SRM650', 'price_cents' => 18000, 'stock_warehouse' => 130],
            ['uomId' => 29, 'barcode' => '8991002100189', 'name' => 'Minuman Energi 250ml', 'sku' => 'MNERG', 'price_cents' => 9000, 'stock_warehouse' => 160],
            ['uomId' => 32, 'barcode' => '8991002100196', 'name' => 'Kopi Sachet 10s', 'sku' => 'KPS10', 'price_cents' => 15000, 'stock_warehouse' => 100],
            ['uomId' => 29, 'barcode' => '8991002100202', 'name' => 'Teh Celup 25s', 'sku' => 'THC25', 'price_cents' => 12000, 'stock_warehouse' => 90],

            // ======== SNACK =========
            ['uomId' => 32, 'barcode' => '8991002100219', 'name' => 'Keripik Singkong Sachet', 'sku' => 'KRSCH', 'price_cents' => 7500, 'stock_warehouse' => 300],
            ['uomId' => 32, 'barcode' => '8991002100226', 'name' => 'Keripik Kentang Sachet', 'sku' => 'KRKTS', 'price_cents' => 9500, 'stock_warehouse' => 250],
            ['uomId' => 32, 'barcode' => '8991002100233', 'name' => 'Biskuit Kaleng 454g', 'sku' => 'BSK454', 'price_cents' => 45000, 'stock_warehouse' => 60],
            ['uomId' => 3,  'barcode' => '8991002100240', 'name' => 'Mie Instan Pack 40s', 'sku' => 'MIP40', 'price_cents' => 115000, 'stock_warehouse' => 60],
            ['uomId' => 2,  'barcode' => '8991002100257', 'name' => 'Wafer Karton 20 Pack', 'sku' => 'WFRK20', 'price_cents' => 98000, 'stock_warehouse' => 75],
            ['uomId' => 35, 'barcode' => '8991002100264', 'name' => 'Coklat Batang Box 12pcs', 'sku' => 'CKB12', 'price_cents' => 65000, 'stock_warehouse' => 80],

            // ======== ROKOK =========
            ['uomId' => 31, 'barcode' => '8991002100271', 'name' => 'Rokok Marlboro Slop', 'sku' => 'RKMAR', 'price_cents' => 320000, 'stock_warehouse' => 50],
            ['uomId' => 31, 'barcode' => '8991002100288', 'name' => 'Rokok Djarum Super Slop', 'sku' => 'RKDJR', 'price_cents' => 280000, 'stock_warehouse' => 65],
            ['uomId' => 31, 'barcode' => '8991002100295', 'name' => 'Rokok Sampoerna A Slop', 'sku' => 'RKSMP', 'price_cents' => 295000, 'stock_warehouse' => 70],
            ['uomId' => 31, 'barcode' => '8991002100301', 'name' => 'Rokok Surya Slop', 'sku' => 'RKSYR', 'price_cents' => 275000, 'stock_warehouse' => 60],
            ['uomId' => 31, 'barcode' => '8991002100318', 'name' => 'Rokok LA Lights Slop', 'sku' => 'RKLA', 'price_cents' => 285000, 'stock_warehouse' => 55],

            // ======== KEBUTUHAN RUMAH =========
            ['uomId' => 36, 'barcode' => '8991002100325', 'name' => 'Tisu Roll', 'sku' => 'TSRL', 'price_cents' => 25000, 'stock_warehouse' => 180],
            ['uomId' => 35, 'barcode' => '8991002100332', 'name' => 'Sabun Mandi Box 12pcs', 'sku' => 'SBM12', 'price_cents' => 95000, 'stock_warehouse' => 70],
            ['uomId' => 35, 'barcode' => '8991002100349', 'name' => 'Shampoo Sachet Box 12pcs', 'sku' => 'SHM12', 'price_cents' => 85000, 'stock_warehouse' => 65],
            ['uomId' => 33, 'barcode' => '8991002100356', 'name' => 'Popok Bayi Bal 40pcs', 'sku' => 'PPKB40', 'price_cents' => 350000, 'stock_warehouse' => 30],
            ['uomId' => 34, 'barcode' => '8991002100363', 'name' => 'Kabel Listrik per Meter', 'sku' => 'KBLMTR', 'price_cents' => 12000, 'stock_warehouse' => 400],
            ['uomId' => 37, 'barcode' => '8991002100370', 'name' => 'Panci Set 3pcs', 'sku' => 'PNCST3', 'price_cents' => 250000, 'stock_warehouse' => 20],
            ['uomId' => 37, 'barcode' => '8991002100387', 'name' => 'Wajan Set 2pcs', 'sku' => 'WJNST2', 'price_cents' => 175000, 'stock_warehouse' => 25],

            // ======== KOSMETIK & PERLENGKAPAN =========
            ['uomId' => 12, 'barcode' => '8991002100394', 'name' => 'Bedak Tabur Botol', 'sku' => 'BDKTB', 'price_cents' => 22000, 'stock_warehouse' => 150],
            ['uomId' => 32, 'barcode' => '8991002100400', 'name' => 'Sampo Sachet', 'sku' => 'SHMSCH', 'price_cents' => 2500, 'stock_warehouse' => 600],
            ['uomId' => 32, 'barcode' => '8991002100417', 'name' => 'Pasta Gigi Sachet', 'sku' => 'PGSCH', 'price_cents' => 2000, 'stock_warehouse' => 500],
            ['uomId' => 12, 'barcode' => '8991002100424', 'name' => 'Deodorant Botol', 'sku' => 'DODBTL', 'price_cents' => 25000, 'stock_warehouse' => 120],
            ['uomId' => 35, 'barcode' => '8991002100431', 'name' => 'Masker Wajah Box 10pcs', 'sku' => 'MSK10', 'price_cents' => 50000, 'stock_warehouse' => 100],

            // ======== dst… sampai total 100 ========
        ];

        foreach ($products as $p) {
            DB::table('products')->insert(array_merge($p, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }
}
