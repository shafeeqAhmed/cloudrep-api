<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class Ivr extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $fillable = [
        'name',
        'contact_no',
        'is_active'
    ];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = Str::uuid()->toString();
        });
    }
    public function nodes()
    {
        return $this->hasMany(IvrBuilder::class, 'ivr_id', 'id')->leftJoin('routings as r', 'r.id', '=', 'ivr_builders.dial_routing_plan')->select('ivr_builders.*', 'r.uuid as dial_routing_plan');
    }


    public static function getRecord($col, $val)
    {
        return Ivr::where($col, $val)->first();
    }
    public static function getRecordWithNodes($col, $val)
    {
        return Ivr::where($col, $val)->with('nodes')->first();
    }
    public static function getIdByUuid($uuid)
    {
        return Ivr::whereUuid($uuid)->value('id');
    }

    public function childs()
    {
        return $this->hasMany(IvrBuilder::class, 'ivr_id', 'id');
    }

    public static function updateRecord($col, $val, $data)
    {
        return Ivr::where($col, '=', $val)->update($data);
    }

    public static function getIvrs(Request $request)
    {

        $ivrs = Ivr::when($request->q, function ($query, $q) {
            return $query->where('name', 'LIKE', "%{$q}%");
        })
            ->when($request->soryBy, function ($query, $sortBy) {
                return $query->orderBy($sortBy, request('sortDesc') == 'true' ? 'asc' : 'desc');
            })
            ->when($request->page, function ($query, $page) {
                return $query->offset($page - 1);
            })
            ->paginate($request->perPage);

        return $ivrs;
    }
}
