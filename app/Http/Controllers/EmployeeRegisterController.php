<?php

namespace App\Http\Controllers;

use function view;

class EmployeeRegisterController extends Controller
{
    public function index()
    {
        return view('auth.employee-register');
    }
}
