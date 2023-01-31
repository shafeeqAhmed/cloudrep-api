<?php

namespace App\Ivr\Tags;

use App\Models\CampaignGeoLocation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;

class State
{
    private $values;
    private $value;
    private $andOperator;
    private $orOpertor;
    private $region;
    public function __construct()
    {
        $this->value = request('CallerState');
        $this->region = request('FromZip');
    }

    public function isCorrect($conditions)
    {
        // dd($this->value, $conditions);
        $conditions = collect($conditions);
        $andConditions = $conditions->where('type', 'and');
        $orConditions = $conditions->where('type', 'or');
        $this->andOperator = $this->filterConditions($andConditions);
        $this->orOpertor =  $this->filterConditions($orConditions);

        if ($andConditions->isNotEmpty() && $orConditions->isNotEmpty()) {
            return $this->andOperator && $this->orOpertor;
        }
        if ($andConditions->isNotEmpty()) {
            return $this->andOperator;
        }
        if ($orConditions->isNotEmpty()) {
            return $this->orOpertor;
        }
        if (is_null($this->orOpertor)) {
            return false;
        }
    }
    public function isAllowRegion($campaignId)
    {
        $zipCode = CampaignGeoLocation::where('campaign_id', $campaignId)->pluck('zipcode');
        if ($zipCode->isNotEmpty()) {
            return $zipCode->contains($this->region);
        } else {
            return true;
        }
    }
    public function filterConditions($conditions)
    {
        foreach ($conditions as $condition) {
            $this->values = $condition['tag_operator_code'];
            $response = false;
            if (method_exists(self::class, $condition['select_operator_for_tag'])) {
                $response =  call_user_func_array([$this, $condition['select_operator_for_tag']], []);
            }

            return $response;
        }
    }


    private function equal_to()
    {
        return $this->values->contains($this->value);
    }
    private function not_equal_to()
    {
        return $this->values->doesntContain($this->value);
    }
}
