<?php
namespace App\Http\Controllers\Personnel;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user()->load('personnel');
        return view('personnel.dashboard', compact('user'));
    }
}
