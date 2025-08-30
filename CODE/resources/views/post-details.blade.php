<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>𝕐 - Comments</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        'brand-gray': '#808080',
                        'brand-gray-dark': '#606060',
                        'brand-light': '#f5f5f5'
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-brand-light min-h-screen font-sans">
    <!-- Main Container -->
    <div class="flex min-h-screen">
        <!-- Left Sidebar - Desktop -->
        <div id="sidebar" class="lg:fixed flex lg:w-64 xl:w-72 top-0 left-0 h-screen bg-white border-r border-gray-200 flex-col overflow-y-auto">
            <div class="p-4">
                <div class="w-8 h-8 bg-gradient-to-r from-brand-gray to-brand-gray-dark rounded-full flex items-center justify-center mb-8">
                    <span class="text-white font-bold">𝕐</span>
                </div>
                
                <nav class="space-y-2">
                    <x-sidebar-nav />
                </nav>
            </div>
            
            <div id="userSection" class="mt-auto p-4">
                <div class="flex items-center space-x-3 p-3 rounded-full hover:bg-gray-100 cursor-pointer">
                    <img src="{{ Auth::user()->profile_picture ? asset(Auth::user()->profile_picture) : asset('assets/profilePicture/DefaultPfp.jpg') }}" class="w-10 h-10 rounded-full">
                    <div class="hidden xl:block flex-1">
                        <p class="font-medium text-gray-900" id="sidebarUserName">{{ Auth::user()->name }}</p>
                        <p class="text-gray-500 text-sm" id="sidebarUserHandle">{{ Auth::user()->username }}</p>
                    </div>
                    <button class="hidden xl:block text-gray-500 hover:text-gray-700">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex">
            <!-- Center Content -->
            <div class="flex-1 max-w-2xl mx-auto bg-white border-x border-gray-200">
                <!-- Header -->
                <div class="sticky top-0 bg-white bg-opacity-80 backdrop-blur-md border-b border-gray-200 p-4">
                    <div class="flex items-center space-x-3">
                        <button onclick="window.history.back()" class="p-2 rounded-full hover:bg-gray-100">
                            <i class="fas fa-arrow-left text-xl"></i>
                        </button>
                        <h1 class="text-xl font-bold">Comments</h1>
                    </div>
                </div>

                <!-- Original Post -->
                <div id="originalPost" class="border-b border-gray-200 p-4">
                    <!-- Post content will be loaded here -->
                    <div class="flex space-x-3">
                        <a href="{{ route('profile.show', $post->user->id) }}">
                            <img src="{{ asset($post->user->profile_picture) }}" class="w-12 h-12 rounded-full">
                        </a>
                        <div class="flex-1">
                            <div class="flex items-center justify-between mb-1">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('profile.show', $post->user->id) }}" class="hover:underline">
                                        <span class="font-bold text-gray-900">{{ $post->user->name}}</span>
                                    </a>
                                    <a href="{{ route('profile.show', $post->user->id) }}" class="hover:underline">
                                        <span class="text-gray-500">&#64;{{ $post->user->username }}</span>
                                    </a>
                                    <span class="text-gray-500">·</span>
                                    <span class="text-gray-500">{{ $post->created_at->diffForHumans() }}</span>
                                </div>

                                @if ($post->user_id === Auth::id())
                                    <div class="relative ">
                                        <button class="more-button p-2 rounded-full hover:bg-gray-200" onclick="toggleDropdown(this)">
                                            <i class="fas fa-ellipsis-h"></i>
                                        </button>
            
                                        <div class="more-menu hidden absolute right-0 mt-2 w-36 bg-white border rounded-md shadow-lg z-50">    
                                            <a href="{{ route('post.edit', $post->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Edit Post</a>

                                            <form action="{{ route('post.destroy', $post->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                                    Delete Post
                                                </button>
                                            </form>
                                         </div>
                                    </div>
                                @endif
                            </div>
                            
                            <p class="text-gray-900 mb-3 text-lg">{{ $post->content }}</p>
                
                            @if($post->image)
                                <div class="mb-3">
                                    <img src="{{ asset($post->image) }}" alt="Post Photo" class="rounded-lg max-w-full h-auto">
                                </div>
                            @endif

                            @if ($post->video)
                                <div class="mt-3">
                                    <video controls class="rounded-lg max-w-full h-auto">
                                        <source src="{{ asset($post->video) }}" type="video/mp4">
                                        Your browser does not support the video tag.
                                    </video>
                                </div>
                            @endif

                            
                            <div class="flex items-center justify-between max-w-md pt-3 border-t border-gray-100">
                                <button class="flex items-center space-x-2 text-gray-500 hover:text-blue-500 group">
                                    <div class="p-2 rounded-full group-hover:bg-blue-50">
                                        <i class="fas fa-comment"></i>
                                    </div>
                                    <span class="text-sm">{{ $post->comments->count() }}</span>
                                </button>

                                <form action="{{ route('post.repost', $post->id) }}" method="POST">
                                    @csrf
                                    <button class="flex items-center space-x-2 {{ $post->isRepostedBy(Auth::user()) ? 'text-green-500' : 'text-gray-500' }} hover:text-green-500 group">
                                        <div class="p-2 rounded-full group-hover:bg-green-50">
                                            <i class="fas fa-retweet"></i>
                                        </div>
                                        <span class="text-sm">{{ $post->reposts()->count() }}</span>
                                    </button>
                                </form>
                                
                                <form method="POST" action="{{  route('post.like', $post->id) }}">
                                    @csrf
                                    <button type="submit" 
                                    class="flex items-center space-x-2 {{ $post->isLikedBy(Auth::user()) ? 'text-red-500' : 'text-gray-500' }} hover:text-red-500 group">
                                        <div class="p-2 rounded-full group-hover:bg-red-50">
                                            <i class="fas fa-heart"></i>
                                        </div>
                                        <span class="text-sm">{{ $post->likedByUsers->count() }}</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Comment Form -->
                @if(auth()->id() !== $post->user_id)
                <form method="POST" action=" {{ route('comment.store', ['post' => $post->id]) }} ">
                @csrf
                <div id="commentForm" class="border-b border-gray-200 p-4">
                    <div class="flex space-x-3">
                        <img src="{{ Auth::user()->profile_picture ? asset(Auth::user()->profile_picture) : asset('assets/profilePicture/DefaultPfp.jpg') }}" class="w-12 h-12 rounded-full">
                        <div class="flex-1">
                            <textarea id="commentText" name="comment" placeholder="Write a comment..." 
                            class="w-full text-lg placeholder-gray-500 border-none outline-none resize-none bg-transparent" 
                            rows="3"></textarea>
                            <div class="flex items-center justify-between mt-4">
                                <div class="flex space-x-4 text-brand-gray"></div>
                                <button type="submit" id="commentButton" 
                                    class="bg-black text-white px-6 py-2 rounded-full font-medium">
                                    Comment
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                </form>
                @endif

                <!-- Comments List -->
                <div id="commentsList">
                    <!-- Comments will be loaded here -->
                     @foreach($post->comments as $comment)
                        <div class="relative border-b border-gray-100 p-4 hover:bg-gray-50 transition-colors">
                            <!-- Dropdown Button and Menu -->
                            @if ($comment->user_id === Auth::id())
                                <div class="absolute top-4 right-4 z-10">
                                    <button class="more-button p-2 rounded-full hover:bg-gray-200" onclick="toggleDropdown(this)">
                                        <i class="fas fa-ellipsis-h"></i>
                                    </button>

                                    <div class="more-menu hidden absolute right-0 mt-2 w-36 bg-white border rounded-md shadow-lg z-50">
                                            <a href="{{ route('comment.edit', $comment->id) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Edit Comment</a>

                                            <form action="{{ route('comment.destroy', $comment->id) }}" method="POST" onsubmit="return confirm('Are you sure?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                                                    Delete Comment
                                                </button>
                                            </form>
                                    </div>
                                </div>
                            @endif

                            <!-- Comment Content -->
                            <div class="flex space-x-3">
                                <a href="{{ route('profile.show', $comment->user->id) }}">
                                    <img src="{{ $comment->user->profile_picture ? asset($comment->user->profile_picture) : asset('assets/profilePicture/DefaultPfp.jpg') }}" class="w-10 h-10 rounded-full">
                                </a>
                                <div class="flex-1">
                                    <div class="flex items-center space-x-2 mb-1">   
                                        <a href="{{ route('profile.show', $comment->user->id) }}" class="hover:underline">
                                            <span class="font-bold text-gray-900">{{ $comment->user->name}}</span>
                                        </a>
                                        <a href="{{ route('profile.show', $comment->user->id) }}" class="hover:underline">
                                            <span class="text-gray-500">&#64;{{ $comment->user->username }}</span>
                                        </a>
                                        <span class="text-gray-500">·</span>
                                        <span class="text-gray-500">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>

                                    <p class="text-gray-900 mb-3">{{ $comment->content }}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <script src="../js/activeSidebar.js"></script>
    <script src="../js/moreOptionsButton.js"></script>
    <script src="../js/imageAndVideoInput.js"></script>
</body>
</html>