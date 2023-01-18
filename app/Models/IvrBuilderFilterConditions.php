<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\support\Str;
use Illuminate\Http\Request;
use App\Models\Campaign;
use App\Models\TagOperators;
use App\Models\Tags;
use App\Models\IvrBuilderFilterConditionValues;

class IvrBuilderFilterConditions extends Model
{
    use HasFactory;
    protected $fillable = ['campaign_id', 'ivr_builder_id', 'tag_id', 'tag_operator_id', 'type'];

    public static function boot()
    {
        parent::boot();
        self::creating(function ($model) {
            $model->uuid = str::uuid()->toString();
        });
    }

    public static function saveTagFilterConditions(Request $request)
    {

        //     $params = json_decode($request->filters)->filters;
        //  $params = json_decode($request->filters, true);


        // $params = (array) json_decode(file_get_contents('php://input'), TRUE);
        // return ($request->filters);
        $filter_conditions = array();
        $filter_condition_values = array();
        $count = 0;
        foreach ($request->filters as $i => $record) {

            /*$type = $record->type ?? null;
            $ivr_builder_id = $record->ivr_builder_id ?? null;
            $campaign_id = isset($record->campaign_uuid) ?  Campaign::getIdByUuid($record->campaign_uuid) : null;
            $tag_id =  isset($record->tag_uuid) ?  Tags::getIdByUuid($record->tag_uuid) : null;
            $tag_operator_id = isset($record->tag_operator_uuid) ?  TagOperators::getIdByUuid($record->tag_operator_uuid) : null;
            $tag_operator_value = $record->tag_operator_value ?? null;*/

            $type = $record['type'] ?? null;
            $ivr_builder_id = $record['ivr_builder_id'] ?? null;
            $campaign_id = isset($record['campaign_uuid']) ?  Campaign::getIdByUuid($record['campaign_uuid']) : null;
            $tag_id =  isset($record['tag_uuid']) ?  Tags::getIdByUuid($record['tag_uuid']) : null;
            $tag_operator_id = isset($record['tag_operator_uuid']) ?  TagOperators::getIdByUuid($record['tag_operator_uuid']) : null;

            $filter_conditions['type'] = $type;
            $filter_conditions['campaign_id'] = $campaign_id;
            $filter_conditions['ivr_builder_id'] = $ivr_builder_id;
            $filter_conditions['tag_id'] = $tag_id;
            $filter_conditions['tag_operator_id'] = $tag_operator_id;

            $result = self::create($filter_conditions);
            
            if (!empty($record['tag_operator_value'])) {
                foreach ($record['tag_operator_value'] as $index => $value) {
                    $filter_condition_values[$count]['filter_condition_id'] = $result->id;
                    $filter_condition_values[$count]['tag_operator_value'] = $value;
                    $count++;
                }
            }
        }

        IvrBuilderFilterConditionValues::insert($filter_condition_values);
    }
}