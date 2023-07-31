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
            if (request()->ajax()) return $this->ajaxDataTable(auth()->user()->proposals()->whereNull('archived_at')->with(['user', 'category', 'category.parent'])->select('proposals.*'));
            $user = auth()->user();
            $successful = $user->proposals()->where('proposals.status', Status::APPROVED);
            $totalSum = Proposal::moneyFormat($successful->sum('proposals.creditAmount'));
            $targetPercent = $user->targetPercent();
            $monthSum = Proposal::moneyFormat($successful->where('proposals.created_at', '>=', now()->subMonth())->sum('proposals.creditAmount'));
            return view('proposal.index', compact('totalSum', 'monthSum', 'targetPercent'));
        } catch (Throwable $e) {
        }
        return response('Error', 500);
    }

    public function draft()
    {
        if (request()->ajax()) return $this->ajaxDataTable(auth()
            ->user()->proposals()
            ->onlyTrashed()->with(['user', 'category', 'category.parent'])
            ->select('proposals.*'));
        return view('proposal.draft');
    }

    public function archive()
    {
        if (request()->ajax()) return $this->ajaxDataTable(auth()
            ->user()->proposals()
            ->archived()->with(['user', 'category', 'category.parent'])
            ->select('proposals.*'));
        return view('proposal.archive');
    }

    public function sendToArchive($id)
    {
        $proposal = auth()->user()->proposals()
            ->whereNull('proposals.archived_at')
            ->withTrashed()
            ->findOrFail($id);
        $proposal->archived_at = now();
        $success = $proposal->save();
        return redirect()->route('proposal.archive')
            ->with(
                $success ? 'success' : 'error',
                $success ? __("Archived") : __("Whoops! Something went wrong.")
            );
    }

    public function edit($id)
    {
        $proposal = auth()->user()->proposals()
            ->where('proposals.id', $id)
            ->withTrashed()->firstOrFail();
        return view('proposal.edit', compact('proposal'));
    }

    public function update($id, ProposalRequest $request)
    {
        $proposal = auth()->user()->proposals()
            ->where('proposals.id', $id)
            ->withTrashed()
            ->firstOrFail();

        if (!$request->isDraft()) {
            $proposal->notices()->update(['status' => Status::APPROVED]);
            $request->merge(['status' => Status::PENDING]);
        }
        return $this->defaultFields($proposal, $request, ['status']);
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
            "notice",
        ], $merge);
        $isDraft = $request->isDraft();
        $success = $proposal->saveData($request->only($default), $request->get('allFilesName', []));
        $redirectUrl = $isDraft ? route('proposal.draft') : route('proposal.index');
        return response()->json(['message' => $success
            ? $isDraft ? __("Saved.") : __("Application sent")
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
                return match ($proposal->deadlineStatus()) {
                    Status::DEADLINE_ENDS => 'bg-amber-400',
                    Status::DEADLINE_EXPIRED => 'bg-red-400',
                    default => 'bg-white',
                };
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
                return $proposal::CURRENCY . $proposal->creditAmount;
            })
            ->editColumn('status', function ($proposal) {
                return trans("status.$proposal->status");
            })
            ->editColumn('payoutAmount', function ($proposal) {
                return $proposal::CURRENCY . $proposal->payoutAmount;
            })
            ->editColumn('created_at', function ($proposal) {
                return $proposal->created_at->format('d.m.Y');
            })
            ->filterColumn('created_at', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(`proposals`.`created_at`,'%d.%m.%Y %H:%i:%s') LIKE ?", ["%$keyword%"]);
            })
            ->addColumn('fullName', function (Proposal $proposal) {
                $linkEdit = route('proposal.edit', [$proposal->id]);
                $linkDuplicate = route('proposal.duplicate', [$proposal->id]);
                $linkDelete = route('proposal.delete', [$proposal->id]);
                $linkInvoice = null;
                $sendToArchive = route('proposal.sendToArchive', [$proposal->id]);
                $archived = !is_null($proposal->archived_at);
                if ($proposal->status === Status::APPROVED && !is_null($proposal->invoice_file)) {
                    $linkInvoice = route('readFile', ['path' => $proposal->invoice_file]);
                }
                $html = "<div class='row-actions d-flex align-items-center' role='group'>";
                $html .= "<span class='text-sm'>ID: $proposal->id</span>";
                if ($category = optional(optional($proposal->category)->parent)->name) {
                    $html .= "|<span class='text-sm'>$category</span>";
                }
                $html .= "|<a href='$linkEdit' type='button' target='_blank' class='text-sm text-primary edit-link'>
                                    " . __('Show') . "
                                </a>";

                if (!$archived) {
                    $html .= "|<a href='$linkDuplicate'
                                   class='text-sm text-primary'>" . __('Duplicate') . "
                         </a>";
                    $html .= "|<a href='#' type='button' class='text-sm text-primary' data-toggle='modal'
                                        data-method='PUT'
                                        data-title='" . __('Send to archive') . "'
                                        data-target='#confirmModal'
                                        data-url='$sendToArchive'>" . __('Archive the project') . "
                                </a>";
                }

                if (!is_null($linkInvoice)) {
                    $html .= "|<a href='$linkInvoice' target='_blank'
                                   class='text-sm text-success'>
                                   " . __('Invoice') . "</a>";
                }

                if ($proposal->trashed()) {
                    $html .= "|<a href='#' type='button' class='text-sm text-danger' data-toggle='modal'
                                        data-target='#confirmModal'
                                        data-url='$linkDelete'>" . __('Delete') . "
                                </a>";
                }

                $html .= "</div>";
                return "<span class='row-title d-flex'>$proposal->firstName $proposal->lastName</span>" . $html;
            })
            ->filterColumn('fullName', function ($query, $keyword) {
                $query->whereRaw("CONCAT(`proposals`.`firstName`,  ' ', `proposals`.`lastName`) LIKE ?", ["%$keyword%"]);
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
            ->addColumn('action', function (Proposal $proposal) {
                $linkEdit = route('proposal.edit', [$proposal->id]);
                $linkDuplicate = route('proposal.duplicate', [$proposal->id]);
                $linkDelete = route('proposal.delete', [$proposal->id]);
                $linkInvoice = null;
                if ($proposal->status === Status::APPROVED && !is_null($proposal->invoice_file)) {
                    $linkInvoice = route('readFile', ['path' => $proposal->invoice_file]);
                }
                $html = "<div class='d-flex justify-content-between' role='group'>";
                if ($proposal->trashed() || $proposal->isRevision() || $proposal->isApproved()) {
                    $html .= "<a href='$linkEdit' target='_blank'
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
                if (!is_null($linkInvoice)) {
                    $html .= "<a href='$linkInvoice' target='_blank' title='Invoice'
                                   class='btn btn-sm btn-info mr-1'>
                                   <i class='fas fa-fw fa-file-invoice'></i></a>";
                }

                $html .= "</div>";
                return $html;
            })
            ->rawColumns(['number', 'email', 'action', 'fullName'])
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
