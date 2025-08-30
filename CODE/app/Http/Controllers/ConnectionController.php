<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ConnectionController extends Controller
{
    public function follow(User $user)
    {
        $currentUser = Auth::user();

        if (!$currentUser->following->contains($user->id)) {
            $currentUser->following()->attach($user->id);
        }

        return back();
    }


    public function unfollow(User $user)
    {
        $currentUser = Auth::user();

        if ($currentUser->following->contains($user->id)) {
            $currentUser->following()->detach($user->id);
        }

        return back(); // يمكنك إعادة التوجيه إذا أردت
    }


    // return back();
}
