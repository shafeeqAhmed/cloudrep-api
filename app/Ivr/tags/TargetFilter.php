<?php

namespace App\Ivr\Tags;

use App\Models\TargetListing;
use Illuminate\Support\Facades\Log;

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
            $list[$index]['id'] = $condition['id'];
            $list[$index]['target_id'] = $condition['target_id'];
            $list[$index]['type'] = $condition['type'];
            $list[$index]['selected_value_for_tag'] = $condition->tag_value;
            $list[$index]['select_operator_for_tag'] = $condition->select_operator_for_tag;
            $list[$index]['destination'] = TargetListing::where('id', $condition->target_id)->value('destination');
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
        $isCorrect = $state->isCorrect($conditions);
        // Log::debug($isCorrect);
        // Log::info($conditions);
        return ['containFilter' => count($conditions) > 0, 'isCorrect' => $isCorrect, $id];
        // return ['containFilter' => true, 'isCorrect' => false];
        // if ($id = 6) {
        //     return  ['containFilter' => true, 'isCorrect' => true];
        // } else {
        // return ['containFilter' => true, 'isCorrect' => false];
        // }
    }
}
