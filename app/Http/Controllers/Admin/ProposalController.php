<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Exports\ProposalExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProposalRequest;
use App\Http\Resources\ProposalResource;
use App\Models\Proposal;
use App\Models\Role;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;
use Excel;
use Dompdf\Dompdf;

class ProposalController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth', 'role:' . Role::ADMIN]);
    }

    public function index()
    {
        try {
            if (request()->ajax()) return datatables()
                ->of(Proposal::with(['user', 'category', 'category.parent'])->select('proposals.*'))
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
                ->editColumn('id', function ($proposal) {
                    return "<strong>$proposal->id</strong>";
                })
                ->editColumn('user.name', function ($proposal) {
                    return $proposal->user->name ?? $proposal->user->email;
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
                ->addColumn('fullName', function (Proposal $proposal) {
                    $linkInvoice = null;
                    $linkEdit = route('admin.proposals.edit', [$proposal->id]);
                    $linkDelete = route('admin.proposals.delete', [$proposal->id]);
                    if ($proposal->status === Status::APPROVED && !is_null($proposal->invoice_file)) {
                        $linkInvoice = route('readFile', ['path' => $proposal->invoice_file]);
                    }
                    $html = "<div class='d-flex justify-content-start align-items-center' role='group'>";
                    $html .= "<span class='text-sm'>ID: $proposal->id</span>";
                    if ($category = optional(optional($proposal->category)->parent)->name) {
                        $html .= "|<span class='text-sm'>$category</span>";
                    }
                    $html .= "|<a href='$linkEdit' type='button' target='_blank' class='text-sm text-primary edit-link'>
                                    " . __('Show') . "
                                </a>";
                    if (!is_null($linkInvoice)) {
                        $html .= "|<a href='$linkInvoice' target='_blank'
                                   class='text-sm text-success'>
                                   " . __('Invoice') . "</a>";
                    }
                    $html .= "|<a href='#' type='button' class='text-sm text-danger' data-toggle='modal'
                                        data-target='#confirmModal'
                                        data-url='$linkDelete'>" . __('Delete') . "
                                </a>";
                    $html .= "</div>";
                    return "<span class='d-flex'>$proposal->firstName $proposal->lastName</span>" . $html;
                })
                ->filterColumn('fullName', function ($query, $keyword) {
                    $query->whereRaw("CONCAT(`proposals`.`firstName`,  ' ', `proposals`.`lastName`) LIKE ?", ["%$keyword%"]);
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
                ->editColumn('birthday', function ($proposal) {
                    return optional($proposal->birthday)->format('d.m.Y');
                })
                ->filterColumn('birthday', function ($query, $keyword) {
                    $query->whereRaw("DATE_FORMAT(`proposals`.`birthday`,'%d.%m.%Y') LIKE ?", ["%$keyword%"]);
                })
                ->editColumn('deadline', function ($proposal) {
                    return optional($proposal->deadlineDateFormat())->format('d.m.Y');
                })
                ->filterColumn('deadline', function ($query, $keyword) {
                    $query->whereRaw("DATE_FORMAT(DATE_ADD(`proposals`.`created_at`, INTERVAL `proposals`.`deadline` MONTH),'%d.%m.%Y') LIKE ?", ["%$keyword%"]);
                })
                ->editColumn('user.email', function ($proposal) {
                    $email = $proposal->user->email;
                    $name = $proposal->user->name ?? $proposal->user->email;
                    $link = route('admin.email.index', ['type' => 'manager', 'email' => $email]);
                    return "<div class='inline-flex'>
                                <input class='user_id' type='hidden' value='$proposal->user_id'>
                                <a class='email' href='$link'>$email</a>
                            </div>";
                })
                ->editColumn('email', function ($proposal) {
                    $link = route('admin.email.index', ['type' => 'client', 'email' => $proposal->email]);
                    return "<a href='$link'>$proposal->email</a>";
                })
                ->addColumn('action', function ($proposal) {
                    $linkInvoice = null;
                    $linkEdit = route('admin.proposals.edit', [$proposal->id]);
                    $linkDelete = route('admin.proposals.delete', [$proposal->id]);
                    if ($proposal->status === Status::APPROVED && !is_null($proposal->invoice_file)) {
                        $linkInvoice = route('readFile', ['path' => $proposal->invoice_file]);
                    }
                    $html = "<div class='d-flex justify-content-between' role='group'>";
                    $html .= "<a href='$linkEdit' type='button' target='_blank' class='btn btn-sm btn-info mr-1 edit-link'>
                                    <i class='fa fa-eye'></i>
                                </a>";
                    if (!is_null($linkInvoice)) {
                        $html .= "<a href='$linkInvoice' target='_blank'
                                   class='btn btn-sm btn-info mr-1'>
                                   <i class='fas fa-fw fa-file-invoice'></i></a>";
                    }
                    $html .= "<button type='button' class='btn btn-sm btn-danger mr-1' data-toggle='modal'
                                        data-target='#confirmModal'
                                        data-url='$linkDelete'><i class='fa fa-trash'></i>
                                </button>";
                    $html .= "</div>";
                    return $html;
                })
                ->rawColumns(['id', 'email', 'user.email', 'action', 'fullName'])
                ->make(true);
            return view('admin.proposal.index');
        } catch (Exception $e) {
        }
        return response('Error', 500);
    }

    public function edit($id)
    {
        $proposal = Proposal::findOrFail($id);
        $formData = ProposalResource::make($proposal)->toJson();
        return view('admin.proposal.edit', compact('formData', 'proposal'));
    }

    public function update(ProposalRequest $request, $id)
    {
        $proposal = Proposal::findOrFail($id);
        $request->validate([
            "number" => "sometimes|nullable|unique:proposals,number,$id"
        ]);
        $success = $proposal->saveData($request->only([
            "gender",
            "childrenCount",
            "rentAmount",
            "communalAmount",
            "communalExpenses",
            "applicantType",
            "objectData",
            "number",
            "status",
            "commission",
            "bonus",
            "category_id",
            "creditComment",
            "otherCredit",
            "deadline",
            "residenceDate",
            "monthlyPayment",
            "creditAmount",
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
            "deleted_at"
        ]), $request->get('allFilesName', []));
        return response()->json(['message' => $success
            ? __("Action Saved Successfully")
            : __("Whoops! Something went wrong."),
            'redirectUrl' => route('admin.proposals.index'), 'success' => $success], $success ? 200 : 500);
    }

    public function delete($id)
    {
        $proposal = Proposal::findOrFail($id);
        try {
            $proposal->deleteAllFiles();
            $proposal->forceDelete();
            return redirect()->route('admin.proposals.index')->with('success', __('Proposal deleted'));
        } catch (Exception $exception) {
            Log::error("ProposalController::delete {$exception->getMessage()}");
        }
        return redirect()->back()->with('error', __("Whoops! Something went wrong."));

    }

    public function export($id, Request $request)
    {
        $proposal = Proposal::findOrFail($id);
        $ext = $request->get('ext', 'xlsx');
        try {
            if (in_array($ext, ['pdf', 'csv', 'xlsx'])) {
                $fileName = "proposal_{$proposal->id}.$ext";
                if ($ext === 'pdf') {
                    $dompdf = new Dompdf(['defaultFont' => 'DejaVu Serif']);
                    $dompdf->loadHtml(view('proposal.pdf', compact('proposal'))->render());
                    $dompdf->setPaper('A4');
                    $dompdf->render();
                    $dompdf->stream($fileName);
                    return null;
                }
                return Excel::download(new ProposalExport($id), $fileName);
            }
        } catch (Throwable $exception) {
            Log::error("export {$exception->getMessage()}");
        }
        return back()->with('error', 'Error');
    }
}
