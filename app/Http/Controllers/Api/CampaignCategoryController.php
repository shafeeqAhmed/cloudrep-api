<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\CampaignCategoryResource;
use App\Models\CampaignCategory;
use Illuminate\Http\Request;

class CampaignCategoryController extends ApiController
{
    public function storeCampaignCategory(Request $request)
    {
        $campaign_category = new CampaignCategory($request->all());
        // $campaign_category->name = $request->name;
        $campaign_category->save();
        if (empty($campaign_category)) {
            return $this->respond([
                'status' => false,
                'message' => 'Campaign Category Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Campaign Category has been Created Successfully!',
            'data' => [
                'campaign_category' => new CampaignCategoryResource($campaign_category)
            ],
        ]);
    }

    public function getCampaignCategory(Request $request)
    {
        $compaign_categories = CampaignCategory::all();
        if (empty($compaign_categories)) {
            return $this->respond([
                'status' => false,
                'message' => 'Campaign Categories Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Campaign Categories has been Fetched Successfully!',
            'data' => [
                'campaign_categories' => CampaignCategoryResource::collection($compaign_categories)
            ],
        ]);
    }
}
