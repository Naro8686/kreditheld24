<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Proposal;
use App\Models\Role;
use App\Traits\File;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    use File;

    public function __construct()
    {
        $this->middleware(['auth', 'role:' . Role::ADMIN]);
    }

    public function index()
    {
        $proposals = Proposal::paginate();
        return view('admin.dashboard', compact('proposals'));
    }

    public function readFile(Request $request)
    {
        $path = $request->get('path');
        if (!is_null($path) && $data = $this->read($path)) {
            $pathToFile = public_path("storage/{$data['meta']['path']}");
            $headers = $data['headers'];
            return response()->file($pathToFile, $headers);
        }
        return 'not found';
    }
}
