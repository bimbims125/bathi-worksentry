<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $absen = Attendance::all();
        return view('pages.admin.dashboard.index', compact('absen'));
    }
}
