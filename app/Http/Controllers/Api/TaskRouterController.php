<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Twilio\Jwt\TaskRouter\WorkerCapability;
use Twilio\Jwt\TaskRouter\WorkspaceCapability;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class TaskRouterController extends ApiController
{

    private $sid;
    private $token;
    private $workspaceSid;
    private $workerSid;

    public function __construct()
    {
        $this->sid = config('general.twilio_account_sid');
        $this->token = config('general.twilio_auth_token');
        $this->workspaceSid = 'WS3152c0f41c84706199d4dbd82ed683b3';
        // $this->workerSid = 'WK523d9d7d3831bbd6a1a3b9dec5e69ea5';
    }
    public function getWorkerCapability(Request $request)
    {
        // return response()->json($request->user()->worker_sid);
        $this->workerSid = $request->user()->worker_sid;
        $capability = new WorkerCapability($this->sid, $this->token, $this->workspaceSid, $this->workerSid);

        $capability->allowFetchSubresources();
        $capability->allowActivityUpdates();
        $capability->allowReservationUpdates();
        $token = $capability->generateToken();
        // By default, tokens are good for one hour.
        // Override this default timeout by specifiying a new value (in seconds).
        // For example, to generate a token good for 8 hours:
        $token = $capability->generateToken(28800);

        return $this->respond([
            'status' => true,
            'message' => 'Your token has been generate Successfully!',
            'data' => [
                'workerCapability' => $token
            ]
        ]);
    }
    public function getWorkSpaceCapability()
    {
        $capability = new WorkspaceCapability($this->sid, $this->token, $this->workspaceSid);
        $capability->allowFetchSubresources();
        $capability->allowUpdatesSubresources();
        $capability->allowDeleteSubresources();
        $token = $capability->generateToken();
        // By default, tokens are good for one hour.
        // Override this default timeout by specifiying a new value (in seconds).
        // For example, to generate a token good for 8 hours:
        $workerToken = $capability->generateToken(28800);

        return $this->respond([
            'status' => true,
            'message' => 'Your token has been generate Successfully!',
            'data' => [
                'work-spcace-capability-token' => $token
            ]
        ]);
    }
    public function workSpaceCallBackUrl()
    {

        return $this->respond([
            'status' => true,
            'message' => 'workspace call back url!',
            'data' => []
        ]);
    }




    public function assigment(Request $request)
    {

        $assignment_instruction = [
            'instruction' => 'dequeue',
            // 'post_work_activity_sid' => 'WA92871fe67075e6556c02e92de6f4b924',
            // 'from' => '+16472944676' // a verified phone number from your twilio account
        ];


        return $this->respond($assignment_instruction, ['Content-Type', 'application/json']);
    }
}
