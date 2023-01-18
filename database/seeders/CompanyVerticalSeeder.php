<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CompanyVerticalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('company_verticals')->insert([
            [
                'uuid' => Str::uuid(),
                'name' => 'Sale Marketing',
                'icon' => 'icon1',
                'bussines_category_id' => 1,
                'created_at' => now(),
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Inboud Call Center',
                'icon' => 'icon2',
                'bussines_category_id' => 1,
                'created_at' => now(),
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Campaigns',
                'icon' => 'icon2',
                'bussines_category_id' => 1,
                'created_at' => now(),
            ],
        ]);
    }
}
