<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Jobs\SendTextMessage;

class VerificationCode extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public static function generatedVerificationCode($user_id)
    {

        self::where('user_id', $user_id)->delete();
        $envornament = config('app.env');
        if ($envornament == 'local' || $envornament == 'development') {
            $code = 1111;
        } else {
            $code = rand(1000, 9999);
        }
        $expire_date_time = Carbon::now()->addMinute(30)->format('Y-m-d H:i:s');
        $user = User::where('id', $user_id)->first();
        // dd(request()->user()->phone_no);
        self::create([
            'user_id' => $user_id,
            'code' => $code,
            'expired_date_time' => $expire_date_time
        ]);
        // dispatch(new SendTextMessage($code, '+18735030331', '+16725721405'));
        if ($envornament == 'production') {
            dispatch(new SendTextMessage($code, $user->phone_no, '+18735030331'));
        }
        return $code;
    }
    public static function verifyTwoFa($user_id, $code)
    {
        return self::where('user_id', $user_id)->where('code', $code)->delete();
    }

    public static function verifyTwoFaPreReg($id, $code)
    {
        return PreRegistration::where('id', $id)->where('verification_code', $code)->update(['verification_code'=>'']);
    }

    public static function isVerified($user_id)
    {
        return self::where('user_id', $user_id)->exists();
    }
    public static function generatedVerificationCodeForPreReg($id)
    {
        PreRegistration::where('id', $id)->update(['verification_code'=>'']);
        $envornament = config('app.env');
        if ($envornament == 'local' || $envornament == 'development') {
            $code = 1111;
        } else {
            $code = rand(1000, 9999);
        }
        $expire_date_time = Carbon::now()->addMinute(30)->format('Y-m-d H:i:s');
        $pre_register = PreRegistration::where('id', $id)->first();
        if($pre_register)
        {
            $pre_register->verification_code = $code;
            $pre_register->code_expired_date_time = $expire_date_time;
            $pre_register->update();
        }
        if ($envornament == 'production') {
            dispatch(new SendTextMessage($code, $pre_register->phone_no, '+18735030331'));
        }
        return $code;
    }
}
