<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>𝕐 - Admin</title>
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
   <!-----Confirmation message of the request------>
    @if (session('success'))
        <div style="
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
            padding: 15px 25px;
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            z-index: 9999;
            font-weight: bold;
            text-align: center;
            .alert-fade-out {
            opacity: 1;
            transition: opacity 1s ease-out;
        }

        .alert-fade-out.hide {
            opacity: 0;
        ">
            {{ session('success') }}
        </div>
    @endif
    <script>
        setTimeout(() => {
            const alerts = document.querySelectorAll('[style*="position: fixed"]');
            alerts.forEach(alert => alert.remove());
        }, 3000);
    </script>
    <!-- Main Container -->
    <div class="flex min-h-screen">
        <!-- Left Sidebar - Desktop -->
        <div id="sidebar" class="fixed top-0 left-0 h-screen lg:flex lg:w-64 xl:w-72 bg-white border-r border-gray-200 flex-col">
            <div class="p-4">
                <div class="w-8 h-8 bg-gradient-to-r from-brand-gray to-brand-gray-dark rounded-full flex items-center justify-center mb-8">
                    <span class="text-white font-bold">𝕐</span>
                </div>
                
                <nav class="space-y-2">
                    <a href="admin" class="flex items-center space-x-3 px-3 py-3 rounded-full hover:bg-gray-100 transition-colors nav-item active">
                        <i class="fas fa-shield-alt text-xl"></i>
                        <span class="text-xl font-medium hidden xl:block">Admin</span>
                    </a>
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
            <div class="flex-1 max-w-4xl mx-auto bg-white border-x border-gray-200">
                <!-- Header -->
                <div class="sticky top-0 bg-white bg-opacity-80 backdrop-blur-md border-b border-gray-200 p-4">
                    <div class="flex items-center space-x-3">
                        <i class="fas fa-shield-alt text-brand-gray text-xl"></i>
                        <h1 class="text-xl font-bold">Admin Dashboard</h1>
                    </div>
                </div>
                
                <!-- Admin Tabs -->
                <div class="border-b border-gray-200">
                    <div class="flex">
                        <button onclick="switchAdminTab('users')" class="px-6 py-4 text-center font-medium hover:bg-gray-50 admin-tab active" data-tab="users">
                            <i class="fas fa-users mr-2"></i>
                            All Users
                        </button>
                        <button onclick="switchAdminTab('posts')" class="px-6 py-4 text-center font-medium hover:bg-gray-50 admin-tab" data-tab="posts">
                            <i class="fas fa-file-alt mr-2"></i>
                            All Posts
                        </button>
                    </div>
                </div>

                <!-- Admin Content -->
                <div id="adminContent">
                    <!-- Users Tab -->
                    <div id="usersContent" class="admin-content">
                        <div class="p-4">
                            <div class="flex items-center justify-between mb-6">
                                <h2 class="text-lg font-semibold">User Management</h2>
                                <div class="flex items-center space-x-4">
                                    <span class="text-sm text-gray-500">Total Users: <strong>{{ $users->count() }}</strong></span>
                                    <div class="relative">
                                        <!---Search Users-->
                                       <form method="GET" action="/admin" class="flex items-center space-x-2">
                                            <div class="relative">
                                                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                                <input type="text" name="user_search" value="{{ request('user_search') }}" placeholder="Search users..."
                                                    class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-gray">
                                            </div>
                                            <button type="submit" class="px-4 py-2 bg-brand-gray text-white rounded-lg hover:bg-brand-gray-dark transition">
                                                <i class="fa-solid fa-magnifying-glass"></i>
                                            </button>
                                        </form>
                                        @if(request()->filled('user_search') && !str_starts_with(request('user_search'), '@'))
                                            <div class="text-red-500 font-bold mb-2">
                                                Please start the username with @ symbol to search.
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            </div>
                            
                            <!-- Users Table -->
                            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                                <table class="w-full">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Posts</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                        </tr>
                                    </thead>
                                    <!--this section will be used to render the users later on
                                    use this as a refrence-->
                                    <tbody class="bg-white divide-y divide-gray-200">
                                         @foreach ($users as $user)
                                        <tr class="hover:bg-gray-50">
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex items-center">
                                                    <img src="{{ $user->profile_picture ? asset($user->profile_picture) : asset('assets/profilePicture/DefaultPfp.jpg') }}"
                                                        class="w-10 h-10 rounded-full object-cover object-center">
                                                    <div class="ml-4">
                                                        <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                                        <div class="text-sm text-gray-500">{{ '@' . $user->username }}</div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $user->email }}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                {{ \Carbon\Carbon::parse($user->created_at)->format('M Y') }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                {{ $user->post->count() }}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                              <form action="/users/{{ $user->id }}" method="POST" onsubmit="return confirm('Are you sure you want to delete the User?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="" style="color: red; font-weight: bold;"><i class="fas fa-trash"></i></button>
                                               </form>

                                            </td>
                                        </tr>
                                         @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Posts Tab -->
                    <div id="postsContent" class="admin-content hidden">
                        <div class="p-4">
                            <div class="flex items-center justify-between mb-6">
                                <h2 class="text-lg font-semibold">Post Management</h2>
                                <div class="flex items-center space-x-4">
                                    <span class="text-sm text-gray-500">Total Posts: <strong>{{ $posts->count() }}</strong></span>
                                    <div class="relative">
                                        <!---Search Posts-->
                                        <form method="GET" action="/admin" class="flex items-center space-x-2">
                                            <div class="relative">
                                                <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                                                <input type="text" name="post_search" value="{{ request('post_search') }}" placeholder="Search posts..."
                                                    class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-gray">
                                            </div>
                                            <button type="submit" class="px-4 py-2 bg-brand-gray text-white rounded-lg hover:bg-brand-gray-dark transition">
                                                <i class="fa-solid fa-magnifying-glass"></i>
                                            </button>
                                        </form>
                                        @if(request()->filled('post_search') && !str_starts_with(request('post_search'), '@'))
                                            <div class="text-red-500 font-bold mb-2">
                                                Please start the username with @ symbol to search.
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            </div>
                            
                            <!-- Posts List -->
                            <div class="space-y-4">
                                <!-- Post Item -->
                                 <!--this section will be used to render the users later on
                                    use this as a refrence-->
                                @foreach ($posts as $post)
                                    @if($post->is_re_post)
                                        @continue
                                    @endif
                                    <div class="bg-white border border-gray-200 rounded-lg p-4">
                                        <div class="flex items-start justify-between">
                                            <div class="flex space-x-3 flex-1">
                                                <img src="{{ $post->user->profile_picture ? asset($post->user->profile_picture) : asset('assets/profilePicture/DefaultPfp.jpg') }}"
                                                    class="w-12 h-12 rounded-full object-cover object-center">
                                                <div class="flex-1">
                                                    <div class="flex items-center space-x-2 mb-2">
                                                        <span class="font-bold text-gray-900">{{ $post->user->name }}</span>
                                                        <span class="text-gray-500">{{ '@' . $post->user->username }}</span>
                                                        <span class="text-gray-500">·</span>
                                                        <span class="text-gray-500">{{ $post->created_at->diffForHumans() }}</span>
                                                    </div>
                                                    <p class="text-gray-900 mb-3">{{ $post->content }}</p>

                                                    @if ($post->image)
                                                        <img src="{{ asset($post->image) }}" class="rounded-lg max-w-full h-auto mb-3">
                                                    @endif

                                                    @if ($post->video)
                                                        <video controls class="rounded-lg max-w-full h-auto mb-3">
                                                            <source src="{{ asset('assets/' . $post->video) }}" type="video/mp4">
                                                            Your browser does not support the video tag.
                                                        </video>
                                                    @endif

                                                    <div class="flex items-center space-x-6 text-gray-500 text-sm">
                                                        <span><i class="fas fa-heart text-red-500 mr-1"></i>{{ $post->likedByUsers->count() }} likes</span>
                                                        <span><i class="fas fa-comment mr-1"></i>{{ $post->comments->count() }} comments</span>
                                                        @if ($post->is_re_post)
                                                            <span><i class="fas fa-retweet text-green-500 mr-1"></i>Reposted</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <form action="{{route('admin.destroy', $post->id)}}" method="POST" onsubmit="return confirm('Are you sure you want to delete the Post?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" style="color: red; font-weight: bold;">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Sidebar -->
            <div class="hidden lg:block w-80 p-4">
                <!-- Admin Stats -->
                <div class="bg-gray-50 rounded-2xl p-4 mb-4">
                    <h2 class="text-xl font-bold mb-4">Platform Stats</h2>
                    <div class="space-y-3">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Users</span>
                            <span class="font-bold text-lg">{{ $totalUsers }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Total Posts</span>
                            <span class="font-bold text-lg">{{ $totalPosts }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="../js/admin.js"></script>
    <script src="../js/activeSidebar.js"></script>
</body>
</html>