<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            ['name' => 'admin', 'guard_name' => 'web', 'created_at' => now()],
            ['name' => 'client', 'guard_name' => 'web', 'created_at' => now()],
            ['name' => 'publisher', 'guard_name' => 'web', 'created_at' => now()],
            ['name' => 'agent', 'guard_name' => 'web', 'created_at' => now()],
        ]);
    }
}
