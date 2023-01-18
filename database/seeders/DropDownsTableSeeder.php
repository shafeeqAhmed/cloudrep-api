<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class DropDownsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('dropdowns')->insert([
            [
                'uuid' => Str::uuid(),
                'label' => 'Ads',
                'value' => 'ads',
                'type' => 'agent',
                'created_at' => now()
            ],
            [
                'uuid' => Str::uuid(),
                'label' => 'Landing Page',
                'value' => 'landing page',
                'type' => 'agent',
                'created_at' => now()
            ],
            [
                'uuid' => Str::uuid(),
                'label' => 'Organic Search',
                'value' => 'organic search',
                'type' => 'agent',
                'created_at' => now()
            ],
            [
                'uuid' => Str::uuid(),
                'label' => 'My Own',
                'value' => 'my own',
                'type' => 'publisher website',
                'created_at' => now()
            ],
            [
                'uuid' => Str::uuid(),
                'label' => 'Other',
                'value' => 'other',
                'type' => 'publisher website',
                'created_at' => now()
            ],
            [
                'uuid' => Str::uuid(),
                'label' => 'Client Website',
                'value' => 'client website',
                'type' => 'publisher website',
                'created_at' => now()
            ],
            [
                'uuid' => Str::uuid(),
                'label' => 'Ads',
                'value' => 'ads',
                'type' => 'publisher onboarding interest',
                'created_at' => now()
            ],
            [
                'uuid' => Str::uuid(),
                'label' => 'Search Ranking',
                'value' => 'search ranking',
                'type' => 'publisher onboarding interest',
                'created_at' => now()
            ],
            [
                'uuid' => Str::uuid(),
                'label' => 'Lorem Ipsum',
                'value' => 'lorem ipsum',
                'type' => 'publisher onboarding interest',
                'created_at' => now()
            ],
            [
                'uuid' => Str::uuid(),
                'label' => 'Organic Search',
                'value' => 'organic search',
                'type' => 'publisher onboarding interest',
                'created_at' => now()
            ],
            [
                'uuid' => Str::uuid(),
                'label' => 'Annual',
                'value' => 'annual',
                'type' => 'agent bonus type',
                'created_at' => now()
            ],
            [
                'uuid' => Str::uuid(),
                'label' => 'Sales',
                'value' => 'sales',
                'type' => 'agent bonus type',
                'created_at' => now()
            ],
            [
                'uuid' => Str::uuid(),
                'label' => 'Performance',
                'value' => 'performance',
                'type' => 'agent bonus type',
                'created_at' => now()
            ],
            [
                'uuid' => Str::uuid(),
                'label' => 'Dependability',
                'value' => 'dependability',
                'type' => 'agent bonus type',
                'created_at' => now()
            ],
            [
                'uuid' => Str::uuid(),
                'label' => 'None',
                'value' => 'none',
                'type' => 'invoice terms',
                'created_at' => now()
            ],
            [
                'uuid' => Str::uuid(),
                'label' => 'Custom',
                'value' => 'custom',
                'type' => 'invoice terms',
                'created_at' => now()
            ],
            [
                'uuid' => Str::uuid(),
                'label' => '7 Days',
                'value' => '7 days',
                'type' => 'invoice terms',
                'created_at' => now()
            ],
            [
                'uuid' => Str::uuid(),
                'label' => 'Call Length',
                'value' => 'call length',
                'type' =>  'publisher payout_on',
                'created_at' => now()
            ],
            [
                'uuid' => Str::uuid(),
                'label' => 'Converted Call',
                'value' => 'converted call',
                'type' =>  'agent payout_on',
                'created_at' => now()
            ],
            [
                'uuid' => Str::uuid(),
                'label' => 'Online',
                'value' => 'online',
                'type' => 'campaign_registration_working_state',
                'created_at' => now()
            ],
            [
                'uuid' => Str::uuid(),
                'label' => 'Offline',
                'value' => 'offline',
                'type' => 'campaign_registration_working_state',
                'created_at' => now()
            ],
            [
                'uuid' => Str::uuid(),
                'label' => 'On Break',
                'value' => 'on break',
                'type' => 'campaign_registration_working_state',
                'create_at' => now()
            ],
            [
                'uuid' => Str::uuid(),
                'label' => 'Earning an income',
                'value' => 'earning an income',
                'type' => 'business_scale_type',
                'create_at' => now()
            ],
            [
                'uuid' => Str::uuid(),
                'label' => 'Scale My business',
                'value' => 'scale my business',
                'type' => 'business_scale_type',
                'create_at' => now()
            ],
            [
                'uuid' => Str::uuid(),
                'label' => 'Start My business',
                'value' => 'start my business',
                'type' => 'business_scale_type',
                'create_at' => now()
            ],
            [
                'uuid' => Str::uuid(),
                'label' => 'All The Options',
                'value' => 'all the options',
                'type' => 'business_scale_type',
                'create_at' => now()
            ],
            [
                'uuid' => Str::uuid(),
                'label' => 'Remote Support Representative',
                'value' => 'remote support representative',
                'type' => 'work_type_with_cloudrep',
                'create_at' => now()
            ],
            [
                'uuid' => Str::uuid(),
                'label' => 'Remote Sales Representative',
                'value' => 'remote sales representative',
                'type' => 'work_type_with_cloudrep',
                'create_at' => now()
            ],
            [
                'uuid' => Str::uuid(),
                'label' => 'Lead ( Form Submits )',
                'value' => 'lead ( form submits )',
                'type' => 'work_type_with_cloudrep',
                'create_at' => now()
            ],
            [
                'uuid' => Str::uuid(),
                'label' => 'Sales (E-Commerce)',
                'value' => 'sales (e-commerce)',
                'type' => 'work_type_with_cloudrep',
                'create_at' => now()
            ],
            [
                'uuid' => Str::uuid(),
                'label' => 'Off',
                'value' => 'off',
                'type' => 'dial_recording_setting',
                'created_at' => now()
            ],
            [
                'uuid' => Str::uuid(),
                'label' => 'On Answer',
                'value' => 'on answer',
                'type' => 'dial_recording_setting',
                'created_at' => now()
            ],
            [
                'uuid' => Str::uuid(),
                'label' => 'Entire Call',
                'value' => 'entire call',
                'type' => 'dial_recording_setting',
                'created_at' => now()
            ]
        ]);
    }
}
