<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ApiEndPointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('api_endpoints')->insert([
            //Auth
            ['uuid' => Str::uuid(), 'api_list_id' => '1', 'type' => 'Post', 'url' => '/api/register', 'title' => 'Signup', 'description' => 'Register User'],
            ['uuid' => Str::uuid(), 'api_list_id' => '1', 'type' => 'Post', 'url' => '/api/login', 'title' => 'Signin', 'description' => 'Login by email, password'],
            ['uuid' => Str::uuid(), 'api_list_id' => '1', 'type' => 'Post', 'url' => '/api/forgot-password', 'title' => 'Forgot Password', 'description' => 'Forgot Password by email'],
            ['uuid' => Str::uuid(), 'api_list_id' => '1', 'type' => 'Post', 'url' => '/api/reset-password', 'title' => 'Reset Password', 'description' => 'Reset Password by email, password, token'],
            //User
            ['uuid' => Str::uuid(), 'api_list_id' => '2', 'type' => 'Get', 'url' => '/api/auth/user', 'title' => 'Get Auth User', 'description' => 'Get Auth User'],
            ['uuid' => Str::uuid(), 'api_list_id' => '2', 'type' => 'Get', 'url' => '/api/user', 'title' => 'Get Users', 'description' => 'Get Users'],
            ['uuid' => Str::uuid(), 'api_list_id' => '2', 'type' => 'Put', 'url' => '/api/user', 'title' => 'Update User', 'description' => 'Update User'],
        ]);
    }
}
