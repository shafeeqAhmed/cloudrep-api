<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;
use Laravel\Jetstream\Jetstream;
use Illuminate\Validation\Rule;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Validate and create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        // $input['role'] = 'agent';
        Validator::make($input, [
            'first_name' => ['required', 'string', 'max:15'],
            'last_name' => ['required', 'string', 'max:15'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'role' => [
                'required',
                Rule::in(['client', 'agent', 'publisher'])
            ],
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature() ? ['accepted', 'required'] : '',
        ])->validate();
        $user =  User::create([
            'name' => $input['first_name'] . ' ' . $input['last_name'],
            'first_name' => $input['first_name'],
            'last_name' => $input['last_name'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'step' => 3
        ]);
        $user->assignRole($input['role']);

        $wallet = $user->wallet;
        $wallet->meta = ['currency' => 'USD'];
        $wallet->balance = 500;
        $wallet->save();
        return User::where('id', $user->id)->first();
    }

    public function createdByAdmin(array $input)
    {

        Validator::make($input, [
            'first_name' => ['required', 'string', 'max:15'],
            'last_name' => ['required', 'string', 'max:15'],
            'phone_no' => ['required', 'min:10'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => $this->passwordRules(),
            'role' => [
                'required',
                Rule::in(['client', 'agent', 'publisher'])
            ],
        ])->validate();

        $user =  User::create([
            'name' => $input['first_name'] . ' ' . $input['last_name'],
            'first_name' => $input['first_name'],
            'last_name' => $input['last_name'],
            'phone_no' => $input['phone_no'],
            'email' => $input['email'],
            'password' => Hash::make($input['password']),
            'step' => 6,
            'email_verified_at' => Carbon::now()
        ]);
        $user->assignRole($input['role']);

        $wallet = $user->wallet;
        $wallet->meta = ['currency' => 'USD'];
        $wallet->balance = 500;
        $wallet->save();
        return User::where('id', $user->id)->first();
    }
}
