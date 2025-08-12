<?php
 
namespace App\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
	/**
	 * Display the user profile.
	 */
	public function profile()
	{
		$user = Auth::user();
		return view('user.profile', compact('user'));
	}

	/**
	 * Update the user profile.
	 */
	public function updateProfile(Request $request)
	{
		$user = Auth::user();
		$validator = Validator::make($request->all(), [
			'name' => 'required|string|max:255',
			'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
			'password' => 'nullable|string|min:8|confirmed',
		]);
		
		if ($validator->fails())
			return redirect()->back()->withErrors($validator)->withInput();
		$user->name = $request->input('name');
		$user->email = $request->input('email');

		if ($request->filled('password'))
			$user->password = Hash::make($request->input('password'));
		$user->save();
		return redirect()->route('user.profile')->with('success', 'Profile updated successfully.');
	}
}