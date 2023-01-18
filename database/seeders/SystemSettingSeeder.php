<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SystemSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('system_settings')->insert([
            ['setting_uuid' => Str::uuid(), 'name' => 'company name', 'value' => 'cloudrepai', 'created_at' => now()],
            ['setting_uuid' => Str::uuid(), 'name' => 'company address', 'value' => 'canada', 'created_at' => now(),],
        ]);
    }
}
