<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DropdownTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('dropdown_types')->insert([
            ['uuid' => Str::uuid(), 'name' => 'agent','is_active' => true],
            ['uuid' => Str::uuid(), 'name' => 'publisher website', 'is_active' => false],
            ['uuid' => Str::uuid(), 'name' => 'publisher onboarding interest', 'is_active' => true],
            ['uuid' => Str::uuid(), 'name' => 'agent bonus type', 'is_active' => true],
            ['uuid' => Str::uuid(), 'name' => 'invoice terms', 'is_active' => true],
            ['uuid' => Str::uuid(), 'name' => 'publisher payout_on', 'is_active' => true],
            ['uuid' => Str::uuid(), 'name' => 'agent payout_on', 'is_active' => true],
            ['uuid' => Str::uuid(), 'name' => 'campaign_registration_working_state', 'is_active' => true],
            ['uuid' => Str::uuid(), 'name' => 'business_scale_type', 'is_active' => true],
            ['uuid' => Str::uuid(), 'name' => 'work_type_with_cloudrep', 'is_active' => true],
            ['uuid' => Str::uuid(), 'name' => 'dial_recording_setting', 'is_active' => true],
        ]);
    }
}
