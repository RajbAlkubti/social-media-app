<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>𝕐 - Profile</title>
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
        <div id="sidebar" class="fixed top-0 left-0 h-screen lg:flex lg:w-64 xl:w-72 bg-white border-r border-gray-200 flex-col">
            <div class="p-4">
                <div class="w-8 h-8 bg-gradient-to-r from-brand-gray to-brand-gray-dark rounded-full flex items-center justify-center mb-8">
                    <span class="text-white font-bold">𝕐</span>
                </div>
                
                <nav class="space-y-2">
                    <x-sidebar-nav />
                </nav>
            </div>
            
            <!--fill the name and @ and add a logout button form for this section
            currently the data will be hardcoded untill later -->
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
                <!-- this will only be seen in smaller screens-->
                <div class="sticky top-0 bg-white bg-opacity-80 backdrop-blur-md border-b border-gray-200 p-4 lg:hidden">
                    <div class="flex items-center justify-between">
                        <div class="w-8 h-8 bg-gradient-to-r from-brand-gray to-brand-gray-dark rounded-full flex items-center justify-center">
                            <span class="text-white font-bold">𝕐</span>
                        </div>
                    </div>
                </div>

                <!-- Profile Header -->
                <div class="relative">
                    <!-- Cover Image -->
                    <div class="h-48 bg-gradient-to-r from-brand-gray to-brand-gray-dark"></div>
                    
                    <!-- Profile Info -->
                    <!-- This section will load all of the user info -->
                    <div class="px-4 pb-4">
                        <div class="flex justify-between items-start -mt-16 mb-4">
                            <img src="{{ $profile->profile_picture ? asset($profile->profile_picture) : asset('assets/profilePicture/DefaultPfp.jpg') }}" class="w-32 h-32 rounded-full border-4 border-white object-cover object-center"">
                            @if(Auth::user()->id !== $profile->id)
                                <div class="ml-auto mt-20">
                                    @if (Auth::user()->following->contains($profile->id))
                                    <form action="{{ route('unfollow', $profile->id) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button class="bg-gray-300 text-black px-4 py-1.5 rounded-full text-sm font-medium hover:bg-gray-400">
                                        Following
                                    </button>
                                </form>
                                    @else
                                <form action="{{ route('follow', $profile->id) }}" method="POST" class="inline">
                                            @csrf
                                    <button class="bg-black text-white px-6 py-3 rounded-full text-base font-medium hover:bg-gray-800">
                                            Follow
                                        </button>
                                        </form>
                                    @endif
                                </div>
                            @endif
                        </div>
                        
                        <div class="mb-4">
                            <h1 class="text-2xl font-bold">{{$profile->name}}</h1>
                            <p class="text-gray-500">{{'@' . $profile->username }}</p>
                            <p class="text-gray-500">{{ $profile->email }}</p>
                            <p class="mt-3 text-gray-900">{{ $profile->bio }}</p>
                            <div class="flex items-center space-x-4 mt-3 text-gray-500">
                                <span><i class="fas fa-calendar-alt mr-1"></i>Joined {{ \Carbon\Carbon::parse($profile->created_at)->format('F Y') }}</span>
                            </div>
                            <div class="flex space-x-6 mt-3">
                                <span><strong>{{ $profile->following->count() }}</strong> <span class="text-gray-500">Following</span></span>
                                <span><strong>{{ $profile->followers->count() }}</strong> <span class="text-gray-500">Followers</span></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!--break point between the profile info above and the user's posts-->
                <div class="border-b border-gray-200"></div>

                <!-- Profile Content -->
                <div id="profileContent">
                    <!-- Posts Tab -->
                    <div id="postsContent" class="profile-content">
                        <!-- User's Posts -->
                        @foreach($profile->post as $posts)

                        @php
                            $content = $posts->is_re_post && $posts->originalPost ? $posts->originalPost : $posts;
                            $targetId = $content->id; // original post's id if repost, else own id
                        @endphp
                        <div data-url="{{ route('posts.show', $targetId) }}" class="post clickable border-b border-gray-200 p-4 hover:bg-gray-50 transition-colors cursor-pointer">

                            @if($posts->is_re_post && $posts->originalPost)
                                <div class="text-sm text-gray-500 mb-2">
                                    <i class="fas fa-retweet"></i>  {{ $posts->user_id === Auth::id() ? 'You' : $posts->user->name }} reposted
                                </div>
                            @endif

                            <div class="flex space-x-3">
                                <a href="{{ route('profile.show', $content->user->id) }}">
                                    <img src="{{ $content->user->profile_picture ? asset($content->user->profile_picture) : asset('assets/profilePicture/DefaultPfp.jpg') }}"
                                        class="w-12 h-12 rounded-full">
                                </a>
                                <div class="flex-1">
                                    <div class="flex items-center justify-between mb-1">
                                        <div class="flex items-center space-x-2">      
                                            <a href="{{ route('profile.show', $content->user->id) }}" class="font-bold text-gray-900 hover:underline">
                                                {{ $content->user->name }}
                                            </a>

                                            <a href="{{ route('profile.show', $content->user->id) }}" class="text-gray-500 hover:underline">
                                                &#64;{{ $content->user->username }}
                                            </a>
                                            <span class="text-gray-500">·</span>
                                            <span class="text-gray-500">{{ $content->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                    
                                    <p class="text-gray-900 mb-3">{{ $content->content }}</p>
                                    
                                    @if($content->image)
                                    <div class="mb-3">
                                        <img src="{{ asset($content->image) }}" alt="Post Photo" class="rounded-lg max-w-full h-auto">
                                    </div>
                                    @endif

                                    @if ($content->video)
                                        <div class="mt-3">
                                            <video controls class="rounded-lg max-w-full h-auto">
                                                <source src="{{ asset($content->video) }}" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        </div>
                                    @endif

                                    <div class="flex items-center justify-between max-w-md">
                                        <button class="flex items-center space-x-2 text-gray-500 hover:text-blue-500 group">
                                            <div class="p-2 rounded-full group-hover:bg-blue-50">
                                                <i class="fas fa-comment"></i>
                                            </div>
                                            <span class="text-sm">{{ $content->comments->count()}}</span>
                                        </button>
                                        
                                        <form method="POST" action="{{ route('post.repost', $content->id) }}">
                                            @csrf
                                            <button type="submit"
                                                class="flex items-center space-x-2 {{ $content->isRepostedBy(Auth::user()) ? 'text-green-500' : 'text-gray-500' }} hover:text-green-500 group">
                                                <div class="p-2 rounded-full group-hover:bg-green-50">
                                                    <i class="fas fa-retweet"></i>
                                                </div>
                                                <span class="text-sm">{{ $content->reposts()->count() }}</span>
                                            </button>
                                        </form>
                                        
                                        <form method="POST" action="{{  route('post.like', $content->id) }}">
                                            @csrf
                                            <button type="submit" 
                                            class="flex items-center space-x-2 {{ $content->isLikedBy(Auth::user()) ? 'text-red-500' : 'text-gray-500' }} hover:text-red-500 group">
                                                <div class="p-2 rounded-full group-hover:bg-red-50">
                                                    <i class="fas fa-heart"></i>
                                                </div>
                                                <span class="text-sm">{{ $content->likedByUsers->count() }}</span>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
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