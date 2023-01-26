<?php

namespace App\Ivr;

use App\Models\IvrBuilder;
use App\Http\Resources\RouterNodeFilterResource;

class IvrNodesFilter
{

    private $response;

    private $filter_conditions;
    private $ivrBuilder;
    private $ivr_builder_id;
    private $ivr;


    public function __construct($ivr_builder_id)
    {
        $this->ivr_builder_id = $ivr_builder_id;
        $this->ivrBuilder = new IvrBuilder();
    }


    public function getFilters()
    {

        $router_childs = $this->ivrBuilder
            ->with(['filterConditions.filter_condition_values', 'filterConditions.tag', 'filterConditions.tag_operator'])
            ->where([['parent_id', $this->ivr_builder_id], ['node_type', 'filter']])->get();
        $filters_arr = array();
        foreach ($router_childs as $index => $record) {

            $filters_arr[$index]['router_node_uuid'] = $record->uuid;
            $filters_arr[$index]['filter_conditions'] = $this->getFilterConditions($record->filterConditions);
        }

        return $this->response = $filters_arr;
    }


    public function getFilterConditions($conditions)
    {
        $condition_arry = array();
        foreach ($conditions as $index => $condition) {
            $condition_arry[$index]['type'] = $condition['type'];
            $condition_arry[$index]['tag_operator_uuid'] = $condition->tag_operator->uuid;
            $condition_arry[$index]['tag_uuid'] = $condition->tag->uuid;
            $condition_arry[$index]['tag_operator_value'] = $this->getFilterConditionValues($condition->filter_condition_values);
        }

        return $condition_arry;
    }

    public function getFilterConditionValues($condition_values)
    {
        $condition_values_arr = array();
        foreach ($condition_values as $index => $value) {
            $condition_values_arr[$index] = $value['tag_operator_value'];
        }
        return $condition_values_arr;
    }

    private function getiIdByUuid($uuid)
    {
        if (is_null($uuid)) {
            return "";
        } else {
            return $this->ivrBuilder->where('uuid', $uuid)->value('id');
        }
    }
}
