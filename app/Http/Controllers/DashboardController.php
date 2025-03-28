<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->isDoctor()) {
            return view('dashboard.doctor');
        } elseif ($user->isPatient()) {
            return view('dashboard.patient');
        }
        return redirect('/login');
    }
}
