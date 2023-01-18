<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function sendEmail()
    {
        $url = 'https://www.google.com';
        $to_name = 'Admin';
        $to_email = 'shafeeq.ahmed541@gmail.com';
        $data = [
            'name' => 'cloudrepai',
            'body' => 'This is a test email by cloudrepai',
            'url' => $url
        ];
        Mail::send('mail', $data, function ($message) use ($to_name, $to_email) {
            $message->to($to_email, $to_name)
                ->subject('Laravel Test Mail');
        });
    }
}
