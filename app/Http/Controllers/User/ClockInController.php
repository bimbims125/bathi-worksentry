<?php

namespace App\Http\Controllers\user;

use App\Models\Geofence;
use App\Models\Attendance;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ClockInController extends Controller
{
    public function index()
    {
        $geofences_polygon = Geofence::select('id', 'name', 'coordinates', 'status')->where('type', 'polygon')->get();
        return view('pages.user.attendance.clock_in', compact('geofences_polygon'));
    }

    public function store(Request $request){

        try{

                // Validate input from the user
                $validated_data = $request->validate([
                    'location'         => 'nullable|string|max:255', // Location must be provided
                    'clock_in_picture'  => 'nullable|string', // Expect a base64 string for checkin_picture
                    'latlong' => 'nullable|string',
                ]);
                if($validated_data['location'] == null){
                    $validated_data['location'] = 'Test Location';
                }
                if ($validated_data['clock_in_picture'] != null) {
                    $base64Image = str_replace('data:image/png;base64,', '', $validated_data['clock_in_picture']);
                    $base64Image = str_replace(' ', '+', $base64Image);

                    $imageName = time() . '.png';

                    // Ambil nama user, misal dari Auth::user()
                    $userName = Auth::user()->name; // atau gunakan $user->name jika sudah didefinisikan
                    $userFolder = Str::slug($userName); // untuk memastikan nama folder aman (tanpa spasi/karakter aneh)

                    $imagePath = "clock_in_pictures/{$userFolder}/{$imageName}";

                    Storage::disk('public')->put($imagePath, base64_decode($base64Image));
                $validated_data['clock_in_picture'] = $imagePath;
            }
            $now = Carbon::now();
            $cutoff = Carbon::createFromTime(14, 45, 0); // Set cutoff time to 2:45 PM

            $isLate = $now->gt($cutoff); // true if current time is after cutoff
            $lateMinutes = $isLate ? $now->diffInMinutes($cutoff) : 0;

            $attendance = new Attendance();
            $attendance->user_id = auth()->user()->id;
            $attendance->location = $validated_data['location'];
            $attendance->clock_in_time = $now;
            $attendance->is_late = $isLate;
            $attendance->late_time = $lateMinutes;
            $attendance->clock_in_picture = $validated_data['clock_in_picture'];
            $attendance->latlong = $validated_data['latlong'];
            $attendance->save();

            return response()->json([
                'status' => 'success',
                'message' => 'Clock in successful',
                'data' => $attendance
            ], 201);

        }catch(\Exception $e){
            dd($e);
        }
    }
}
