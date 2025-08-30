<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>𝕐 - Explore</title>
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
                    <form method="POST" action="/logout" class="hidden xl:block">
                        @csrf
                        <button type="submit" class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-sign-out-alt"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>


        <!-- Main Content -->
        <div class="flex-1 flex lg:ml-64 xl:ml-72">
            <!-- Center Content -->
            <div class="flex-1 max-w-2xl mx-auto bg-white border-x border-gray-200">
                <!-- Header -->
                <div class="sticky top-0 bg-white bg-opacity-80 backdrop-blur-md border-b border-gray-200 p-4 lg:hidden">
                    <div class="flex items-center justify-between">
                        <div class="w-8 h-8 bg-gradient-to-r from-brand-gray to-brand-gray-dark rounded-full flex items-center justify-center">
                            <span class="text-white font-bold">𝕐</span>
                        </div>
                    </div>
                </div>

                <!-- Search Header -->
                <form method="POST" action="{{ route('search') }}">
                    @csrf
                    <div class="sticky top-0 bg-white bg-opacity-80 backdrop-blur-md border-b border-gray-200 p-4">
                        <div class="relative">
                            <button type="submit" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
                                <i class="fas fa-search"></i>
                            </button>
                            <input
                                type="text"
                                name="query"
                                placeholder="Search 𝕐"
                                class="w-full pl-10 pr-4 py-3 bg-gray-100 rounded-full border-none outline-none focus:bg-white focus:ring-2 focus:ring-brand-gray"
                            >
                        </div>
                    </div>
                </form>
                
                <!-- Explore Content -->
                <div id="exploreContent">
                    @if(isset($searchQuery))
                        <div id="searchResults">
                            <h2 class="text-lg font-semibold mb-4 text-center">Results for "{{ $searchQuery }}"</h2>

                            @if($searchUsers->isNotEmpty())
                                @foreach($searchUsers as $user)
                                    <div class="p-4 border-b border-gray-100 hover:bg-gray-50 transition-colors">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-3">
                                                <a href="{{ route('profile.show', $user->id) }}" class="flex items-center space-x-3">
                                                    <img src="{{ $user->profile_picture ? asset($user->profile_picture) : asset('assets/profilePicture/DefaultPfp.jpg') }}"
                                                        class="w-12 h-12 rounded-full" alt="Profile picture of {{ $user->name }}">
                                                    <div>
                                                        <p class="font-bold text-gray-900">{{ $user->name }}</p>
                                                        <p class="text-sm text-gray-500">{{ $user->username }}</p>
                                                        @if($user->bio)
                                                        <p class="text-sm text-gray-600 mt-1">{{ $user->bio }}</p>
                                                        @endif
                                                    </div>
                                                </a>
                                            </div>                 
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                            @if($searchPosts->isNotEmpty() && $searchPosts != "")
                                @foreach($searchPosts as $post)
                                    <div data-url="{{ route('posts.show', $post->id) }}" class="post clickable border-b border-gray-200 p-4 hover:bg-gray-50 transition-colors cursor-pointer">
                                        <div class="flex space-x-3">
                                            <a href="{{ route('profile.show', $post->user->id) }}">
                                                <img src="{{ $post->user->profile_picture ? asset($post->user->profile_picture) : asset('assets/profilePicture/DefaultPfp.jpg') }}"
                                                    class="w-12 h-12 rounded-full">
                                            </a>
                                            <div class="flex-1">
                                                <div class="flex items-center justify-between mb-1">
                                                    <div class="flex items-center space-x-2">      
                                                        <a href="{{ route('profile.show', $post->user->id) }}" class="font-bold text-gray-900 hover:underline">
                                                            {{ $post->user->name }}
                                                        </a>

                                                        <a href="{{ route('profile.show', $post->user->id) }}" class="text-gray-500 hover:underline">
                                                            &#64;{{ $post->user->username }}
                                                        </a>
                                                        <span class="text-gray-500">·</span>
                                                        <span class="text-gray-500">{{ $post->created_at->diffForHumans() }}</span>
                                                    </div>
                                                </div>
                                                
                                                <p class="text-gray-900 mb-3">{{ $post->content }}</p>
                                                
                                                @if($post->image)
                                                <div class="mb-3">
                                                    <img src="{{ asset($post->image) }}" alt="Post Photo" class="rounded-lg max-w-full h-auto">
                                                </div>
                                                @endif

                                                @if ($post->video)
                                                    <div class="mt-3">
                                                        <video controls class="rounded-lg max-w-full h-auto">
                                                            <source src="{{ asset($content->video) }}" type="video/mp4">
                                                            Your browser does not support the video tag.
                                                        </video>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif

                            @if($searchUsers->isEmpty() && $searchPosts->isEmpty())
                                <div class="flex justify-center items-center h-64">
                                    <p class="text-center text-gray-500">No results found.</p>
                                </div>
                            @endif
                        </div>
                    @else
                        <div id="foryouContent" class="explore-content">
                            <p class="text-center text-gray-500">Explore content goes here...</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Right Sidebar -->
            <div class="hidden lg:block w-80 p-4">
                <!-- Who to follow -->
                <!--placeholder for user suggestions-->
                <div class="bg-gray-50 rounded-2xl p-4">
                    <h2 class="text-xl font-bold mb-4">Who to follow</h2>
                    <div class="space-y-3">
                        @include('components.suggested-users')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../js/activeSidebar.js"></script>
    <script src="../js/routeToPost.js"></script>
</body>
</html>