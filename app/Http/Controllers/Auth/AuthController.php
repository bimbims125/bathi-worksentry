<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\RedirectResponse;

class AuthController extends Controller
{
    public function index(){
        return view('pages.auth.login');
    }

    public function authenticate(Request $request){
        // Validate user input
        $validatedData = $request->validate([
            'username' => 'required|string',  // 'username' can be either an email or a username, depending on your implementation
            'password' => 'required|string|min:5',
        ]);

        // Retrieve the validated username and password
        $username = $validatedData['username'];
        $password = $validatedData['password'];

        // Check if the username is an email or a regular username
        $user = User::where('username', $username)->first();

        // dd(User::where('role', 'subadmin')->get);
        // If the user is found and the password matches
        if ($user && Hash::check($password, $user->password)) {
            // Use Auth::attempt() to log in the user and create a session
            if (Auth::attempt(['username' => $username, 'password' => $password])) {
                // After successful login, check if the user is an admin
                if (Auth::user()->role == 'admin' || Auth::user()->role == 'superadmin') {
                    // Redirect to admin dashboard or admin-specific page
                    return redirect()->route('admin.dashboard.index');

                } else {
                    // Redirect to the regular user dashboard
                    return redirect()->route('user.clock-in.index');
                }
                // } else if (Auth::user()->role == 'subadmin'){
                //     return redirect()->route('vehicle_inspection.index');

            } else {
                // If credentials are invalid
                return back()->withErrors(['username' => 'Invalid Username Or Email'])->withInput();
            }
        } else {
            // If no user is found or password does not match
            return back()->withErrors(['username' => 'Invalid Username Or Email'])->withInput();
        }
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/auth/login');
    }
}
