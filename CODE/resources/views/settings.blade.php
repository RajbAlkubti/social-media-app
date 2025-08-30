<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>𝕐 - Settings</title>
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
            
            <!--fill the name and @ and add a logout button form for this section
            currently the data will be hardcoded untill later -->
            <div id="userSection" class="mt-auto p-4">
                <div class="flex items-center space-x-3 p-3 rounded-full hover:bg-gray-100 cursor-pointer">
                    <img id="sidebarProfilePicturePreview" src= "{{ Auth::user()->profile_picture ? asset(Auth::user()->profile_picture) : asset('assets/profilePicture/DefaultPfp.jpg') }}" class="w-10 h-10 rounded-full">
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
        <div class="flex-1 flex">
            <!-- Center Content -->
            <div class="flex-1 max-w-2xl mx-auto bg-white border-x border-gray-200">
                <!-- Header -->
                <div class="sticky top-0 bg-white bg-opacity-80 backdrop-blur-md border-b border-gray-200 p-4">
                    <h1 class="text-xl font-bold">Settings</h1>
                </div>
                
                <!-- Settings Tabs -->
                <div class="border-b border-gray-200">
                    <div class="flex">
                        <button onclick="switchSettingsTab('profile')" class="px-4 py-3 text-center font-medium hover:bg-gray-50 settings-tab active" data-tab="profile">
                            Profile
                        </button>
                        <button onclick="switchSettingsTab('security')" class="px-4 py-3 text-center font-medium hover:bg-gray-50 settings-tab" data-tab="security">
                            Security
                        </button>
                    </div>
                </div>

                <!-- Settings Content -->
            <div id="settingsContent">
                <!-- Profile Settings -->
                <div id="profileSettings" class="settings-content p-4">
                    <h2 class="text-lg font-semibold mb-4">Profile Information</h2>

                    @if(session('success'))
                        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data" class="space-y-4">
                        @csrf

                        <!-- Profile Picture -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Profile Picture</label>
                            <div class="relative w-24 h-24">
                                <img id="profilePicturePreview" src="{{ Auth::user()->profile_picture ? asset(Auth::user()->profile_picture) : asset('assets/profilePicture/DefaultPfp.jpg') }}"
                                class="w-full h-full rounded-full object-cover border border-gray-300">

                                <label for="profilePictureInput"
                                    class="absolute inset-0 bg-black bg-opacity-40 rounded-full flex items-center justify-center cursor-pointer hover:bg-opacity-50">
                                    <i class="fas fa-camera text-white text-lg"></i>
                                </label>

                                <input id="profilePictureInput" type="file" name="profile_picture" class="hidden">
                            </div>

                            @if(Auth::user()->profile_picture)
                            <button type="button" class="text-red-500 text-sm hover:underline mt-2"
                                    onclick="document.getElementById('removePhotoForm').submit();">
                                Remove Picture
                            </button>
                            @endif
                        </div>

                        <!-- Display Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Display Name</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-gray">
                        </div>

                        <!-- Username -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Username</label>
                            <input type="text" name="username" value="{{ old('username', $user->username) }}" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-gray">
                        </div>

                        <!-- Bio -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bio</label>
                            <textarea name="bio" rows="3" class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-gray">{{ old('bio', $user->bio) }}</textarea>
                        </div>

                        <button type="submit" class="bg-brand-gray text-white px-6 py-3 rounded-lg hover:bg-brand-gray-dark">
                            Save Changes
                        </button>
                    </form>
                         @if(Auth::user()->profile_picture)
                <form id="removePhotoForm" method="POST" action="{{ route('profile.removePicture') }}" style="display: none;">
                    @csrf
                </form>
                    @endif
                </div>
            </div>


                   <!-- Security Settings -->
                   <div id="securitySettings" class="settings-content hidden p-4">
    <h2 class="text-lg font-semibold mb-4">Security Settings</h2>

    {{-- Success message --}}
    @if(session('success_security'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
            {{ session('success_security') }}
        </div>
    @endif

    {{-- Display validation errors --}}
    @if($errors->any())
        <div class="bg-red-100 text-red-800 p-3 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('settings.updateSecurity') }}" class="space-y-4">
        @csrf
        @method('PUT')

        {{-- Email --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
            <input type="email" name="email" value="{{ old('email', $user->email) }}"
                   class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-gray">
        </div>

        {{-- New Password --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">New Password</label>
            <input type="password" name="password"
                   class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-gray">
        </div>

        {{-- Confirm New Password --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Confirm New Password</label>
            <input type="password" name="password_confirmation"
                   class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-gray">
        </div>

        {{-- Current Password for identity verification --}}
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Current Password</label>
            <input type="password" name="current_password"
                   class="w-full p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-brand-gray"
                   required>
        </div>

        <button type="submit" class="bg-brand-gray text-white px-6 py-3 rounded-lg hover:bg-brand-gray-dark">
            Update Settings
        </button>
    </form>
</div>


    <script src="../js/settings.js"></script>
    <script src="../js/activeSidebar.js"></script>
</body>
</html>