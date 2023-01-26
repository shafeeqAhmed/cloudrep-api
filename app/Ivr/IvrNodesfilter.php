<?php

namespace App\Ivr;

use App\Models\IvrBuilder;
use App\Http\Resources\RouterNodeFilterResource;

class IvrNodesFilter
{

    private $ivrBuilder;
    private $node;
    private $filters;
    private $response;
    private $filter_conditions;
    private $ivr;
    private $tagType;
    private $state;


    public function __construct($node)
    {
        $this->node = $node;
        $this->ivrBuilder = new IvrBuilder();
    }


    public function getFilters()
    {

        $router_childs = $this->ivrBuilder
            ->with(['filterConditions.filter_condition_values', 'filterConditions.tag', 'filterConditions.tag_operator'])
            ->where([['parent_id', $this->node->id], ['node_type', 'filter']])->get();
        $filters_arr = array();
        foreach ($router_childs as $index => $record) {

            $filters_arr[$index]['node_type'] = $record->node_type;
            $filters_arr[$index]['conditions'] = $this->getFilterConditions($record->filterConditions);
        }

        return $filters_arr;
    }


    public function getFilterConditions($conditions)
    {
        $condition_arry = array();
        foreach ($conditions as $index => $condition) {
            $condition_arry[$index]['type'] = $condition['type'];
            $condition_arry[$index]['selected_value_for_tag'] = $condition->tag_value;
            $condition_arry[$index]['select_operator_for_tag'] = $condition->select_operator_for_tag;
            $condition_arry[$index]['tag_operator_list'] = $condition->tag_operator->pluck('value');
            $condition_arry[$index]['tag_operator_value'] = $condition->filter_condition_values->pluck('tag_operator_value');
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
    public function getNextNode()
    {
        return  $this->getFilters();
    }
}
