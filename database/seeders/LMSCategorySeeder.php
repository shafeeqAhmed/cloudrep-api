<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class LMSCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('lms_categories')->insert([
            ['lms_category_uuid' => Str::uuid(), 'name' => 'Awareness', 'description' => 'This category is about awareness.', 'is_active' => true, 'parent_id' => 1],
            ['lms_category_uuid' => Str::uuid(), 'name' => 'Educational', 'description' => 'This category is about Educational.', 'is_active' => true, 'parent_id' => 1],
            ['lms_category_uuid' => Str::uuid(), 'name' => 'Workshop', 'description' => 'This category is about Workshop.', 'is_active' => false, 'parent_id' => 2]
        ]);
    }
}
