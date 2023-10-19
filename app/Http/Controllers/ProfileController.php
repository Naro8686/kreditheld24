<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProposalRequest;
use App\Http\Requests\UserRequest;
use App\Models\Proposal;
use App\Models\Role;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Log;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }


    public function index()
    {
        $user = auth()->user();
        $invoices = $user->proposals()->whereNotNull('invoice_file')->get();
        return view('profile.index', compact('invoices'));
    }

    public function update(UserRequest $request)
    {
        $user = auth()->user();
        $user->email = $request->get('email', $user->email);
        $user->name = $request->get('name', $user->name);
        $user->surname = $request->get('surname', $user->surname);
        $user->phone = $request->get('phone', $user->phone);
        $user->city = $request->get('city', $user->city);
        $user->street = $request->get('street', $user->street);
        $user->house = $request->get('house', $user->house);
        $user->postcode = $request->get('postcode', $user->postcode);
        $user->birthday = $request->get('birthday', $user->birthday);
        $user->card_number = $request->get('card_number', $user->card_number);
        $user->tax_number = $request->get('tax_number', $user->tax_number);
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
        $user->save();
        return redirect()->back()->with('success', __('Data saved successfully'));
    }
}
