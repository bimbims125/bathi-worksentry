<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Geofence;
use Illuminate\Http\Request;

class GeofenceController extends Controller
{
    public function index()
    {
        $geofences = Geofence::all();
        // dd($geofences);
        return view('pages.admin.geofence.index', compact('geofences'));
    }
}
