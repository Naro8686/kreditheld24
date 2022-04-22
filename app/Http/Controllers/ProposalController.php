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
            if (request()->ajax()) return $this->ajaxDataTable(auth()->user()->proposals()->with(['user', 'category', 'category.parent'])->select('proposals.*'));
            $successful = auth()->user()->proposals()->where('proposals.status', Status::APPROVED);
            $totalSum = $successful->sum('proposals.creditAmount');
            $targetPercent = $successful->count('proposals.id') === 0 || auth()->user()->proposals()->count() === 0 ? 0
                : (int)(($successful->count('proposals.id') / auth()->user()->proposals()->count()) * 100);
            $monthSum = $successful->where('proposals.created_at', '>=', now()->subMonth())->sum('proposals.creditAmount');
            return view('proposal.index', compact('totalSum', 'monthSum', 'targetPercent'));
        } catch (\Throwable $e) {
        }
        return response('Error', 500);
    }

    public function old_index()
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
        if (request()->ajax()) return $this->ajaxDataTable(auth()->user()->proposals()->onlyTrashed()->with(['user', 'category', 'category.parent'])->select('proposals.*'));
        return view('proposal.draft');
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

    private function ajaxDataTable($proposalsBuilder){
        return datatables()
            ->of($proposalsBuilder)
            ->addColumn('bgColor', function ($proposal) {
                $bgColor = 'bg-white';
                $diff = null;
                if ($proposal->deadlineDateFormat()) $diff
                    = now()->diff($proposal->deadlineDateFormat());
                if (!is_null($diff)) {
                    if ($diff->invert) $bgColor = 'bg-red-400';
                    else if ($diff->y <= 1) $bgColor = 'bg-amber-400';
                }
                return $bgColor;
            })
            ->addColumn('statusBgColor', function ($proposal) {
                return $proposal->statusBgColor();
            })
            ->editColumn('number', function ($proposal) {
                return "<strong>$proposal->number</strong>";
            })
            ->editColumn('category.name', function ($proposal) {
                return $proposal->category->name ?? '';
            })
            ->editColumn('category.parent.name', function ($proposal) {
                return $proposal->category->parent->name ?? '';
            })
            ->editColumn('creditAmount', function ($proposal) {
                return $proposal->creditAmount . ' ' . $proposal::CURRENCY;
            })
            ->editColumn('status', function ($proposal) {
                return trans("status.$proposal->status");
            })
            ->editColumn('payoutAmount', function ($proposal) {
                return $proposal->payoutAmount . ' ' . $proposal::CURRENCY;
            })
            ->editColumn('created_at', function ($proposal) {
                return $proposal->created_at->format('d.m.Y H:i:s');
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(`proposals`.`created_at`,'%d.%m.%Y %H:%i:%s') LIKE ?", ["%$keyword%"]);
            })

            ->addColumn('fullName', function ($proposal) {
                return "$proposal->firstName $proposal->lastName";
            })
            ->filterColumn('fullName', function ($query, $keyword) {
                $query->whereRaw("CONCAT(`proposals`.`firstName`,  ' ', `proposals`.`lastName`) LIKE ?", ["%$keyword%"]);
            })
            ->editColumn('phoneNumber', function ($proposal) {
                return "<a href='tel:$proposal->phoneNumber'>$proposal->phoneNumber</a>";
            })
            ->editColumn('deadline', function ($proposal) {
                return optional($proposal->deadlineDateFormat())->format('d.m.Y');
            })
            ->filterColumn('deadline', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(DATE_ADD(`proposals`.`created_at`, INTERVAL `proposals`.`deadline` MONTH),'%d.%m.%Y') LIKE ?", ["%$keyword%"]);
            })
            ->editColumn('email', function ($proposal) {
                return "<a href='mailto:$proposal->email'>$proposal->email</a>";
            })
            ->addColumn('action', function ($proposal) {
                $linkEdit = route('proposal.edit', [$proposal->id]);

                return (($proposal->status === \App\Constants\Status::REVISION) || $proposal->trashed())
                    ? "<div class='d-flex justify-content-between' role='group'>
                                <a href='$linkEdit'
                                   class='btn btn-sm btn-info mr-1 edit-link'>
                                   <i class='fas fa-fw fa-edit'></i></a>
                          </div>"
                    : "";
            })
            ->rawColumns(['number', 'email', 'phoneNumber', 'action'])
            ->make(true);
    }
}
