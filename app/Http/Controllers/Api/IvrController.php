<?php

namespace App\Http\Controllers\Api;

use App\Http\Resources\IvrResource;
use App\Http\Resources\IvrNodeFiltersResource;
use App\Http\Resources\RouterNodeFilterConditionsResource;
use App\Ivr\IvrNodesResponse;
use App\Ivr\IvrNodesfilterResponse;
use App\Models\Ivr;
use Illuminate\Http\Request;
use App\Models\IvrBuilder;
use Illuminate\support\Str;
use App\Models\Tags;
use App\Models\TagOperators;
use App\Models\IvrBuilderFilterConditions;

class IvrController extends ApiController
{
    public function index(Request $request)
    {
        $ivrs = Ivr::getIvrs($request);
        if (empty($ivrs)) {
            return $this->respond([
                'status' => false,
                'message' => 'Ivr is Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Ivrs has been Fetched Successfully!',
            'data' => $ivrs,
        ]);
    }

    public function storeIvr(Request $request)
    {
        $request->validate([
            'name' => 'required'
        ]);
        $ivr = new Ivr($request->all());
        $ivr->save();

        return $this->respond([
            'status' => true,
            'message' => 'Ivr has been created successfully!',
            'data' => [
                'ivr' => new IvrResource($ivr),
            ],
        ]);
    }
    public function duplicateIvr(Request $request)
    {
        $request->validate(['ivr_uuid' => 'required']);
        $ivr = Ivr::where('uuid', $request->ivr_uuid)->first();
        if ($ivr) {
            $copyIvr = $ivr->replicate();
            $copyIvr->uuid = generateUuid();
            $copyIvr->name = !empty($ivr->name)  ? $ivr->name  . '-copy' : $ivr->name;
            $copyIvr->contact_no = '';
            // $copyIvr->is_active = $ivr->is_active;
            $copyIvr->save();
            $this->assignIvrNode($ivr->id, $copyIvr);

            return $this->respond([
                'status' => true,
                'message' => 'Record Duplicated Successfully!',
                'data' => []
            ]);
        }
    }
    public function assignIvrNode($ivrId, $copyIvr)
    {
        // $copyIvrId = Ivr::getIdByUuid($copyIvrUuid);
        $ivrNodes = IvrBuilder::where('ivr_id', $ivrId)->get();
        if ($ivrNodes) {
            $newParentIds = [];
            $newIds = [];
            foreach ($ivrNodes as $ivrNode) {
                $newIvrNode = $ivrNode->replicate();
                $newIvrNode->uuid = Str::uuid()->toString();
                $newIvrNode->node_type = $ivrNode->node_type;
                $newIvrNode->tag_name = !empty($newIvrNode->tag_name)  ? $newIvrNode->tag_name  . '-copy' : $newIvrNode->tag_name;
                $newIvrNode->ivr_id = $copyIvr->id;
                $newIvrNode->created_at = now();
                $newIvrNode->save();
                $newParentIds[$ivrNode->id] = $newIvrNode->id;
                $newIds[] = $newIvrNode->id;
            }
            foreach ($newParentIds as $oldParent => $value) {
                $types = ['parent_id', 'on_failer', 'on_success', 'press_0', 'press_1', 'press_2', 'press_3', 'press_4', 'press_5', 'press_6', 'press_7', 'press_8', 'press_9', 'goto_node'];
                foreach ($types as $type) {
                    IvrBuilder::whereIn('id', $newIds)
                        ->where($type, $oldParent)
                        ->update([
                            $type => $value,
                        ]);
                }
            }
        }
    }
    public function deleteIvr(Request $request)
    {
        Ivr::where('uuid', $request->uuid)->forceDelete();
        return $this->respond([
            'status' => true,
            'message' => 'Record has been deleted successfully!',
            'data' => []
        ]);
    }
    public function getIvr(Request $request)
    {
        $request->validate(['uuid' => 'required']);
        $data = [];
        $ivr = Ivr::where('uuid', $request->uuid)->select('id', 'uuid', 'name')->first();

        //restore soft deleted record
        IvrBuilder::where('ivr_id', $ivr->id)->whereNotNull('deleted_at')->onlyTrashed()->restore();

        $data['ivr'] = $ivr->toArray();
        $IvrNodesResponse = new IvrNodesResponse();

        $data['nodes'] = $IvrNodesResponse->getNodes($ivr);
        return $this->respond([
            'status' => true,
            'message' => 'Record has been deleted successfully!',
            'data' => $data
        ]);
    }

    public function getTags()
    {
        $tags = Tags::get(['uuid', 'name', 'value']);

        return $this->respond([
            'status' => true,
            'message' => 'Tags has been fetched successfully!',
            'tags' => $tags
        ]);
    }

    public function getTagOperators(Request $request)
    {
        $request->validate(['tag_uuid' => 'required']);
        $tag_id = Tags::getIdByUuid($request->tag_uuid);

        $operators = TagOperators::where('tag_id', $tag_id)->get(['name', 'uuid']);

        return $this->respond([
            'status' => true,
            'message' => 'Tag Operators has been fetched successfully!',
            'operators' => $operators
        ]);
    }

    public function storeTagFilterConditions(Request $request)
    {
        return  $campaignReporting = IvrBuilderFilterConditions::saveTagFilterConditions($request);
        return $this->respond([
            'status' => true,
            'message' => 'Filtetr Condition has been saved successfully!',
            'data' => [
                'filterConditions' => []
            ],
        ]);
    }


    public function getIvrFilterRecord(Request $request)
    {
        $request->validate(['ivr_builder_id' => 'required']);
        $Ivr_builder_filter_conditions = IvrBuilderFilterConditions::with('filter_condition_values', 'tag', 'tag_operator')->where('ivr_builder_id', $request->ivr_builder_id)->get();
        return $this->respond([
            'status' => true,
            'message' => 'Filter Record has been fetched successfully!',
            'data' => [
                'filters' => IvrNodeFiltersResource::collection($Ivr_builder_filter_conditions)
            ],
        ]);
    }

    public function getIvrFilterConditions(Request $request)
    {
        $IvrNodesResponse = new IvrNodesfilterResponse($request->ivr_builder_uuid);
        return $IvrNodesResponse->getFilters();
    }
}
