<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Contracts\Permission;
use Spatie\Permission\Models\Role;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'user_uuid' => Str::uuid(),
                'worker_sid' => null,
                'name' => 'Admin',
                'first_name' => 'tony',
                'last_name' => 'admin',
                'email' => 'tony@cloudrep.ai',
                'phone_no' => '+13159878456',
                'email_verified_at' => now(),
                'password' => Hash::make(123456789),
                'step' => 6,
                'created_at' => now(),
            ],
            [
                'user_uuid' => Str::uuid(),
                'worker_sid' => null,
                'name' => 'Theo',
                'first_name' => 'Theo',
                'last_name' => 'admin',
                'email' => 'theo@cloudrep.ai',
                'phone_no' => '+131254698',
                'email_verified_at' => now(),
                'password' => Hash::make(123456789),
                'step' => 6,
                'created_at' => now(),
            ],
            [
                'user_uuid' => Str::uuid(),
                'worker_sid' => null,
                'name' => 'Publisher',
                'first_name' => 'test',
                'last_name' => 'publisher',
                'email' => 'publisher@gmail.com',
                'phone_no' => '+13248965414',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'step' => 6,
                'created_at' => now(),
            ],
            [
                'user_uuid' => Str::uuid(),
                'worker_sid' => null,
                'name' => 'Client',
                'first_name' => 'test',
                'last_name' => 'client',
                'email' => 'client@gmail.com',
                'phone_no' => '+13251445665',
                'email_verified_at' => now(),
                'password' => Hash::make('password'),
                'step' => 6,
                'created_at' => now(),
            ],
            [
                'user_uuid' => 'f09ffc43-42b9-4fee-b0fb-bd867687867e',
                'worker_sid' => 'WK523d9d7d3831bbd6a1a3b9dec5e69ea5',
                'name' => 'Alice',
                'first_name' => 'alice',
                'last_name' => 'agent',
                'email' => 'alice@gmail.com',
                'phone_no' => '+1326987564',
                'email_verified_at' => now(),
                'password' => Hash::make(123456789),
                'step' => 6,
                'created_at' => now(),
            ],
            [
                'user_uuid' => '3f28dc13-2f50-407e-a957-689c7e42380f',
                'worker_sid' => 'WK035ba25f33afaae6885990da936d81d3',
                'name' => 'Bob',
                'first_name' => 'bob',
                'last_name' => 'agent',
                'email' => 'bob@gmail.com',
                'phone_no' => '+1123654789',
                'email_verified_at' => now(),
                'password' => Hash::make(123456789),
                'step' => 6,
                'created_at' => now(),
            ],

        ]);
    }
}
