<?php

namespace App\Http\Controllers\user;

use App\Models\Geofence;
use App\Models\Attendance;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class ClockInController extends Controller
{
    public function index()
    {
        $geofences_polygon = Geofence::select('id', 'name', 'coordinates', 'status')->where('type', 'polygon')->get();
        return view('pages.user.attendance.clock_in', compact('geofences_polygon'));
    }

    public function store(Request $request){
        // Validate input from the user
        $validated_data = $request->validate([
            'location'         => 'required|string|max:255', // Location must be provided
            'clockin_picture'  => 'nullable|string', // Expect a base64 string for checkin_picture
            'latlong'
        ]);

        if ($validated_data['clockin_picture'] != null) {
            $base64Image = str_replace('data:image/png;base64,', '', $validated_data['clockin_picture']);
            $base64Image = str_replace(' ', '+', $base64Image);

            $imageName = time() . '.' . 'png';
            $imagePath = 'clockin_pictures/' . $imageName;

            Storage::disk('public')->put($imagePath, base64_decode($base64Image));
            $validated_data['clockin_picture'] = $imagePath; // Simpan path relatif
        }
        // Save the data to the database
        $attendance = new Attendance();
        $attendance->user_id = auth()->user()->id;
        $attendance->location = $validated_data['location'];
        $attendance->clockin_picture = $validated_data['clockin_picture'];
        $attendance->latlong = $validated_data['latlong'];
        $attendance->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Clock in successful',
            'data' => $attendance
        ], 201);
    }
}
