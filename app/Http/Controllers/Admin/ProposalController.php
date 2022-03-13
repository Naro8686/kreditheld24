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
        $proposals = Proposal::orderByDesc('id')->paginate();
        return view('admin.proposal.index', compact('proposals'));
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
            "number",
            "status",
            "notice",
            "commission",
            "bonus",
            "creditType",
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
