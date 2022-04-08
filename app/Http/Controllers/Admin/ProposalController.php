<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProposalRequest;
use App\Http\Resources\ProposalResource;
use App\Models\Proposal;
use App\Models\Role;
use Exception;
use Illuminate\Support\Facades\Log;

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
                ->of(Proposal::with(['user', 'category', 'category.parent'])->select('proposals.*')->limit(1))
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
                ->editColumn('birthday', function ($proposal) {
                    return $proposal->birthday->format('d.m.Y');
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
                    return "<div>
                                <input class='user_id' type='hidden' value='$proposal->user_id'>
                                <a class='email' href='$link'>$name</a>
                            </div>";
                })
                ->editColumn('email', function ($proposal) {
                    $link = route('admin.email.index', ['type' => 'client', 'email' => $proposal->email]);
                    return "<a href='$link'>$proposal->email</a>";
                })
                ->addColumn('action', function ($proposal) {
                    $linkEdit = route('admin.proposals.edit', [$proposal->id]);
                    $linkDelete = route('admin.proposals.delete', [$proposal->id]);
                    return "<div class='d-flex justify-content-between' role='group'>
                                <a href='$linkEdit' type='button' class='btn btn-sm btn-info mr-1'>
                                    <i class='fa fa-eye'></i>
                                </a>
                                <button type='button' class='btn btn-sm btn-danger mr-1' data-toggle='modal'
                                        data-target='#confirmModal'
                                        data-url='$linkDelete'><i class='fa fa-trash'></i>
                                </button>
                            </div>";
                })
                ->rawColumns(['id', 'email', 'user.email', 'action'])
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
        $request->merge([
            'notice' => $request->get('status') !== Status::REVISION
                ? null : $request->get('notice')
        ]);
        $success = $proposal->saveData($request->only([
            "gender",
            "childrenCount",
            "rentAmount",
            "applicantType",
            "objectData",
            "number",
            "status",
            "notice",
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
            "uploads"
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
            $proposal->delete();
            return redirect()->route('admin.proposals.index')->with('success', __('Proposal deleted'));
        } catch (Exception $exception) {
            Log::error("ProposalController::delete {$exception->getMessage()}");
        }
        return redirect()->back()->with('error', __("Whoops! Something went wrong."));

    }
}
