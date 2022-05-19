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
        $mail = $this
            ->subject(config('app.name'))
            ->markdown('emails.send', [
                'message' => $this->message,
                'data' => $this->data,
            ]);
        if (isset($this->data['invoice_pdf'])) {
            $mail->attach($this->data['invoice_pdf'], [
                'mime' => 'application/pdf',
                'as' => 'invoice.pdf',
            ]);
        }
        return $mail;
    }
}
