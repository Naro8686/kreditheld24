<?php

namespace App\Http\Controllers\Admin;

use App\Constants\Status;
use App\Http\Controllers\Controller;
use App\Models\ProposalNotice;
use App\Models\User;
use Illuminate\Http\Request;

class ProposalNoticeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        $request->validate([
            'proposal_id' => ['required', 'exists:proposals,id'],
            'message' => ['required', 'string'],
        ]);
        $user = auth()->user();
        if (!$user->isAdmin()) $user->proposals()
            ->where('proposals.id', $request['proposal_id'])
            ->firstOrFail();

        $notice = new ProposalNotice();
        $notice->proposal_id = $request['proposal_id'];
        $notice->user_id = $user->id;
        $notice->message = $request['message'];
        $notice->status = Status::PENDING;
        $notice->save();
        return response()->json([
            'notices' => ProposalNotice::where('proposal_id', $request['proposal_id'])->get(),
            'success' => true
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
