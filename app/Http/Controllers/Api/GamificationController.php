<?php

namespace App\Http\Controllers\Api;

use App\Classes\Gamification;
use Illuminate\Http\Request;

class GamificationController extends APiController
{
    public function getGamificationPoint(Request $request) {
        $gamification = new Gamification();
        $gamification_point = $gamification->getGamificationPoint($request);
        return $this->respond([
            'status' => true,
            'message' => 'Gamification Points has been fetched successfully!',
            'data' => [
                'gamification_points' => $gamification_point
            ],
        ]);
    }
}
