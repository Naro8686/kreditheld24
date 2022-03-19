<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\SendEmail;
use App\Models\Proposal;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Log;

class SendEmailController extends Controller
{
    public static array $types = ['client', 'manager'];

    public function __construct()
    {
        $this->middleware(['auth', 'role:' . Role::ADMIN]);
    }

    public function index(Request $request, $type)
    {
        $data = ['type' => $type];
        if ($request->has('email')) $data['email'] = $request['email'];

        return view('admin.email.send', compact('data'));
    }

    public function send(Request $request, $type)
    {
        $request->validate([
            'message' => 'required|string',
            'email' => 'sometimes|email',
        ]);
        $emails = [];
        if ($request->has('email')) $emails[] = $request['email'];
        else switch ($type) {
            case 'client':
                Proposal::whereNotNull('email')
                    ->groupBy('email')
                    ->orderBy('email')
                    ->select('email')
                    ->chunk(200, function ($clients) use (&$emails) {
                        foreach ($clients as $client) $emails[] = $client->email;
                    });
                break;
            case 'manager':
                User::whereHas('roles', function ($query) {
                    $query->whereIn('roles.slug', [Role::MANAGER]);
                })->select('email')->chunk(200, function ($managers) use (&$emails) {
                    foreach ($managers as $manager) $emails[] = $manager->email;
                });
                break;
        }

        if (empty($emails)) return redirect()->back()->with('error', __('empty'));
        try {
            foreach ($emails as $key => $email) {
                Mail::to($email)->later(now()->addSeconds($key), new SendEmail($request['message']));
            }
            $status = 'success';
        } catch (Exception $exception) {
            Log::error("SendEmailController::send {$exception->getMessage()}");
            $status = 'error';
        }
        return redirect()->back()->with($status, $status === 'success'
            ? __('Message sent successfully')
            : __("Whoops! Something went wrong."));
    }
}
