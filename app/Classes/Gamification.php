<?php

namespace App\Classes;

use App\Models\Gamification as GamificationModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class Gamification {

    public function add($request, $user_id, $point, $reason = null, $isTransfer){
        $gamification = new GamificationModel($request->all());
        $gamification->user_id = $user_id;
        $gamification->point = $point;
        $gamification->reason = $reason;
        $gamification->isTransfer = $request->boolean($isTransfer);
        $gamification->save();
    }

    public function getGamificationPoint(Request $request) {
        $gamification_point = GamificationModel::where('user_id',$request->user()->id)->sum('point');
        return $gamification_point;
    }
}