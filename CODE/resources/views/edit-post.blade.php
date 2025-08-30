<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>𝕐 - Edit Post</title>
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
        <div id="sidebar" class="hidden lg:flex lg:w-64 xl:w-72 bg-white border-r border-gray-200 flex-col">
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
                <div class="sticky top-0 bg-white bg-opacity-80 backdrop-blur-md border-b border-gray-200 p-4 z-10">
                    <div class="flex items-center space-x-3">
                        <button onclick="window.history.back()" class="p-2 rounded-full hover:bg-gray-100">
                            <i class="fas fa-arrow-left text-xl"></i>
                        </button>
                        <h1 class="text-xl font-bold">Edit Post</h1>
                    </div>
                </div>

                <form action="{{ route('post.update', $post->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div id="createPostSection" class="border-b border-gray-200 p-4">
                        <div class="flex space-x-3">
                            <img src="{{ Auth::user()->profile_picture ? asset(Auth::user()->profile_picture) : asset('assets/profilePicture/DefaultPfp.jpg') }}" class="w-10 h-10 rounded-full">
                            <div class="flex-1">
                                <textarea name="content" id="postText" placeholder="What's happening?" class="w-full text-xl placeholder-gray-500 border-none outline-none resize-none bg-transparent" rows="3">{{ old('content', $post->content) }}</textarea>

                                <div id="imagePreview" class="relative inline-block 
                                    @if ($post->image || $post->video) 
                                    @else 
                                        hidden 
                                    @endif">

                                    <img id="previewImg" src="{{ $post->image ? asset($post->image) : '#' }}" class="rounded-lg max-w-full h-auto 
                                        @if ($post->image) 
                                        @else 
                                            hidden 
                                        @endif" 
                                    alt="Image Preview">

                                    <video id="previewVideo" controls src="{{ $post->video ? asset($post->video) : '#' }}" class="rounded-lg max-w-full h-auto 
                                        @if ($post->video) 
                                        @else 
                                            hidden 
                                        @endif">
                                        Your browser does not support the video tag.
                                    </video>

                                    <button type="button" id="removeImage" class="absolute top-2 right-2 w-8 h-8 bg-black bg-opacity-60 text-white rounded-full 
                                        @if ($post->image) 
                                        @else 
                                            hidden 
                                        @endif">
                                        <i class="fas fa-times"></i>
                                    </button>

                                    <button type="button" id="removeVideo" class="absolute top-2 right-2 w-8 h-8 bg-black bg-opacity-60 text-white rounded-full 
                                        @if ($post->video) 
                                        @else 
                                            hidden 
                                        @endif">
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
                                        <button id="postButton" class="bg-brand-gray text-white px-6 py-2 rounded-full font-medium hover:bg-brand-gray-dark transition-colors disabled:opacity-50">
                                            Update
                                        </button>
                                    </div>
                                </div>
                                <input name="image" type="file" id="imageInput" accept="image/*" class="hidden">
                                <input name="video" type="file" id="videoInput" accept="video/*" class="hidden">

                            </div>
                        </div>
                    </div>
                </form>


            </div>
        </div>
    </div>

    <script src="../js/activeSidebar.js"></script>
    <script src="{{ asset('js/imageAndVideoInput.js') }}"></script>

</body>
</html>