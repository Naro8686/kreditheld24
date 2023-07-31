<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Notifications\CreateAccount;
use DB;
use Exception;
use Illuminate\Http\Request;
use Log;
use Throwable;

class ManagerController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:' . Role::ADMIN]);
    }

    public function index()
    {
        $managers = User::whereHas('roles', function ($query) {
            $query->whereIn('roles.slug', [Role::MANAGER]);
        })->withCount(['proposals'])->orderByDesc('users.id')->paginate(20);
        return view('admin.managers.index', compact('managers'));
    }

    public function create()
    {
        return view('admin.managers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:5',
        ]);
        try {
            $email = $request['email'];
            $name = $request->get('name') ?: explode('@', $email)[0];
            $password = $request['password'];
            DB::transaction(function () use ($email, $password, $name) {
                /** @var User $manager */
                $managerRole = Role::whereSlug(Role::MANAGER)->first();
                $createProposalsPermission = Permission::whereSlug(Permission::CREATE_PROPOSALS)->first();
                $manager = new User();
                $manager->name = $name;
                $manager->email = $email;
                $manager->email_verified_at = now();
                $manager->password = bcrypt($password);
                $manager->save();
                $manager->roles()->attach($managerRole);
                $manager->permissions()->attach($createProposalsPermission);
                $manager->notify((new CreateAccount($email, $password)));
            });
            return redirect()->route('admin.managers.index')->with('success', __('Account created'));
        } catch (Throwable|Exception $exception) {
            DB::rollBack();
            Log::error("ManagerController::store {$exception->getMessage()}");
        }
        return redirect()->back()->with('error', __("Whoops! Something went wrong."));
    }

    public function show($id)
    {
        $manager = User::findOrFail($id);
        $invoices = $manager->proposals()->whereNotNull('invoice_file')->get();
        return view('admin.managers.show', compact('manager', 'invoices'));
    }

    public function edit($id)
    {
        $manager = User::findOrFail($id);
        return view('admin.managers.edit', compact('manager'));
    }

    public function update(UserRequest $request, $id)
    {
        $manager = User::findOrFail($id);
        $manager->email = $request->get('email', $manager->email);
        $manager->name = $request->get('name', $manager->name);
        $manager->surname = $request->get('surname', $manager->surname);
        $manager->phone = $request->get('phone', $manager->phone);
        $manager->city = $request->get('city', $manager->city);
        $manager->street = $request->get('street', $manager->street);
        $manager->house = $request->get('house', $manager->house);
        $manager->postcode = $request->get('postcode', $manager->postcode);
        $manager->birthday = $request->get('birthday', $manager->birthday);
        $manager->card_number = $request->get('card_number', $manager->card_number);
        $manager->tax_number = $request->get('tax_number', $manager->tax_number);
        $manager->save();
        return redirect()->route('admin.managers.index')->with('success', __('Data saved successfully'));
    }

    public function delete($id)
    {
        $manager = User::findOrFail($id);
        try {
            /** @var User $manager */
            foreach ($manager->proposals as $proposal) {
                $proposal->deleteAllFiles();
            }
            $manager->delete();
            return redirect()->route('admin.managers.index')->with('success', __('Account deleted'));
        } catch (Exception $exception) {
            Log::error("ManagerController::delete {$exception->getMessage()}");
        }
        return redirect()->back()->with('error', __("Whoops! Something went wrong."));
    }
}
