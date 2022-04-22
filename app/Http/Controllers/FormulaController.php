<?php

namespace App\Http\Controllers;

use App\Models\Formula;

class FormulaController extends Controller
{
    public function index()
    {
        $formulas = Formula::paginate();
        return view('formulas', compact('formulas'));
    }
}
