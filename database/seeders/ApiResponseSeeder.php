<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ApiResponseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $register_response = "{
            'status': true,
                  'message': 'User has been Registered Successfully!',
                  'data': {
                     'userData': {
                         'id': 2,
                         'uuid': 'fb5700d7-d543-4d41-8192-f9d962517f14',
                         'name': 'John Doe',
                         'first_name': 'John',
                         'last_name': Doe',
                         'email': 'johndoe@gmail.com',
                         'phone_no': '+11236547891',
                         'step': '3',
                         'profile_photo': 'https://ui-avatars.com/api/?name=j+j&color=7F9CF5&background=EBF4FF',
                         'ability': [
                             {
                                 'action': 'all',
                                 'subject': 'agent'
                             }
                         ],
                         'role': 'agent',
                         'is_verified_email': false
                    },
                    accessToken':  '18|x6Qlq7pZfG9TcOuFyZBR6PAZZZyHZjbs8h6Sa9Ar',
                    refreshToken': '18|x6Qlq7pZfG9TcOuFyZBR6PAZZZyHZjbs8h6Sa9Ar',
                    isVerified2fa': false,
                }
        }";
           $login_response = "{
                  'status': true,
                  'message': 'User has been Login Successfully!',
                  'data': {
                     'userData': {
                         'id': 2,
                         'uuid': 'fb5700d7-d543-4d41-8192-f9d962517f14',
                         'name': 'John Doe',
                         'first_name': 'John',
                         'last_name': Doe',
                         'email': 'johndoe@gmail.com',
                         'phone_no': '+11236547891',
                         'step': '3',
                         'profile_photo': 'https://ui-avatars.com/api/?name=j+j&color=7F9CF5&background=EBF4FF',
                         'ability': [
                             {
                                 'action': 'all',
                                 'subject': 'agent'
                             }
                         ],
                         'role': 'agent',
                         'is_verified_email': false
                    },
                    accessToken':  '18|x6Qlq7pZfG9TcOuFyZBR6PAZZZyHZjbs8h6Sa9Ar',
                    refreshToken': '18|x6Qlq7pZfG9TcOuFyZBR6PAZZZyHZjbs8h6Sa9Ar',
                    isVerified2fa': false,
                }
            }";
            $not_found = "{
                'message': 'User Not Found'
            }";
            $forgot_password = "{
                'message': 'A password reset link has been sent to this email successfully!'
              }";
            $forgot_failed = "{
                'message': 'We can't find a user with that email address'
              }";
            $reset_password = "{
                'message': 'User password has been reset successfully!'
              }";
            $reset_failed = "{
                'message': 'Sorry, wrong credentials. Please try again'
            }";
            $user_response = " * {
                      'status': true,
                      'message': 'User has been Fetched Successfully!',
                      'data': {
                         'id': 2,
                         'uuid': 'fb5700d7-d543-4d41-8192-f9d962517f14',
                         'name': 'John Doe',
                         'first_name': 'John',
                         'last_name': Doe',
                         'email': 'johndoe@gmail.com',
                         'phone_no': '+11236547891',
                         'role': null,
                         'profile_photo': {},
                         'created_at': '2022-06-04T18:32:20.000000Z',
                         'updated_at': '2022-06-04T18:36:16.000000Z',
                         'deleted_at': null
                     }
                }";
        DB::table('api_responses')->insert([
            //Auth
            ['uuid' => Str::uuid(), 'api_endpoint_id' => '1', 'code' => '201', 'description' => 'Success', 'example_value' => json_encode($register_response)],
            ['uuid' => Str::uuid(), 'api_endpoint_id' => '2', 'code' => '201', 'description' => 'Success', 'example_value' => json_encode($login_response)],
            ['uuid' => Str::uuid(), 'api_endpoint_id' => '1', 'code' => '404', 'description' => 'User Not Found', 'example_value' => json_encode($not_found)],
            ['uuid' => Str::uuid(), 'api_endpoint_id' => '3', 'code' => '201', 'description' => 'Success', 'example_value' => json_encode($forgot_password)],
            ['uuid' => Str::uuid(), 'api_endpoint_id' => '3', 'code' => '422', 'description' => "We can't find a user with that email address", 'example_value' => json_encode($forgot_failed)],
            ['uuid' => Str::uuid(), 'api_endpoint_id' => '4', 'code' => '422', 'description' => 'Success', 'example_value' => json_encode($reset_password)],
            ['uuid' => Str::uuid(), 'api_endpoint_id' => '4', 'code' => '422', 'description' => "Sorry, wrong credentials. Please try again", 'example_value' => json_encode($reset_failed)],
            //User
            ['uuid' => Str::uuid(), 'api_endpoint_id' => '5', 'code' => '201', 'description' => "Success", 'example_value' => json_encode($user_response)],
        ]);
    }
}
