<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('products')->insert([
            'name' => 'Iphone 10',
            'description' => 'Mobile 2',
            'amount'    => '980'
        ]);
        DB::table('products')->insert([
            'name' => 'Iphone 11',
            'description' => 'Mobile 3',
            'amount'    => '980'
        ]);
        DB::table('products')->insert([
            'name' => 'Iphone 12',
            'description' => 'Mobile 4',
            'amount'    => '980'
        ]);
    }
}
