<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\SendEmailToManager;
use App\Models\Role;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Log;

class SendEmailToManagerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:' . Role::ADMIN]);
    }

    public function index($manager_id = null)
    {
        $manager = User::find($manager_id);
        return view('admin.email.send', compact('manager'));
    }

    public function send(Request $request, $manager_id = null)
    {
        $request->validate([
            'message' => 'required|string'
        ]);
        $emails = [];
        if (!is_null($manager_id)) {
            $manager = User::findOrFail($manager_id);
            $emails[] = $manager->email;
        } else User::whereHas('roles', function ($query) {
            $query->whereIn('roles.slug', [Role::MANAGER]);
        })->select('email')->chunk(200, function ($managers) use (&$emails) {
            foreach ($managers as $manager) $emails[] = $manager->email;
        });
        try {
            foreach ($emails as $key => $email) {
                Mail::to($email)->later(now()->addSeconds($key), new SendEmailToManager($request['message']));
            }
            $status = 'success';
        } catch (Exception $exception) {
            Log::error("SendEmailToManagerController::send {$exception->getMessage()}");
            $status = 'error';
        }
        return redirect()->back()->with($status, $status === 'success'
            ? __('Message sent successfully')
            : __("Whoops! Something went wrong."));
    }
}
