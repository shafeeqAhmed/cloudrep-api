<?php

namespace App\Ivr\Tags;

use Illuminate\Support\Collection;

class FilterDetail
{
    private $values;
    private $value;
    private $andOperator;
    private $orOpertor;
    public function __construct()
    {
        $this->value = 'Alabam';
    }

    public function isCorrect($conditions)
    {
        $conditions = collect($conditions);

        $this->andOperator = $this->testFilterConditions($conditions->where('type', 'and'));
        $this->orOpertor =  $this->testFilterConditions($conditions->where('type', 'or'));
        return $this->andOperator && $this->orOpertor;
    }
    public function testFilterConditions($conditions)
    {
        foreach ($conditions as $condition) {
            $this->values = $condition['tag_operator_value'];
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
