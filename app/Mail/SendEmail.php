<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $message;
    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($message, $data = [])
    {
        $this->message = $message;
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject(config('app.name'))
            ->markdown('emails.send', [
                'message' => $this->message,
                'data' => $this->data,
            ]);
    }
}
