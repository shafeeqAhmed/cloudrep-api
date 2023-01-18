<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('services')->insert([
            [
                'service_uuid' => Str::uuid(),
                'name' => 'Leads',
                'icon' => 'https://cloudrepbucket.s3.amazonaws.com/uploads/services/CPy7fBRAGEAmU070N3mmLbuC1hEf22UW5nO8ohFE.png',
                'image' => 'https://cloudrepbucket.s3.amazonaws.com/uploads/services/XDGv8teuDXzWFiWlWoUCJtYvubBt8PJFOYE1jmWh.png',
                'created_at' => now(),
            ],
            [
                'service_uuid' => Str::uuid(),
                'name' => 'Remote Sales Representatives',
                'icon' => 'https://cloudrepbucket.s3.amazonaws.com/uploads/services/g1269i241eQNxTruGlcr0sRIBhT2opeFPasTwz8p.png',
                'image' => 'https://cloudrepbucket.s3.amazonaws.com/uploads/services/jr82J2a31axFCnfQwqnf310uXg2T07y2kH8u9KSE.png',
                'created_at' => now(),
            ],
            [
                'service_uuid' => Str::uuid(),
                'name' => 'Sales (eCommerce)',
                'icon' => 'https://cloudrepbucket.s3.amazonaws.com/uploads/services/sgsj5gfNpUBj2LkkTEtKm6nia1sBVaL2R2FILGPc.png',
                'image' => 'https://cloudrepbucket.s3.amazonaws.com/uploads/services/K9i9TyobJqia0JmFhJ9HZYV3LbiBNBjwGlparGKl.png',
                'created_at' => now(),
            ],
            [
                'service_uuid' => Str::uuid(),
                'name' => 'Remote Services',
                'icon' => 'https://cloudrepbucket.s3.amazonaws.com/uploads/services/QBEyCMHKwj070QtcFkr1umI1mWPhzq8dDH0Oa9Hw.png',
                'image' => 'https://cloudrepbucket.s3.amazonaws.com/uploads/services/GB5P2iI50lUP11zPgUcJZ5Y1GWl4lTht29qq2AKO.png',
                'created_at' => now(),
            ],
            [
                'service_uuid' => Str::uuid(),
                'name' => 'Analytics',
                'icon' => 'https://cloudrepbucket.s3.amazonaws.com/uploads/services/jFXJ7BWDT2zkgzWWDlFWuFsQjjTev1JmUrcx7qau.png',
                'image' => 'https://cloudrepbucket.s3.amazonaws.com/uploads/services/iEXgdzf2h9q8yH4plh8BEiAnRRTZWqivaWtaAS5k.png',
                'created_at' => now(),
            ],
            [
                'service_uuid' => Str::uuid(),
                'name' => 'Installs (Downloads)',
                'icon' => 'https://cloudrepbucket.s3.amazonaws.com/uploads/services/CPy7fBRAGEAmU070N3mmLbuC1hEf22UW5nO8ohFE.png',
                'image' => 'https://cloudrepbucket.s3.amazonaws.com/uploads/services/2MXp9I9Wl3znrfArK6TkEsdasazsckjUuAX04s8R.png',
                'created_at' => now(),
            ],


        ]);
    }
}
