<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;



class WalletController extends Controller
{
    public function withdraw($user_uuid,$amount){
        $user = User::where('user_uuid',$user_uuid)->first();
        //$user->deposit($amount);
        echo $user->balance;
    }
}
