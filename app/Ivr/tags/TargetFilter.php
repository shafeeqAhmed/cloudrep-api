<?php

namespace App\Ivr\Tags;

use App\Models\TargetListing;

class TargetFilter
{

    private $targetListing;
    private $nextNode;


    public function __construct()
    {
        $this->targetListing = new TargetListing();
    }


    public function getFilters($id)
    {

        $targetFilter = $this->targetListing
            ->with(['filterConditions.filter_condition_values', 'filterConditions.tag', 'filterConditions.tag_operator'])
            ->where('id', $id)->first();

        return $this->getFilterConditions($targetFilter->filterConditions);
    }

    public function getFilterConditions($conditions)
    {
        $list = array();
        foreach ($conditions as $index => $condition) {
            $list[$index]['type'] = $condition['type'];
            $list[$index]['selected_value_for_tag'] = $condition->tag_value;
            $list[$index]['select_operator_for_tag'] = $condition->select_operator_for_tag;
            // $list[$index]['tag_operator_list'] = $condition->tag_operator->pluck('value');
            // $list[$index]['tag_operator_value'] = $condition->filter_condition_values->pluck('tag_operator_value');
            $list[$index]['tag_operator_code'] = $condition->filter_condition_values->pluck('tag_operator_code');
        }

        return $list;
    }
    public function check($id)
    {

        $state = new State();
        $conditions = $this->getFilters($id);
        return ['containFilter' => count($conditions) > 0, 'isCorrect' => $state->isCorrect($conditions)];
    }
}
