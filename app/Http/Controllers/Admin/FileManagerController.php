<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class FileManagerController extends Controller
{
    public function index()
    {
        return view('admin.file-manager.index');
    }
}
