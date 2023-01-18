<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Bavix\Wallet\Internal\Events\TransactionCreatedEventInterface;
use Illuminate\Support\Facades\Log;

class MyWalletTransactionCreatedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(TransactionCreatedEventInterface $event): void
    {
        Log::info(json_encode($event->getId().'  '.$event->getWalletId().'  '.$event->getType()));
    }
}
