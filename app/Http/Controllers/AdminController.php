<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;


class AdminController extends Controller
{
    public function index(){
        return view('admin.index');
    }

    public function settings()
    {
        $admin = Auth::user();
        return view('admin.settings', compact('admin'));
    }

public function updateSetting(Request $request)
{
    // Debugging: Check if auth is working
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


public function users_details()
{
    // Paginate using the custom Users model
    $users = User::orderBy('created_at', 'ASC')->paginate(10);

    // Pass the paginated results to the view
    return view('admin.users', compact('users'));
}

public function users_delete($id)
{
    // Find and delete the user using the custom Users model
    $user = User::find($id);
    if ($user) {
        $user->delete();
        return redirect()->route('admin.users')->with('status', 'User has been deleted successfully');
    }
    return redirect()->route('admin.users')->with('status', 'User not found');
}


public function edit($id)
{
    $user = User::findOrFail($id); // Fetch user by ID
    return view('admin.edit_user', compact('user')); // Pass user data to view
}

public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255|unique:users,email,' . $id,
        'utype' => 'required|string|in:USR,ADM', // Ensure valid user type
    ]);

    $user = User::findOrFail($id); // Fetch user by ID
    $user->name = $request->name;
    $user->email = $request->email;
    $user->utype = $request->utype; // Update user type
    $user->save(); // Save changes

    return redirect()->route('admin.users')->with('success', 'User updated successfully.');
}
}
