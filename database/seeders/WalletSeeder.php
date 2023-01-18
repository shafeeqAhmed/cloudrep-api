<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class WalletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('wallets')->insert([
            ['uuid' => Str::uuid(), 'holder_type' => 'App\Models\User',  'holder_id' => 1, 'name' => 'Default Wallet', 'slug' => 'default', 'description' => 'test wallet', 'meta' => '{"currency": "USD"}', 'balance' => 500, 'decimal_places' => 2],
            ['uuid' => Str::uuid(), 'holder_type' => 'App\Models\User', 'holder_id' => 3, 'name' => 'Default Wallet', 'slug' => 'default', 'description' => 'test wallet', 'meta' => '{"currency": "USD"}', 'balance' => 500, 'decimal_places' => 2],
            ['uuid' => Str::uuid(), 'holder_type' => 'App\Models\User', 'holder_id' => 4, 'name' => 'Default Wallet', 'slug' => 'default', 'description' => 'test wallet', 'meta' => '{"currency": "USD"}', 'balance' => 500, 'decimal_places' => 2],
            ['uuid' => Str::uuid(), 'holder_type' => 'App\Models\User', 'holder_id' => 5, 'name' => 'Default Wallet', 'slug' => 'default', 'description' => 'test wallet', 'meta' => '{"currency": "USD"}', 'balance' => 500, 'decimal_places' => 2],
        ]);
    }
}
