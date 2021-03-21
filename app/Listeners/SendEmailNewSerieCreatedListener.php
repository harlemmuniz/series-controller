<?php

namespace App\Listeners;

use App\Events\NewSerieCreatedEvent;
use App\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendEmailNewSerieCreatedListener  implements ShouldQueue
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
        $seasonsQty = $event->seasonsQty;
        $episodesQty = $event->episodesQty;

        $users = User::all();

        foreach ($users as $indice => $user) {
            $multiplicador = $indice + 1;
            $mail = new \App\Mail\NewSerieMail(
                $user->name,
                $serieName,
                $seasonsQty,
                $episodesQty
            );

            $mail->subject('Series: New Serie Added');
            $when = now()->addSeconds($multiplicador * 10);
            \Illuminate\Support\Facades\Mail::to($user)->later($when, $mail);
        }
    }
}
