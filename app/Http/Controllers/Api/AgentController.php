<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AgentProfileItemResource;
use App\Models\AgentProfileItem;
use App\Models\AgentTraficSource;
use App\Models\Country;
use App\Models\DropDown;
use Illuminate\Http\Request;
// use Rinvex\Country;

class AgentController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function getAllTraficSource()
    {
        $source = DropDown::where('type', 'agent')->get();
        // return response()->json($source);
        if (empty($source)) {
            return $this->respond([
                'status' => false,
                'message' => 'Agent Trafic Source Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Agent Trafic source has been Fetched Successfully!',
            'data' => [
                'source' => $source
            ],
        ]);
    }


    public function getAgentProfileByUser(Request $request)
    {
        $agent_profile_items = AgentProfileItem::where('user_id', $request->user()->id)->first();
        if (empty($agent_profile_items)) {
            return $this->respond([
                'status' => false,
                'message' => 'Agent Profile Item Not Found',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Agent Profile Items has been Fetched Successfully!',
            'data' => [
                'agent_profile_items' => new AgentProfileItemResource($agent_profile_items)
            ],
        ]);
    }

    public function storeAgentTraficSource(Request $request)
    {
        $agentProfileItem = new AgentProfileItem();
        $agentProfileItem->uuid = generateUuid();
        $agentProfileItem->user_id = $request->user()->id;
        $agentProfileItem->step = 1;
        $agentProfileItem->save();



        if ($request->has('sources')) {
            $sources = explode(',', $request->sources);
            $record = [];
            foreach ($sources as $source) {
                $source_id = DropDown::getIdByUuid($source);
                $temp = [];
                $temp['uuid'] = generateUuid();
                $temp['user_id'] = $request->user()->id;
                $temp['source_id'] = $source_id;
                // $temp['is_active'] = true;
                $temp['created_at'] = now();
                $record[] = $temp;
            }
            AgentTraficSource::insert($record);
        }
        if (empty($record)) {
            return $this->respond([
                'status' => false,
                'message' => 'Agent Trafic Sources Not Created',
                'data' =>  []
            ]);
        }
        return $this->respond([
            'status' => true,
            'message' => 'Agent Trafic Sources been Created Successfully!',
            'data' => []
        ]);
    }

    public function getAgentSources(Request $request)
    {
        $agent_sources = AgentTraficSource::join('dropdowns', 'dropdowns.id', '=', 'agent_trafic_sources.source_id')
            ->where('agent_trafic_sources.user_id', $request->user()->id)
            // ->where('agent_trafic_sources.is_active','=','1')
            ->pluck('dropdowns.uuid');
        if (empty($agent_sources)) {
            return $this->respond([
                'status' => false,
                'message' => 'Agent Sources uuids Not Found',
                'data' =>  []
            ]);
        }
        return $agent_sources;
    }

    public function updateAgentSources(Request $request)
    {
        AgentTraficSource::where('user_id', '=', $request->user()->id)
            ->update(['deleted_at' => now()]);
        if ($request->has('sources')) {
            $sources = explode(',', $request->sources);
            foreach ($sources as $uuid) {
                $agent_sources = AgentTraficSource::withTrashed()->join('dropdowns', 'dropdowns.id', '=', 'agent_trafic_sources.source_id')
                    ->where('agent_trafic_sources.user_id', $request->user()->id)
                    ->where('dropdowns.uuid', $uuid)
                    ->select('agent_trafic_sources.*')->first();

                if ($agent_sources) {
                    $agent_sources->restore();
                } else {
                    $source_id = DropDown::getIdByUuid($uuid);
                    $newSource = new AgentTraficSource();
                    $newSource->uuid = generateUuid();
                    $newSource->user_id = $request->user()->id;
                    $newSource->source_id = $source_id;
                    $newSource->created_at = now();
                    $newSource->save();
                }
            }
            return $this->respond([
                'status' => true,
                'message' => 'Agent Sources has been updated Successfully!'
            ]);
        } else {
            return $this->respond([
                'status' => false,
                'message' => 'Agent Sources not updated!'
            ]);
        }
    }

    public function storeAgentLocation(Request $request)
    {
        $currentAgent = AgentProfileItem::where('user_id', '=', $request->user()->id)->first();
        if ($currentAgent) {
            $currentAgent->location = $request->location;
            $currentAgent->step = 2;
            $currentAgent->update();
            return $this->respond([
                'status' => true,
                'message' => 'Agent Location Saved Successfully!',
                'data' => $request->location
            ]);
        }
    }

    public function storeAgentDevice(Request $request)
    {
        $currentAgent = AgentProfileItem::where('user_id', '=', $request->user()->id)->first();
        if ($currentAgent) {
            $currentAgent->device = $request->device;
            $currentAgent->step = 3;
            $currentAgent->update();
            return $this->respond([
                'status' => true,
                'message' => 'Agent Device Saved Successfully!'
            ]);
        }
    }

    public function getCountryList()
    {
        $countries = Country::select('name', 'id', 'code')->get();
        return $this->respond([
            'status' => true,
            'message' => 'Agent Device Saved Successfully!',
            'countries' => $countries
        ]);
    }
}
