<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\support\Str;

use Spatie\Permission\Traits\HasRoles;
use Bavix\Wallet\Traits\HasWallet;
use Bavix\Wallet\Interfaces\Wallet;
use Bavix\Wallet\Traits\HasWallets;
use Illuminate\Http\Request;

// implements MustVerifyEmail

class User extends Authenticatable implements MustVerifyEmail, Wallet
{
    use HasApiTokens;
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;
    use HasRoles;
    use HasWallet, HasWallets;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    use SoftDeletes;
    protected $fillable = [
        'first_name',
        'last_name',
        'name',
        'worker_sid',
        'phone_no',
        'email',
        'profile_photo_path',
        'step',
        'password',
        'email_verified_at',
        'number_creation',
        'calls_payout_cap',
        'access_to_recording'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    // protected $appends = [
    //     'profile_photo_url',
    // ];
    public function clientProfileItem()
    {
        return $this->hasOne(ClientProfileItem::class, 'user_id', 'id');
    }
    public static function updateRecord($col, $val, $data)
    {
        self::where($col, $val)->update($data);
    }
    public static function getRecord($col, $val)
    {
        $record =  User::where($col, $val)->first();
        return $record ?? null;
    }
    public static function getRecordById($id)
    {
        $record =  User::where('id', $id)->first();
        return $record ?? null;
    }



    public static function updateStep($id, $step)
    {
        return self::where('id', $id)->update(['step' => $step]);
    }
    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->user_uuid = Str::uuid()->toString();
        });
    }
    public static function getIdByUuid($uuid)
    {
        return self::where('user_uuid', $uuid)->value('id');
    }

    public static function getUsers(Request $request)
    {
        $users = User::join('model_has_roles as mr', 'mr.model_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'mr.role_id')
            ->when($request->q, function ($query, $q) {
                return $query->where('name', 'LIKE', "%{$q}%");
            })
            ->selectRaw('CONCAT(users.name, " (" ,roles.name,")") AS name , users.user_uuid as uuid')
            ->when($request->sortBy, function ($query, $sortBy) {
                return $query->orderBy($sortBy, request('sortDesc') == 'true' ? 'asc' : 'desc');
            })
            ->when($request->page, function ($query, $page) {
                return $query->offset($page - 1);
            })
            ->whereDoesntHave('roles', function ($query) {
                return $query->where('name', 'agent');
            })
            ->paginate($request->perPage);
        return $users;
    }
}
