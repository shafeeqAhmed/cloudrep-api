<?php

namespace App\Ivr\Tags;

use App\Models\TargetListing;
use App\Ivr\Tags\FilterDetail;

class TargetFilter
{

    private $targetListing;
    private $node;
    private $nextNode;


    public function __construct($node)
    {
        $this->node = $node;
        $this->targetListing = new TargetListing();
    }


    public function getFilters($targetId)
    {

        $router_childs = $this->targetListing
            ->with(['filterConditions.filter_condition_values', 'filterConditions.tag', 'filterConditions.tag_operator'])
            ->where([['target_id', $targetId]])->get();
        $filters_arr = array();
        foreach ($router_childs as $index => $record) {

            $filters_arr[$index]['node'] = $record;
            $filters_arr[$index]['priority'] = $record->priority;
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
            // $condition_arry[$index]['tag_operator_list'] = $condition->tag_operator->pluck('value');
            // $condition_arry[$index]['tag_operator_value'] = $condition->filter_condition_values->pluck('tag_operator_value');
            $condition_arry[$index]['tag_operator_code'] = $condition->filter_condition_values->pluck('tag_operator_code');
        }

        return $condition_arry;
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
        $filterDetail = new FilterDetail();
        //get all filters against router node
        $filters = $this->getFilters();
        //if router node contain filters
        if ($filters) {
            // iterate the filters
            foreach ($filters as $key => $filter) {
                $isCorrect = $filterDetail->isCorrect($filter['conditions']);
                // if condition is true
                if ($isCorrect) {
                    // find the next tragetted route child
                    $this->nextNode = $filter['node']->filterChild;
                    break;
                }
            }
        }
        return $this->nextNode;
    }
}
