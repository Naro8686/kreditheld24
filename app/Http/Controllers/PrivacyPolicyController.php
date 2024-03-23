<?php

namespace App\Http\Controllers;

class PrivacyPolicyController extends Controller
{
    public function __invoke()
    {
        return response()->file(public_path('PrivacyPolicy.pdf'));
    }
}
