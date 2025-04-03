<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MesaSeeder extends Seeder
{
    public function run()
    {
        DB::table('mesas')->insert([
            ['num_sillas' => 4, 'id_zona' => 1],
            ['num_sillas' => 6, 'id_zona' => 2],
            ['num_sillas' => 2, 'id_zona' => 3],
        ]);
    }
}
