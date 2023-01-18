<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Twilio\Rest\Client;

class TaskController extends ApiController
{
    private $client;
    private $workspaceSid;
    private $workflowSid;
    private $taskSid;
    private $reservationSid;

    public function __construct()
    {

        $this->workspaceSid = 'WS3152c0f41c84706199d4dbd82ed683b3';
        $this->workflowSid = 'WW456fb07f4fdc4f55779dcb6bd90f9273';
        // twilio client intitialization
        $sid = config('general.twilio_account_sid');
        $token = config('general.twilio_token');
        // instantiate a new Twilio Rest Client
        $this->client = new Client($sid, $token);
    }
    public function createTask()
    {
        // create a new task
        $task = $this->client->taskrouter
            ->workspaces($this->workspaceSid)
            ->tasks
            ->create(array(
                'attributes' => '{"selected_language": "es"}',
                'workflowSid' => $this->workflowSid,
            ));
        return $this->respond([
            'status' => true,
            'message' => 'New Task Created Successfully!',
            'data' => $task
        ]);
    }
    public function acceptReservation()
    {
        $this->taskSid = 'WT57619e04d5696cf88d6d65f6e7a526e7';
        $this->reservationSid = 'WR3e286c31bfe86cfc3419534915e28acb';
        $result = $this->client->taskrouter
            ->workspaces($this->workspaceSid)
            ->tasks($this->taskSid)
            ->reservations($this->reservationSid)
            ->update(['reservationStatus' => 'accepted']);
        return $this->respond([
            'status' => true,
            'message' => 'Task has been accepted successfully!',
            'data' => $result
        ]);
    }
    public function deleteTask(Request $request)
    {

        // return $this->client->taskrouter->v1->workspaces($this->workspaceSid)->tasks($request->sid)->delete();
    }
    public function getReservation()
    {
        $reservations = $this->client->taskrouter->v1->workspaces($this->workspaceSid)
            ->tasks("WT44cf33a97c2089a463fd0391a39fa843")
            ->reservations
            ->read([], 20);
        return $this->respond([
            'status' => true,
            'message' => 'New Task Created Successfull!',
            'data' => $reservations
        ]);
    }
    public function updateTask(Request $request)
    {
        $task = $this->client->taskrouter->v1->workspaces($this->workspaceSid)
            ->tasks($request->sid)
            ->update(
                [
                    "assignmentStatus" => "completed",
                    "reason" => "re-open task"
                ]
            );
    }
}
