<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Attribute;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        $usersQuery = User::query();
        $postsQuery = Post::latest();


        if ($request->filled('user_search') && str_starts_with($request->user_search, '@')) {
            $username = ltrim($request->user_search, '@');
            $usersQuery->where('username', 'like', "%{$username}%");
        }


        if ($request->filled('post_search') && str_starts_with($request->post_search, '@')) {
            $username = ltrim($request->post_search, '@');
            $postsQuery->whereHas('user', function ($query) use ($username) {
                $query->where('username', 'like', "%{$username}%");
            });
        }

        $users = $usersQuery->get();
        $posts = $postsQuery->get();

        $totalUsers = User::count();
        $totalPosts = Post::count();

        return view('admin', compact('users', 'posts', 'totalUsers', 'totalPosts'));
    }





    /**
     * Show the form for creating a new resource.
     */

    public function create()
    {
        return view('reg');
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store()
    {

        $attributes = request()->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);


        $user = User::create([
            'name' => $attributes['name'],
            'username' => $attributes['username'],
            'email' => $attributes['email'],
            'password' => $attributes['password']
        ]);


        Auth::login($user);


        return redirect('/home');
    }

    public function loginForm()
    {
        return view('login');
    }

    public function login()
    {
        $attributes = request()->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($attributes)) {
            request()->session()->regenerate();
            
            $user = Auth::user();
            // Check if the user is an admin
            if ($user->user_type === 'admin') {
                return redirect('/admin');
            }

            return redirect('/home');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials.',
        ]);
    }

        public function logout()
    {
        Auth::logout();
        return redirect('/login');
    }

    /**
     * Display the specified resource.
     */

    public function show(User $profile)
    {
        $user = User::find($profile);
        return view('profile', compact('profile'));
    }

    /**
     * Show the form for editing the specified resource.
     */

    public function edit()
    {
        $user = Auth::user();
        return view('settings', compact('user'));
    }


    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'bio' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');

            $filename = uniqid() . '.' . $file->getClientOriginalExtension();

            $file->move(public_path('assets/profilePicture'), $filename);

            $user->profile_picture = 'assets/profilePicture/' . $filename;
        }

        $user = Auth::user();

        $user->name = $request->name;
        $user->username = $request->username;
        $user->bio = $request->bio;

        $user->save();


        return redirect()->route('settings.edit')->with('success', 'Profile updated.');
    }

    public function removeProfilePicture(Request $request)
    {
        $user = Auth::user();

        if ($user->profile_picture && file_exists(public_path($user->profile_picture))) {
            unlink(public_path($user->profile_picture));
        }

        $user->profile_picture = null;
        $user->save();

        return back()->with('success', 'Profile picture removed.');
    }

    public function updateSecurityData(Request $request)
    {
        $user = Auth::user();

        // Validate all inputs including current password
        $request->validate([
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'password' => 'required|string|min:8|confirmed',
            'current_password' => 'required',
        ]);

        // Check if current password is correct
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'The current password is incorrect.']);
        }

        // Update email and password
        $user->email = $request->email;
        $user->password = bcrypt($request->password);

        $user->save();

        return redirect()->route('settings.edit')->with('success', 'Security data updated.');
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::find($id);

        if (!$user) {
            return back()->with('error', 'User not found');
        }

        $user->delete();

        return back()->with('success', 'The user has been successfully deleted ✅');
    }
}
