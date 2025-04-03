<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ZonaSeeder extends Seeder
{
    public function run()
    {
        DB::table('zonas')->insert([
            ['nombre' => 'Terraza'],
            ['nombre' => 'Salón Principal'],
            ['nombre' => 'VIP'],
        ]);
    }
}
