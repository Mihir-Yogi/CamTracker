<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    public function index(){
        return view('user.index');
    }

    
    public function settings()
    {
        // Get the authenticated user
        $user = Auth::user();
    
        // Return the account details view and pass the user data
        return view('user.settings', compact('user'));
    }

public function updateSetting(Request $request)
{
    $user = User::find(Auth::id()); // This should return the authenticated user

    // Debugging step: If $user is null, this will show you the problem.
    if (!$user) {
        dd('No authenticated user found. Ensure you are logged in.');
    }

    // Validate the incoming request data
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $user->id, // Ensure $user->id works
        'mobile' => 'required|string|max:15',
        'old_password' => 'nullable|min:6',
        'new_password' => 'nullable|min:6|confirmed',
    ]);

    // Update the user's basic information
    $user->name = $request->input('name');
    $user->email = $request->input('email');
    $user->mobile = $request->input('mobile');

    // Check if the user wants to change the password
    if ($request->filled('old_password') && $request->filled('new_password')) {
        // Verify that the old password matches
        if (Hash::check($request->input('old_password'), $user->password)) {
            // Hash and update the new password
            $user->password = Hash::make($request->input('new_password'));
        } else {
            // Return an error if the old password doesn't match
            return back()->withErrors(['old_password' => 'The current password is incorrect.']);
        }
    }

    // Save the updated user details
    $user->save();

    // Redirect with a success message
    return redirect()->back()->with('status', 'Settings updated successfully.');
}

}
