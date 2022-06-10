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

    public function build()
    {
        if (!empty($this->data) && isset($this->data['url']) && app()->environment('production')) {
            $this->data['url'] = str_replace('http://', 'https://', $this->data['url']);
        }
        $subject = config('app.name');
        if (isset($this->data['subject']) && !empty($this->data['subject'])) {
            $subject = $this->data['subject'];
        }
        $mail = $this->subject($subject)->markdown('emails.send', [
            'message' => $this->message,
            'data' => $this->data,
        ]);
        if (isset($this->data['attachment']) && !empty($this->data['attachment'])) {
            $mail->attach($this->data['attachment']);
        }
        return $mail;
    }
}
