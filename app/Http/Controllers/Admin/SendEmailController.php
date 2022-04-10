<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\SendEmail;
use App\Models\Proposal;
use App\Models\Role;
use App\Models\User;
use Barryvdh\Debugbar\Facades\Debugbar;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Log;
use Throwable;

class SendEmailController extends Controller
{
    public static array $types = ['client', 'manager', 'proposal_clients'];

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
            'emails' => 'sometimes|nullable|array',
            'emails.*' => 'sometimes|nullable|email',
            'ids' => 'sometimes|nullable|array',
            'ids.*' => 'sometimes|nullable|exists:proposals,id',
        ]);
        $data = [];
        foreach ($request->get('emails', []) as $value) $data[] = [
            'email' => $value,
            'data' => []
        ];

        switch ($type) {
            case 'client':
                $proposals = Proposal::whereNotNull('email');
                if ($request->has('email')) {
                    $proposals->where('email', $request['email']);
                }
                $proposals->groupBy('email', 'firstName', 'lastName')
                    ->orderBy('email')
                    ->select(['email', 'firstName', 'lastName'])
                    ->chunk(200, function ($clients) use (&$data) {
                        foreach ($clients as $client) {
                            $data[] = [
                                'email' => $client->email,
                                'data' => ['fullName' => trim("{$client->firstName} {$client->lastName}")]
                            ];
                        }
                    });
                break;
            case 'manager':
                $managers = User::query();
                if ($request->has('email')) $managers
                    ->where('email', $request['email']);
                else $managers->whereHas('roles', function ($query) {
                    $query->whereIn('roles.slug', [Role::MANAGER]);
                });
                $managers->select(['email', 'name', 'surname'])->chunk(200, function ($managers) use (&$data) {
                    foreach ($managers as $manager) {
                        $data[] = [
                            'email' => $manager->email,
                            'data' => ['fullName' => trim("{$manager->name} {$manager->surname}")]
                        ];
                    }
                });
                break;
            case 'proposal_clients':
                Proposal::whereNotNull('email')
                    ->whereIn('id', $request->get('ids', []))
                    ->groupBy('email', 'firstName', 'lastName')
                    ->orderBy('email')
                    ->select(['email', 'firstName', 'lastName'])
                    ->chunk(200, function ($clients) use (&$data) {
                        foreach ($clients as $client) {
                            $data[] = [
                                'email' => $client->email,
                                'data' => ['fullName' => trim("{$client->firstName} {$client->lastName}")]
                            ];
                        }
                    });
                break;
        }

        try {
            if (empty($data)) throw new Exception(__('empty'), 422);
            foreach ($data as $key => $item) {
                Mail::to($item['email'])->later(now()->addSeconds($key), new SendEmail($request['message'], $item['data']));
            }
            $status = 'success';
        } catch (Throwable $exception) {
            if ($exception->getCode() !== 422) {
                Log::error("SendEmailController::send {$exception->getMessage()}");
            }
            $status = 'error';
        }
        $msg = $status === 'success'
            ? __('Message sent successfully')
            : __("Whoops! Something went wrong.");
        return $request->ajax()
            ? response()->json(['status' => $status, 'msg' => $msg])
            : redirect()->back()->with($status, $msg);
    }
}
