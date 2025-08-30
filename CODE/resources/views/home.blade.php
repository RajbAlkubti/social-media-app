<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>𝕐 - Home</title>
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

            <!-- User Section -->
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

                <!-- Desktop Header -->
                <!-- Main Header-->
                <div class="hidden lg:block sticky top-0 bg-white bg-opacity-80 backdrop-blur-md border-b border-gray-200 p-4">
                    <h1 class="text-xl font-bold">Home</h1>
                </div>
                
                <!-- Feed Tabs -->
                <!-- These are the 2 different tabs each one should load different content-->
                <div class="border-b border-gray-200">
                    <div class="flex">
                        <button onclick="switchTab('following')" class="flex-1 py-4 text-center font-medium hover:bg-gray-50 tab-button active" data-tab="following">
                            Following
                        </button>
                        <button onclick="switchTab('foryou')" class="flex-1 py-4 text-center font-medium hover:bg-gray-50 tab-button" data-tab="foryou">
                            For you
                        </button>
                    </div>
                </div>

                <!-- Create Post -->
                <!-- this is the entire create post section should be under a form that the post controller deals with -->
                <form action="{{ route('post.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div id="createPostSection" class="border-b border-gray-200 p-4">
                        <div class="flex space-x-3">
                            <img src="{{ Auth::user()->profile_picture ? asset(Auth::user()->profile_picture) : asset('assets/profilePicture/DefaultPfp.jpg') }}" class="w-10 h-10 rounded-full">
                            <div class="flex-1">
                                <textarea name="content" id="postText" placeholder="What's happening?" class="w-full text-xl placeholder-gray-500 border-none outline-none resize-none bg-transparent" rows="3"></textarea>

                                <div id="imagePreview" class="relative inline-block hidden">
                                    <img id="previewImg" src="" class="max-w-full max-h-[400px] object-cover rounded-2xl hidden"> 
                                
                                    <video id="previewVideo" class="max-w-full max-h-[400px] rounded-2xl hidden" controls></video> 
                                    <button type="button" id="removeImage" class="absolute top-2 right-2 w-8 h-8 bg-black bg-opacity-60 text-white rounded-full hover:bg-opacity-80 hidden">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    
                                    <button type="button" id="removeVideo" class="absolute top-2 right-2 w-8 h-8 bg-black bg-opacity-60 text-white rounded-full hover:bg-opacity-80 hidden">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    
                                    <input type="hidden" name="image_removed" id="imageRemovedFlag" value="0">
                                    <input type="hidden" name="video_removed" id="videoRemovedFlag" value="0">
                                </div>
                                    
                                <div class="flex items-center justify-between mt-4">
                                    <div class="flex space-x-4 text-brand-gray">
                                        <button type="button" id="imageTrigger" class="hover:bg-blue-50 p-2 rounded-full">
                                            <i class="fas fa-image"></i>
                                        </button>
                                        <button type="button" id="videoTrigger" class="hover:bg-blue-50 p-2 rounded-full">
                                            <i class="fas fa-video"></i>
                                        </button>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <span id="charCount" class="text-sm text-gray-500">280</span>
                                        <button id="postButton" class="bg-black text-white px-6 py-2 rounded-full font-medium transition-colors disabled:opacity-50">
                                            Post
                                        </button>
                                    </div>
                                </div>
                                <input name="image" type="file" id="imageInput" accept="image/*" class="hidden">
                                <input name="video" type="file" id="videoInput" accept="video/*" class="hidden">
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Posts Feed -->
                <!-- there should be 2 one for the 'for you' tab and the other for the 'followers' tab
                maybe there is a better way to do it but this is the current idea-->
                <div id="followingPost" class='posts'>
                    <div class="max-w-2xl mx-auto mt-8">
                         @foreach($followingPosts as $post)
                            @php
                                $content = $post->is_re_post && $post->originalPost ? $post->originalPost : $post;
                                $targetId = $content->id; // original post's id if repost, else own id
                            @endphp
                            <div class="border-b border-gray-200 p-4 hover:bg-gray-50 transition-colors cursor-pointer" onclick="window.location='{{ route('posts.show', $targetId) }}'">
                                @if($post->is_re_post && $post->originalPost)
                                    <div class="text-sm text-gray-500 mb-2">
                                        <i class="fas fa-retweet"></i>  {{ $post->user_id === Auth::id() ? 'You' : $post->user->name }} reposted
                                    </div>
                                @endif
                                <div class="flex space-x-3">
                                    <a href="{{ route('profile.show', $content->user->id) }}">
                                        <img src="{{ $content->user->profile_picture ? asset($content->user->profile_picture) : 'assets/profilePicture/DefaultPfp.jpg' }}" class="w-12 h-12 rounded-full" alt="User Picture">
                                    </a>
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2 mb-1">
                                            <a href="{{ route('profile.show', $content->user->id) }}" class="font-bold text-gray-900 hover:underline">{{ $content->user->name }}</a>
                                            <a href="{{ route('profile.show', $content->user->id) }}" class="hover:underline">
                                                <span class="text-gray-500">&#64;{{ $content->user->username }}</span>
                                            </a>
                                            <span class="text-gray-500">·</span>
                                            <span class="text-gray-500">{{ $content->created_at->diffForHumans() }}</span>
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
                                            
                                            <form action="{{ route('post.repost', $content->id) }}" method="POST">
                                                @csrf
                                                <button class="flex items-center space-x-2 {{ $content->isRepostedBy(Auth::user()) ? 'text-green-500' : 'text-gray-500' }} hover:text-green-500 group">
                                                    <div class="p-2 rounded-full group-hover:bg-green-50">
                                                        <i class="fas fa-retweet"></i>
                                                    </div>
                                                    <span class="text-sm">{{ $content->reposts()->count() }}</span>
                                                </button>
                                            </form>
                                            
                                            <form method="POST" action="{{  route('post.like', $post->id) }}">
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
                <div id="foryouPost" class='posts'>
                    <div class="max-w-2xl mx-auto mt-8">
                        @foreach($forYouPosts as $post)
                            @php
                                $content = $post->is_re_post && $post->originalPost ? $post->originalPost : $post;
                                $targetId = $content->id; // original post's id if repost, else own id
                            @endphp
                            <div class="border-b border-gray-200 p-4 hover:bg-gray-50 transition-colors cursor-pointer" onclick="window.location='{{ route('posts.show', $targetId) }}'">
                                @if($post->is_re_post && $post->originalPost)
                                    <div class="text-sm text-gray-500 mb-2">
                                        <i class="fas fa-retweet"></i>  {{ $post->user_id === Auth::id() ? 'You' : $post->user->name }} reposted
                                    </div>
                                @endif
                               <div class="flex space-x-3">
                                    <a href="{{ route('profile.show', $content->user->id) }}">
                                        <img src="{{ $content->user->profile_picture ? asset($content->user->profile_picture) : 'assets/profilePicture/DefaultPfp.jpg' }}" class="w-12 h-12 rounded-full" alt="User Picture">
                                    </a>
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2 mb-1">
                                            <a href="{{ route('profile.show', $content->user->id) }}" class="font-bold text-gray-900 hover:underline">{{ $content->user->name }}</a>
                                            <a href="{{ route('profile.show', $content->user->id) }}" class="hover:underline">
                                                <span class="text-gray-500">&#64;{{ $content->user->username }}</span>
                                            </a>
                                            <span class="text-gray-500">·</span>
                                            <span class="text-gray-500">{{ $content->created_at->diffForHumans() }}</span>
                                        </div>

                                        <p class="text-gray-900 mb-3">{{ $content->content }}</p>

                                        @if($content->image)
                                            <div class="mb-3">
                                                <img src="{{ asset($content->image) }}" alt="Post Photo" class="rounded-lg max-w-full h-auto">
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
                                        
                                        <div class="flex items-center justify-between max-w-md">
                                            <button class="flex items-center space-x-2 text-gray-500 hover:text-blue-500 group">
                                                <div class="p-2 rounded-full group-hover:bg-blue-50">
                                                    <i class="fas fa-comment"></i>
                                                </div>
                                                <span class="text-sm">{{ $content->comments->count()}}</span>
                                            </button>
                                            
                                            <form action="{{ route('post.repost', $content->id) }}" method="POST">
                                                @csrf
                                                <button class="flex items-center space-x-2 {{ $content->isRepostedBy(Auth::user()) ? 'text-green-500' : 'text-gray-500' }} hover:text-green-500 group">
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

                <!-- Footer -->
                <div class="mt-6 text-sm text-gray-500 space-y-2">
                    <div class="flex flex-wrap gap-x-4">
                        <a href="#" class="hover:underline">Terms of Service</a>
                        <a href="#" class="hover:underline">Privacy Policy</a>
                        <a href="#" class="hover:underline">Cookie Policy</a>
                    </div>
                    <div class="flex flex-wrap gap-x-4">
                        <a href="#" class="hover:underline">Accessibility</a>
                        <a href="#" class="hover:underline">Ads info</a>
                        <a href="#" class="hover:underline">More</a>
                    </div>
                    <p>© 2025 𝕐 Corp.</p>
                </div>
            </div>
        </div>
    </div>


    <script src="../js/home.js"></script>
    <script src="../js/activeSidebar.js"></script>
    <script src="../js/tabSwitch.js"></script>
    <script src="../js/imageAndVideoInput.js"></script>

</body>
</html>