<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IvrNodesRetries extends Model
{
    use HasFactory;
    protected $table = "ivr_nodes_retries";
    protected $guarded = ['id'];


    public static function increament($callSid, $type, $uuid)
    {
        $record = self::where('call_sid', $callSid)->where('node_uuid', $uuid)->where('node_type', $type)->first();
        if ($record) {
            $record->update(['no_of_retires' => ++$record->no_of_retires]);
        } else {
            self::create(['call_sid' => $callSid, 'node_type' => $type, 'node_uuid' => $uuid]);
        }
    }
    public static function getCount($callSid, $uuid)
    {
        return self::where('call_sid', $callSid)->where('node_uuid', $uuid)->value('no_of_retires') ?? 0;
    }
}
