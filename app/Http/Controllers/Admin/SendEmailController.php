<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Actions\SendEmail;
use App\Models\Proposal;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

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

    public function send(Request $request, $type, SendEmail $sendEmail)
    {
        $request->validate([
            'message' => 'required|string',
            'email' => 'sometimes|email',
            'emails' => 'sometimes|nullable|array',
            'emails.*' => 'sometimes|nullable|email',
            'ids' => 'sometimes|nullable|array',
            'attachment' => 'sometimes|nullable|file',
            'subject' => 'sometimes|nullable|string|max:191',
            'ids.*' => 'sometimes|nullable|exists:proposals,id',
        ]);
        $data = [];
        $attachment = null;
        $subject = $request->get('subject');
        if ($file = $request->file('attachment')) {
            $attachment = storage_path("app/tmp/{$file->storeAs('/',  $file->getClientOriginalName(), 'tmp')}");
        }

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
                    ->chunk(200, function ($clients) use (&$data, $attachment, $subject) {
                        foreach ($clients as $client) {
                            $data[] = [
                                'email' => $client->email,
                                'data' => [
                                    'fullName' => trim("{$client->firstName} {$client->lastName}"),
                                    'subject' => $subject,
                                    'attachment' => $attachment,
                                ]
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
                $managers->select(['email', 'name', 'surname'])->chunk(200, function ($managers) use (&$data, $attachment, $subject) {
                    foreach ($managers as $manager) {
                        $data[] = [
                            'email' => $manager->email,
                            'data' => [
                                'fullName' => trim("{$manager->name} {$manager->surname}"),
                                'subject' => $subject,
                                'attachment' => $attachment
                            ]
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
                    ->chunk(200, function ($clients) use (&$data, $attachment, $subject) {
                        foreach ($clients as $client) {
                            $data[] = [
                                'email' => $client->email,
                                'data' => [
                                    'fullName' => trim("{$client->firstName} {$client->lastName}"),
                                    'attachment' => $attachment,
                                    'subject' => $subject,
                                ]
                            ];
                        }
                    });
                break;
        }
        $status = $sendEmail->handle($request['message'], $data);
        $msg = $status === 'success'
            ? __('Message sent successfully')
            : __("Whoops! Something went wrong.");
        return $request->ajax()
            ? response()->json(['status' => $status, 'msg' => $msg])
            : redirect()->back()->with($status, $msg);
    }
}
