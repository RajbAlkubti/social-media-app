<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

use App\Models\Post;
use Illuminate\Support\Facades\Auth;


class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::latest()->get();
        return view('posts.index', compact('posts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'content' => 'nullable|string|max:280',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'video' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:10240',
        ]);

        $post = new Post();
        $post->user_id = auth()->id();
        $post->content = $validatedData['content'];

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('assets/postsImages');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $image->move($destinationPath, $imageName);

            $post->image = 'assets/postsImages/' . $imageName;
        }

        if ($request->hasFile('video')) {
            $video = $request->file('video');
            $videoName = uniqid() . '.' . $video->getClientOriginalExtension();
            $destinationPath = public_path('assets/postsVideos');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $video->move($destinationPath, $videoName);

            $post->video = 'assets/postsVideos/' . $videoName;
        }

        $post->save();
        return redirect('/home')->with('success', 'Post created successfully!');
    }

    public function toggleLike(Post $post)
    {
        $user = Auth::user();

        if ($post->likedByUsers()->where('user_id', $user->id)->exists()) {
            $post->likedByUsers()->detach($user->id);
        } else {
            $post->likedByUsers()->attach($user->id);
        }

        return redirect()->back();
    }

    public function toggleRepost(Post $post)
    {
        $user = Auth::user();

        $repost = Post::where('user_id', $user->id)
                    ->where('original_post_id', $post->id)
                    ->first();

        if ($repost) {
            $repost->delete();
        } else {
            Post::create([
                'user_id' => $user->id,
                'original_post_id' => $post->id,
                'content' => '',
                'is_re_post' => true,
            ]);
        }

        return back();
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return view('post-details', compact('post'));
    }

    public function edit($id)
    {
        $post = Post::findOrFail($id);

        return view('edit-post', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        $validatedData = $request->validate([
            'content' => 'nullable|string|max:280',
            'image' => 'nullable|image|max:2048',
            'video' => 'nullable|mimetypes:video/mp4,video/webm,video/ogg|max:102400',
            'image_removed' => 'boolean',
            'video_removed' => 'boolean'
        ]);

        if ($request->has('content')) {
            $post->content = empty($validatedData['content']) ? null : $validatedData['content'];
        }

        $hasNewImage = $request->hasFile('image');
        $hasNewVideo = $request->hasFile('video');
        $isImageRemoved = $validatedData['image_removed'] ?? false;
        $isVideoRemoved = $validatedData['video_removed'] ?? false;

        if ($hasNewImage) {
            // Delete old image if it exists
            if ($post->image && file_exists(public_path($post->image))) {
                unlink(public_path($post->image));
            }
            
            // If there was an old video, delete it (a new image replaces video)
            if ($post->video && file_exists(public_path($post->video))) {
                unlink(public_path($post->video));
            }

            // Store the new image
            $image = $request->file('image');
            $imageName = uniqid() . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('assets/postsImages');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $image->move($destinationPath, $imageName);
            $post->image = 'assets/postsImages/' . $imageName;
            $post->video = null;
        } 

        //check for new video if no new image was uploaded
        else if ($hasNewVideo) {
            // Delete old video if it exists
            if ($post->video && file_exists(public_path($post->video))) {
                unlink(public_path($post->video));
            }

            // If there was an old image, delete it (a new video replaces image)
            if ($post->image && file_exists(public_path($post->image))) {
                unlink(public_path($post->image));
            }

            // Store the new video
            $video = $request->file('video');
            $videoName = uniqid() . '.' . $video->getClientOriginalExtension();
            $destinationPath = public_path('assets/postsVideos');

            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0755, true);
            }

            $video->move($destinationPath, $videoName);
            $post->video = 'assets/postsVideos/' . $videoName;
            $post->image = null;
        }

        else {
            // If the frontend explicitly marked the image for removal AND no new image was provided
            if ($isImageRemoved && $post->image && file_exists(public_path($post->image))) {
                unlink(public_path($post->image));
                $post->image = null;
            }

            // If the frontend explicitly marked the video for removal AND no new video was provided
            if ($isVideoRemoved && $post->video && file_exists(public_path($post->video))) {
                unlink(public_path($post->video));
                $post->video = null;
            }
            // If no new media and no removal flag, existing media paths remain.
        }

        $post->save();

        return redirect()->route('profile.show', ['profile' => Auth::id()]);
    }

     public function getPosts()
    {
        $user = Auth::user();

        $forYouPosts = Post::with(['user', 'comments'])
            ->where('user_id', '!=', $user->id)
            ->orderBy('created_at', 'desc')
            ->get();

        $followingIds = $user->following()->pluck('users.id');

        $followingPosts = Post::with(['user', 'comments'])
                    ->whereIn('user_id', $followingIds)
                    ->orderBy('created_at', 'desc')
                    ->get();

         return view('home', [
            'followingPosts' => $followingPosts,
            'forYouPosts' => $forYouPosts
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $post = Post::findOrFail($id);

        if ($post->image && file_exists(public_path('assets/' . $post->image))) {
            unlink(public_path('assets/' . $post->image));
        }

        if ($post->video && file_exists(public_path('assets/' . $post->video))) {
            unlink(public_path('assets/' . $post->video));
        }

        $post->delete();
        return redirect()->route('profile.show', ['profile' => Auth::id()])->with('success', 'Post deleted successfully!');
    }


    public function adminDestroyPost(string $id)
    {
        $post = Post::findOrFail($id);

        if ($post->image && file_exists(public_path('assets/' . $post->image))) {
            unlink(public_path('assets/' . $post->image));
        }

        if ($post->video && file_exists(public_path('assets/' . $post->video))) {
            unlink(public_path('assets/' . $post->video));
        }

        $post->delete();
        return back()->with('success', 'Post deleted successfully!');
    }
}
