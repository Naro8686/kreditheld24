<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProposalRequest;
use App\Models\Proposal;
use App\Models\Role;
use Exception;
use Illuminate\Http\Request;
use Log;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }


    public function index()
    {
        return view('profile.index');
    }

    public function update(Request $request)
    {
        $request->validate(['card_number' => 'required|digits_between:12,18'],
            ['card_number.digits_between' => __('Enter the correct number')]);
        $user = auth()->user();
        $user->card_number = $request['card_number'];
        $user->save();
        return redirect()->back();
    }
}
