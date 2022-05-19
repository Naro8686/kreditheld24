<?php

namespace App\Http\Controllers;

use App\Constants\Status;
use App\Http\Requests\ProposalRequest;
use App\Models\Proposal;
use App\Models\Role;
use Dompdf\Dompdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;
use DataTables;
use Yajra\DataTables\Html\Builder;

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
        if (!$request->isDraft()) $request->validate([
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
            $user = auth()->user();
            $successful = $user->proposals()->where('proposals.status', Status::APPROVED);
            $totalSum = $successful->sum('proposals.creditAmount');
            $targetPercent = $user->targetPercent();
            $monthSum = $successful->where('proposals.created_at', '>=', now()->subMonth())->sum('proposals.creditAmount');
            return view('proposal.index', compact('totalSum', 'monthSum', 'targetPercent'));
        } catch (Throwable $e) {
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
        if (request()->ajax()) return $this->ajaxDataTable(auth()
            ->user()->proposals()
            ->onlyTrashed()->with(['user', 'category', 'category.parent'])
            ->select('proposals.*'));
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

        if (!$request->isDraft()) $request->merge([
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
            "communalAmount",
            "communalExpenses",
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
        $redirectUrl = $request->isDraft() ? route('proposal.draft') : route('proposal.index');
        return response()->json(['message' => $success
            ? __("Application sent")
            : __("Whoops! Something went wrong."),
            'redirectUrl' => $redirectUrl, 'success' => $success], $success ? 200 : 500);
    }

    public function exportToPdf(Request $request)
    {
        $key = "print_{$request->user()->id}";
        if ($request->method() === "POST") {
            return session([$key => $request->all()]);
        }
        $proposal = new Proposal(session($key, []));
        $fileName = 'proposal';
        $dompdf = new Dompdf(['defaultFont' => 'DejaVu Serif']);
        $dompdf->loadHtml(view('proposal.pdf', compact('proposal'))->render());
        $dompdf->setPaper('A4');
        $dompdf->render();
        $dompdf->stream($fileName);
        return null;
    }

    private function ajaxDataTable($proposalsBuilder)
    {
        return DataTables::eloquent($proposalsBuilder)
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
            ->filterColumn('category.parent.name', function ($query, $keyword) {
                $query->whereHas('category.parent', function ($q) use ($keyword) {
                    $q->whereRaw("name LIKE ?", ["%$keyword%"]);
                });
            })
            ->editColumn('creditAmount', function ($proposal) {
                return $proposal->creditAmountFormat() . ' ' . $proposal::CURRENCY;
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
                $linkDuplicate = route('proposal.duplicate', [$proposal->id]);
                $linkDelete = route('proposal.delete', [$proposal->id]);
                $linkInvoice = null;
                if ($proposal->status === Status::APPROVED && !is_null($proposal->invoice_file)) {
                    $linkInvoice = route('readFile', ['path' => $proposal->invoice_file]);
                }
                $html = "<div class='d-flex justify-content-between' role='group'>";
                if ($proposal->trashed() || $proposal->status === Status::REVISION) {
                    $html .= "<a href='$linkEdit' title='Invoice'
                                   class='btn btn-sm btn-info mr-1 edit-link'>
                                   <i class='fas fa-fw fa-edit'></i></a>";
                    if ($proposal->trashed()) {
                        $html .= "<button type='button' class='btn btn-sm btn-danger mr-1' data-toggle='modal'
                                        data-target='#confirmModal'
                                        data-url='$linkDelete'><i class='fa fa-trash'></i>
                                </button>";
                    }
                }
                $html .= "<a href='$linkDuplicate'
                                   class='btn btn-sm btn-info mr-1'>
                                   <i class='fas fa-fw fa-copy'></i></a>";
                if (!is_null($linkInvoice)){
                    $html .= "<a href='$linkInvoice' target='_blank'
                                   class='btn btn-sm btn-info mr-1'>
                                   <i class='fas fa-fw fa-file-invoice'></i></a>";
                }

                $html .= "</div>";
                return $html;
            })
            ->rawColumns(['number', 'email', 'phoneNumber', 'action'])
            ->toJson();
    }

    public function delete($id)
    {
        $proposal = auth()->user()->proposals()->withTrashed()->findOrfail($id);
        try {
            $proposal->deleteAllFiles();
            $proposal->forceDelete();
            return redirect()->back()->with('success', __('Proposal deleted'));
        } catch (Throwable $exception) {
            Log::error("ProposalController::delete {$exception->getMessage()}");
        }
        return redirect()->back()->with('error', __("Whoops! Something went wrong."));
    }

    public function duplicate($id)
    {
        /** @var Proposal $proposal */
        $proposal = auth()->user()->proposals()->withTrashed()->findOrfail($id);
        $newProposal = $proposal->replicate();
        $newProposal->copyFiles();
        $newProposal->bonus = null;
        $newProposal->commission = null;
        $newProposal->number = null;
        $newProposal->notice = null;
        $newProposal->status = Status::PENDING;
        $newProposal->deleted_at = now();
        $newProposal->created_at = now();
        $newProposal->updated_at = now();
        $newProposal->pending_at = null;
        $newProposal->approved_at = null;
        $newProposal->revision_at = null;
        $newProposal->denied_at = null;
        $newProposal->save();
        return redirect()->route('proposal.draft')->with('success', __('Duplicate created'));
    }
}
