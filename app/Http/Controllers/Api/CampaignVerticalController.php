<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CampaignVerticalResource;
use App\Models\CampaignVertical;
use Illuminate\Http\Request;

class CampaignVerticalController extends ApiController
{
    public function storeCampaignVertical(Request $request) {
        $campaign_vertical = new CampaignVertical($request->all());
        // $campaign_category->name = $request->name;
        $campaign_vertical->save();
        if (empty($campaign_vertical)) {
            return $this->respond([
                'status' => false,
                'message' => 'Campaign Vertical Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Campaign Vertical has been Created Successfully!',
            'data' => [
                'campaign_vertical' => new CampaignVerticalResource($campaign_vertical)
            ],
        ]);
    }

    public function getCampaignVertical() {
        $compaign_verticals = CampaignVertical::all();
        if (empty($compaign_verticals)) {
            return $this->respond([
                'status' => false,
                'message' => 'Campaign Verticals Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Campaign Verticals has been Fetched Successfully!',
            'data' => [
                'campaign_verticals' =>CampaignVerticalResource::collection($compaign_verticals)
            ],
        ]);
    }
}
