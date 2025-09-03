<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        // Validate the registration data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => ['required', 'string', 'regex:/^[0-9]{11}$|^[0-9]{14}$/'],
            'nid' => ['required', 'string', 'regex:/^[0-9]{13}$|^[0-9]{17}$/'],
            'dob' => 'required|date',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'phone.regex' => 'Phone number must be exactly 11 or 14 digits.',
            'nid.regex' => 'NID number must be exactly 13 or 17 digits.',
            'password.min' => 'Password must be at least 8 characters long.',
            'password.confirmed' => 'Password confirmation does not match.',
        ]);

        // Check if NID exists in government database and verify details
        $nidRecord = DB::table('nid_db')
            ->where('nid_number', $request->nid)
            ->where('name', $request->name)
            ->where('dob', $request->dob)
            ->first();

        if (!$nidRecord) {
            return back()->withErrors([
                'nid_verification' => 'NID verification failed. Please ensure your name, NID number, and date of birth match exactly with government records.'
            ])->withInput();
        }

        // Create the user
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'contact_number' => $request->phone,
            'nid_number' => $request->nid,
            'dob' => $request->dob,
            'password' => Hash::make($request->password),
            'nid_verified' => true,
        ]);

        return redirect()->route('login.form')->with('success', 'Registration successful! Please login with your credentials.');
    }

    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'phone' => 'required|string',
            'password' => 'required|string',
        ]);

        // Try to find user by email and phone
        $user = User::where('email', $request->email)
                   ->where('contact_number', $request->phone)
                   ->first();

        if ($user && Hash::check($request->password, $user->password)) {
            Auth::login($user);
            $request->session()->regenerate();
            
            // Check user role and redirect accordingly
            if ($user->role === 'admin') {
                return redirect()->route('adminpanel');
            } else {
                return redirect()->route('user.dashboard');
            }
        }

        return back()->withErrors([
            'login_failed' => 'Invalid email, phone number, or password. Please try again.'
        ])->withInput($request->only('email', 'phone'));
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('welcome');
    }
}
