<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClockInController extends Controller
{
    public function index()
    {
        return view('pages.user.attendance.clock_in');
    }
}
