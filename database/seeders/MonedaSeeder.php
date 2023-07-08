<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MonedaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('moneda')->insert([
            'Mon_TipoMoneda' => '0',
            'Mon_Descripcion' => '$',
        ]);
        DB::table('moneda')->insert([
            'Mon_TipoMoneda' => '1',
            'Mon_Descripcion' => 'S/',
        ]);
    }
}
