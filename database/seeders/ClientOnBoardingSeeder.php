<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ClientOnBoardingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('client_profile_items')->insert([
            ['uuid' => Str::uuid(), 'user_id' => 4,'bussines_name' => 'test client', 'bussines_address' => 'canada','bussines_phone_no' => '+123456789','country' => 'canada','state' => 'Alberta', 'city' => 'toronto', 'zipcode' => 456798, 'google_my_bussines' => 'https://www.google.com','crunchbase' => 'https://www.crunchbase.com','linkedin' => 'https://www.linkedin.com','twitter' => 'https://www.twitter.com']
        ]);
    }
}
