<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Twilio\Rest\Client;

class SendTextMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    private $client;
    private $to;
    private $from;
    private $message;
    private $service;

    public function __construct($message, $to, $from)
    {
        $sid = config('general.twilio_account_sid');
        $token = config('general.twilio_token');
        $this->client = new Client($sid, $token);

        $this->message = $message;
        $this->to = $to;
        $this->from = $from;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data =  [
            "body" => $this->message,
            "from" =>  $this->from,
            "statusCallback" => config('general.web_hook')
        ];
        $this->client->messages->create($this->to, $data);
    }
}
