<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UomSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('uoms')->insert([
            ['uomKode' => 'KG', 'uomName' => 'Kilogram', 'konvPcs' => 1],
            ['uomKode' => 'G', 'uomName' => 'Gram', 'konvPcs' => 1],
            ['uomKode' => 'L', 'uomName' => 'Liter', 'konvPcs' => 1],
            ['uomKode' => 'ML', 'uomName' => 'Mililiter', 'konvPcs' => 1],
            ['uomKode' => 'Dus', 'uomName' => 'Dus', 'konvPcs' => 12],
            ['uomKode' => 'Slp', 'uomName' => 'Slop', 'konvPcs' => 10],
            ['uomKode' => 'Sch', 'uomName' => 'Sachet', 'konvPcs' => 1],
            ['uomKode' => 'Bal', 'uomName' => 'Bal', 'konvPcs' => 40],
            ['uomKode' => 'Mtr', 'uomName' => 'Meter', 'konvPcs' => 1],
            ['uomKode' => 'Box', 'uomName' => 'Box', 'konvPcs' => 20],
            ['uomKode' => 'Roll', 'uomName' => 'Roll', 'konvPcs' => 1],
            ['uomKode' => 'Set', 'uomName' => 'Set', 'konvPcs' => 1],
        ]);
    }
}
