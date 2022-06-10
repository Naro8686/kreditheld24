<?php

namespace App\Http\Controllers;

use App\Actions\SendEmail;
use App\Models\Role;
use Illuminate\Http\Request;

class SendEmailController extends Controller
{
    public static array $types = ['client', 'manager', 'proposal_clients'];

    public function __construct()
    {
        $this->middleware(['auth', 'role:' . Role::MANAGER]);
    }

    public function send(Request $request, SendEmail $sendEmail)
    {
        $request->validate([
            'message' => 'required|string',
            'attachment' => 'sometimes|nullable|file',
            'subject' => 'sometimes|nullable|string|max:191',
            'ids' => 'sometimes|nullable|array'
        ]);
        $data = [];
        $attachment = null;
        $subject = $request->get('subject');
        $proposals = auth()->user()->proposals()->whereNotNull('proposals.email');
        if (!$request->has('select_all')) {
            $proposals->whereIn('proposals.id', $request->get('ids', []));
        }
        if ($file = $request->file('attachment')) {
            $attachment = storage_path("app/tmp/{$file->storeAs('/',  $file->getClientOriginalName(), 'tmp')}");
        }
        $proposals->groupBy('proposals.email', 'proposals.firstName', 'proposals.lastName')
            ->orderBy('proposals.email')
            ->select(['proposals.email', 'proposals.firstName', 'proposals.lastName'])
            ->chunk(200, function ($clients) use (&$data, $attachment,$subject) {
                foreach ($clients as $client) {
                    $data[] = [
                        'email' => $client->email,
                        'data' => [
                            'fullName' => trim("{$client->firstName} {$client->lastName}"),
                            'subject' => $subject,
                            'attachment' => $attachment
                        ]
                    ];
                }
            });
        $status = $sendEmail->handle($request['message'], $data);
        $msg = $status === 'success'
            ? __('Message sent successfully')
            : __("Whoops! Something went wrong.");
        return $request->ajax()
            ? response()->json(['status' => $status, 'msg' => $msg])
            : redirect()->back()->with($status, $msg);
    }
}
