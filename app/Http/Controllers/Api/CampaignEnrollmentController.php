<?php

namespace App\Http\Controllers\Api;

use App\Models\CampaignEnrollment;
use Illuminate\Http\Request;
use App\Http\Requests\StoreCampaignEnrollmentRequest;

class CampaignEnrollmentController extends ApiController
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCampaignEnrollmentRequest $request)
    {
            $validated = $request->validated();
            dd($validated);

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Api\CampaignEnrollment  $campaignEnrollment
     * @return \Illuminate\Http\Response
     */
    public function show(CampaignEnrollment $campaignEnrollment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Api\CampaignEnrollment  $campaignEnrollment
     * @return \Illuminate\Http\Response
     */
    public function edit(CampaignEnrollment $campaignEnrollment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Api\CampaignEnrollment  $campaignEnrollment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CampaignEnrollment $campaignEnrollment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Api\CampaignEnrollment  $campaignEnrollment
     * @return \Illuminate\Http\Response
     */
    public function destroy(CampaignEnrollment $campaignEnrollment)
    {
        //
    }
}
