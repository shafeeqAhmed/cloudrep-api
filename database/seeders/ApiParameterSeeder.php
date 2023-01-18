<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class ApiParameterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //user param
        DB::table('api_parameters')->insert([
            ['uuid' => Str::uuid(), 'api_endpoint_id' => '5', 'name' => 'user_uuid', 'data_type' => 'string', 'description' => 'get user by auth user_uuid', 'example_data' => 'c0dd00aa-e6e8-4b96-9335-8a01858029e6'],
            ['uuid' => Str::uuid(), 'api_endpoint_id' => '6', 'name' => 'sortBy', 'data_type' => 'string', 'description' => 'sort user by sortBy param', 'example_data' => 'asc/desc'],
            ['uuid' => Str::uuid(), 'api_endpoint_id' => '6', 'name' => 'perPage', 'data_type' => 'integer', 'description' => 'sort user by pagincation param', 'example_data' => 2],
            ['uuid' => Str::uuid(), 'api_endpoint_id' => '6', 'name' => 'role', 'data_type' => 'string', 'description' => 'get user by role', 'example_data' => 'admin/agent/client'],
            ['uuid' => Str::uuid(), 'api_endpoint_id' => '7', 'name' => 'user_uuid', 'data_type' => 'string', 'description' => 'update user by user_uuid', 'example_data' => 'c0dd00aa-e6e8-4b96-9335-8a01858029e6'],
        ]);
    }
}
