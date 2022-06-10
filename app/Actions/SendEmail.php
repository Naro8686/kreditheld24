<?php

namespace App\Actions;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendEmail
{
    public function handle($message, $data = []): string
    {
        try {
            if (empty($data)) throw new Exception(__('empty'), 422);
            foreach ($data as $key => $item) {
                Mail::to($item['email'])->later(now()->addSeconds($key), new \App\Mail\SendEmail($message, $item['data']));
            }
            $status = 'success';
        } catch (Throwable $exception) {
            if ($exception->getCode() !== 422) {
                Log::error("SendEmailController::send {$exception->getMessage()}");
            }
            $status = 'error';
        }
        return $status;
    }
}
