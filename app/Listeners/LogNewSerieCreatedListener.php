<?php

namespace App\Listeners;

use App\Events\NewSerieCreatedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class LogNewSerieCreatedListener implements ShouldQueue
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
     * @param  NewSerieCreatedEvent  $event
     * @return void
     */
    public function handle(NewSerieCreatedEvent $event)
    {
        $serieName = $event->serieName;
        Log::info('New serie created ' . $serieName);
    }
}
