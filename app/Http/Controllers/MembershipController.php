<?php

namespace App\Http\Controllers;

class MembershipController extends Controller
{
    public function index()
    {
        return view('auth.select-member');
    }
}
