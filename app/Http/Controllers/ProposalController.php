<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Http\Requests\ProposalRequest;
use App\Models\Proposal;
use App\Models\Role;
use Illuminate\Http\Request;

class ProposalController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:' . Role::MANAGER]);
    }

    public function create()
    {
        return view('proposal.create');
    }

    public function store(ProposalRequest $request)
    {
        $proposal = new Proposal();
        $request->merge(['user_id' => optional($request->user())->id]);
        return $this->defaultFields($proposal, $request, ['user_id']);
    }

    public function index()
    {
        $proposals = auth()->user()->proposals()->orderByDesc('proposals.id')->paginate();
        return view('proposal.index', compact('proposals'));
    }

    public function edit($id)
    {
        $proposal = auth()->user()->proposals()
            ->where('proposals.id', $id)
            ->where('proposals.status', Status::REVISION)
            ->firstOrFail();
        return view('proposal.edit', compact('proposal'));
    }

    public function update($id, ProposalRequest $request)
    {
        $proposal = auth()->user()->proposals()
            ->where('proposals.id', $id)
            ->where('proposals.status', Status::REVISION)
            ->firstOrFail();
        $request->merge([
            'status' => Status::PENDING,
            'notice' => null,
        ]);
        return $this->defaultFields($proposal, $request, ['status', 'notice']);
    }

    /**
     * @param $proposal
     * @param ProposalRequest $request
     * @param array $merge
     * @return \Illuminate\Http\JsonResponse
     */
    public function defaultFields($proposal, ProposalRequest $request, $merge = []): \Illuminate\Http\JsonResponse
    {
        $default = array_merge([
            "creditType",
            "creditComment",
            "deadline",
            "otherCredit",
            "monthlyPayment",
            "creditAmount",
            "residenceDate",
            "firstName",
            "lastName",
            "street",
            "house",
            "postcode",
            "city",
            "birthday",
            "phoneNumber",
            "email",
            "birthplace",
            "residenceType",
            "familyStatus",
            "insurance",
            "oldAddress",
            "spouse",
            "uploads",
        ], $merge);
        $success = $proposal->saveData($request->only($default), $request->get('allFilesName', []));
        return response()->json(['message' => $success
            ? __("Application sent")
            : __("Whoops! Something went wrong."),
            'redirectUrl' => route('proposal.index'), 'success' => $success], $success ? 200 : 500);
    }
}
