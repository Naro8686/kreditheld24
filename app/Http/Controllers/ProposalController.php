<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Http\Requests\ProposalRequest;
use App\Models\Proposal;
use App\Models\Role;
use Illuminate\Http\JsonResponse;

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
        $request->validate([
            'agree.privacy_policy' => 'required',
            'agree.personal_data' => 'required',
        ]);
        $proposal = new Proposal();
        $request->merge(['user_id' => optional($request->user())->id]);
        return $this->defaultFields($proposal, $request, ['user_id']);
    }

    public function index()
    {
        try {
            $successful = auth()->user()->proposals()->where('proposals.status', Status::APPROVED);
            $totalSum = $successful->sum('proposals.creditAmount');
            $targetPercent = $successful->count('proposals.id') === 0 || auth()->user()->proposals()->count() === 0 ? 0
                : (int)(($successful->count('proposals.id') / auth()->user()->proposals()->count()) * 100);
            $monthSum = $successful->where('proposals.created_at', '>=', now()->subMonth())->sum('proposals.creditAmount');
        } catch (\Exception $exception) {
            $totalSum = $monthSum = $targetPercent = 0;
        }

        $proposals = auth()->user()->proposals()->orderByDesc('proposals.id')->paginate();
        return view('proposal.index', compact('proposals', 'totalSum', 'monthSum', 'targetPercent'));
    }

    public function draft()
    {
        $proposals = auth()->user()->proposals()->onlyTrashed()->orderByDesc('proposals.id')->paginate();
        return view('proposal.draft', compact('proposals'));
    }

    public function edit($id)
    {
        $proposal = auth()->user()->proposals()
            ->where('proposals.id', $id)
            ->where(function ($query) {
                return $query->where([
                    ['proposals.status', Status::REVISION],
                    ['proposals.deleted_at', null]
                ])->orWhere([
                    ['proposals.deleted_at', '!=', null]
                ]);
            })->withTrashed()->firstOrFail();
        return view('proposal.edit', compact('proposal'));
    }

    public function update($id, ProposalRequest $request)
    {
        $proposal = auth()->user()->proposals()
            ->where('proposals.id', $id)
            ->where(function ($query) {
                return $query->where([
                    ['proposals.status', Status::REVISION],
                    ['proposals.deleted_at', null]
                ])->orWhere([
                    ['proposals.deleted_at', '!=', null]
                ]);
            })
            ->withTrashed()
            ->firstOrFail();

        if (!$request->has('draft')) $request->merge([
            'status' => Status::PENDING,
            'notice' => null,
        ]);
        return $this->defaultFields($proposal, $request, ['status', 'notice']);
    }

    /**
     * @param Proposal $proposal
     * @param ProposalRequest $request
     * @param array $merge
     * @return JsonResponse
     */
    public function defaultFields(Proposal $proposal, ProposalRequest $request, array $merge = []): JsonResponse
    {
        $default = array_merge([
            "gender",
            "childrenCount",
            "rentAmount",
            "applicantType",
            "objectData",
            "category_id",
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
            "deleted_at",
        ], $merge);
        $success = $proposal->saveData($request->only($default), $request->get('allFilesName', []));
        return response()->json(['message' => $success
            ? __("Application sent")
            : __("Whoops! Something went wrong."),
            'redirectUrl' => route('proposal.index'), 'success' => $success], $success ? 200 : 500);
    }
}
