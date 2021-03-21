<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class NewSerieMail extends Mailable
{
    use Queueable, SerializesModels;

    public $username;
    public $name;
    public $seasonsQty;
    public $episodesQty;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($username, $name, $seasonsQty, $episodesQty)
    {
        $this->username = $username;
        $this->name = $name;
        $this->seasonsQty = $seasonsQty;
        $this->episodesQty = $episodesQty;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('mail.serie.newserie');
    }
}
