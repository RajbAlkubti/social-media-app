<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;

class SearchController extends Controller
{
   public function search(Request $request)
    {
        $query = trim($request->input('query'));

        if (empty($query)) {
            return view('explore', [
                'searchQuery' => '',
                'searchPosts' => collect(),
                'searchUsers' => collect()
            ]);
        }

        if (str_starts_with($query, '@')) {
            $username = ltrim($query, '@');
            $searchUsers = User::where('username', 'like', "%$username%")->get();
            $searchPosts = collect();
        } else {
            $searchPosts = Post::with(['user', 'comments'])
                ->where('content', 'like', "%$query%")
                ->orderBy('created_at', 'desc')
                ->get();
            $searchUsers = collect();
        }

        return view('explore', [
            'searchQuery' => $query,
            'searchPosts' => $searchPosts,
            'searchUsers' => $searchUsers
        ]);
    }
}
