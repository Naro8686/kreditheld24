<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProposalRequest;
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
        return view('profile.index');
    }

    public function update(Request $request)
    {
        if ($request->has('phone')) $request->merge([
            "phone" => Str::replace('+', '', $request['phone']),
        ]);

        $request->validate([
            'email' => 'required|email:rfc,dns',
            'name' => 'required|string|min:2|max:191',
            'surname' => 'sometimes|nullable|string|min:2|max:191',
            'phone' => 'sometimes|nullable|numeric|phone_number:6,50',
            'address' => 'sometimes|nullable|string|min:5|max:191',
            'card_number' => 'sometimes|nullable|digits_between:12,18',
            'birthday' => 'sometimes|nullable|date|before:today|date_format:Y-m-d',
        ], [
            'card_number.digits_between' => __('Enter the correct number'),
            'phone.phone_number' => __('Enter the correct number')
        ]);

        $user = auth()->user();
        $user->email = $request->get('email', $user->email);
        $user->name = $request->get('name', $user->name);
        $user->surname = $request->get('surname', $user->surname);
        $user->phone = $request->get('phone', $user->phone);
        $user->address = $request->get('address', $user->address);
        $user->birthday = $request->get('birthday', $user->birthday);
        $user->card_number = $request->get('card_number', $user->card_number);
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
        $user->save();
        return redirect()->back();
    }
}
