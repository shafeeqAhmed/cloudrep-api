<?php

namespace App\Listeners;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Bavix\Wallet\Internal\Events\BalanceUpdatedEventInterface;


class MyBalanceUpdatedListener
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
    public function handle(BalanceUpdatedEventInterface $event): void
    {
        Log::info(json_encode($event->getWalletId().'  '.$event->getWalletUuid().'  '.$event->getBalance()));
    }
}
