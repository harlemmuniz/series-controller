<?php

namespace App\Listeners;

use App\Events\SerieRemovedEvent;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Storage;

class RemoveSerieCover implements ShouldQueue
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
     * @param  SerieRemovedEvent  $event
     * @return void
     */
    public function handle(SerieRemovedEvent $event)
    {
        $serie = $event->serie;
        if ($serie->cover) {
            Storage::delete($serie->cover);
        }
    }
}
