<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class IvrBuilder extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['uuid', 'parent_id', 'ivr_id', 'parent_filter_id', 'priority', 'type', 'node_type', 'time_zone', 'tag_name', 'timeout', 'no_of_retries', 'no_of_reproduce', 'node_content_type', 'sound_label', 'sound', 'text', 'text_voice', 'text_language', 'open_time', 'close_time', 'start_break_time', 'break_duration', 'pixel_url', 'pixel_max_fires', 'pixel_advanced', 'pixel_method', 'pixel_content_type', 'pixel_body', 'pixel_custom_header_1', 'pixel_custom_header_2', 'pixel_authorization', 'pixel_username', 'pixel_password', 'dial_recording_setting', 'dial_max_call_length', 'dial_max_recording_time', 'dial_caller_id', 'dial_wishper', 'dial_routing_plan', 'gather_max_number_of_digits', 'gather_min_number_of_digits', 'gather_valid_digits', 'gather_finish_on_key', 'gather_key_press_timeout', 'goto_count', 'goto_current_node', 'goto_node', 'goto_source_node_uuid', 'hangup_message', 'voicemail_max_length', 'voicemail_finish_on_key', 'voicemail_play_beep', 'voicemail_email_notification', 'voicemail_message', 'on_failer', 'on_success', 'press_0', 'press_1', 'press_2', 'press_3', 'press_4', 'press_5', 'press_6', 'press_7', 'press_8', 'press_9'];

    public function childs()
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    public function ivr()
    {
        return $this->belongsTo(IvrBuilder::class, 'ivr_id', 'id');
    }

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = Str::uuid()->toString();
        });
    }
    public static function getIdByUuid($uuid)
    {
        return IvrBuilder::where('uuid', $uuid)->value('id');
    }
    public function fail()
    {
        return $this->hasOne(self::class, 'id', 'on_failer');
    }
    public function success()
    {
        return $this->hasOne(self::class, 'id', 'on_success');
    }
    public function zero()
    {
        return $this->hasOne(self::class, 'id', 'press_0');
    }
    public function one()
    {
        return $this->hasOne(self::class, 'id', 'press_1');
    }

    public function two()
    {
        return $this->hasOne(self::class, 'id', 'press_2');
    }

    public function three()
    {
        return $this->hasOne(self::class, 'id', 'press_3');
    }

    public function four()
    {
        return $this->hasOne(self::class, 'id', 'press_4');
    }
    public function five()
    {
        return $this->hasOne(self::class, 'id', 'press_5');
    }
    public function six()
    {
        return $this->hasOne(self::class, 'id', 'press_6');
    }
    public function seven()
    {
        return $this->hasOne(self::class, 'id', 'press_7');
    }
    public function eight()
    {
        return $this->hasOne(self::class, 'id', 'press_8');
    }
    public function nine()
    {
        return $this->hasOne(self::class, 'id', 'press_9');
    }
    public function goto()
    {
        return $this->hasOne(self::class, 'id', 'goto_node');
    }

    public function routerNodeFilters()
    {
        return $this->hasMany(self::class, 'parent_id', 'id')->where('node_type', 'filter');
        // ->orderBy('priority', 'desc');
    }
    public function filterChild()
    {
        return $this->hasOne(self::class, 'parent_filter_id', 'id');
    }
    public function nodeParentFilter()
    {
        return $this->hasOne(self::class, 'id', 'parent_filter_id');
    }

    public static function getRecordWithAllRelationship($col, $val)
    {
        return self::with(["fail", "success", "zero", "one", "two", "three", "four", "five", "six", "seven", "eight", "nine"])->where($col, $val)->first();
    }
    public static function updateRecord($col, $val, $data)
    {
        return self::where($col, '=', $val)->update($data);
    }
    public function getFillableAttributes($data)
    {
        return  $this->fillableFromArray($data);
    }
    public static function getFilterPeriority($parentId)
    {
        $count = self::where('parent_id', $parentId)->where('node_type', 'filter')->count();
        return ++$count;
    }
    //this the for new build
    public function getNodeAttributes($type)
    {
        if ($type == 'dial') {
            return [
                'routing_uuid',
                'node_type',
                'goto_source_node_uuid',
                'dial_caller_id',
                'dial_max_call_length',
                'dial_max_recording_time',
                'dial_recording_setting',
                'dial_routing_plan',
                'dial_wishper',
                'no_of_reproduce',
                'no_of_retries',
                'node_content_type',
                'sound',
                'text',
                'text_language',
                'text_voice',
                'timeout',
                'type',
            ];
        } elseif ($type == 'gather') {
            return [
                'node_type',
                'goto_source_node_uuid',
                'gather_finish_on_key',
                'gather_key_press_timeout',
                'gather_max_number_of_digits',
                'gather_min_number_of_digits',
                'gather_valid_digits',
                'no_of_reproduce',
                'no_of_retries',
                'node_content_type',
                'sound',
                'text',
                'tag_name',
                'text_language',
                'text_voice',
                'timeout',
                'type',
                'sound_label'
            ];
        } elseif ($type == 'goto') {
            return [
                'node_type',
                'goto_source_node_uuid',
                'goto_count',
                'type',
            ];
        } elseif ($type == 'hangup') {
            return [
                'node_type',
                'goto_source_node_uuid',
                'hangup_message',
                'no_of_reproduce',
                'node_content_type',
                'sound',
                'text',
                'text_language',
                'text_voice',
                'type',
            ];
        } elseif ($type == 'menu') {
            return [
                'node_type',
                'goto_source_node_uuid',
                'no_of_reproduce',
                'no_of_retries',
                'node_content_type',
                'sound',
                'text',
                'text_language',
                'text_voice',
                'tag_name',
                'timeout',
                'type',
            ];
        } elseif ($type == 'play') {
            return [
                'node_type',
                'goto_source_node_uuid',
                'hangup_message',
                'no_of_reproduce',
                'node_content_type',
                'sound',
                'text',
                'text_language',
                'text_voice',
                'type',
            ];
        } elseif ($type == 'voicemail') {
            return [
                'node_type',
                'goto_source_node_uuid',
                'no_of_retries',
                'node_content_type',
                'sound',
                'text',
                'text_language',
                'text_voice',
                'timeout',
                'type',
                'voicemail_email_notification',
                'voicemail_finish_on_key',
                'voicemail_max_length',
                'voicemail_message',
                'voicemail_play_beep',
            ];
        } elseif ($type == 'router') {
            return [
                'node_type',
                'type'
            ];
        } else {
            return [];
        }
    }
}
