<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>𝕐 - Connections</title>
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
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
</head>

<body class="bg-brand-light min-h-screen font-sans">
  <div class="flex min-h-screen">
    <!-- Sidebar -->
    <div id="sidebar" class="fixed top-0 left-0 h-screen lg:flex lg:w-64 xl:w-72 bg-white border-r border-gray-200 flex-col">
      <div class="p-4">
        <div
          class="w-8 h-8 bg-gradient-to-r from-brand-gray to-brand-gray-dark rounded-full flex items-center justify-center mb-8">
          <span class="text-white font-bold">𝕐</span>
        </div>
        <nav class="space-y-2">
          <x-sidebar-nav />
        </nav>
      </div>

      <div id="userSection" class="mt-auto p-4">
        <div
          class="flex items-center space-x-3 p-3 rounded-full hover:bg-gray-100 cursor-pointer">
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
      <div class="flex-1 max-w-2xl mx-auto bg-white border-x border-gray-200">
        <div
          class="sticky top-0 bg-white bg-opacity-80 backdrop-blur-md border-b border-gray-200 p-4">
          <h1 class="text-xl font-bold">Connections</h1>
        </div>

        <div class="border-b border-gray-200">
          <div class="flex">
            <button onclick="switchConnectionTab('followers')"
              class="flex-1 py-4 text-center font-medium hover:bg-gray-50 connection-tab active"
              data-tab="followers">
              Followers
            </button>
            <button onclick="switchConnectionTab('following')"
              class="flex-1 py-4 text-center font-medium hover:bg-gray-50 connection-tab"
              data-tab="following">
              Following
            </button>
          </div>
        </div>

        <div id="connectionsContent">
          <!-- Followers Tab -->
          <div id="followersContent" class="connection-content">
            @foreach(Auth::user()->followers as $follower)
            <div class="space-y-0">
              <div class="p-4 border-b border-gray-100 hover:bg-gray-50 transition-colors">
                <div class="flex items-center justify-between">
                  <div class="flex items-center space-x-3">
                    <a href="{{ route('profile.show', $follower->id) }}" class="flex items-center space-x-3">
                    <img src="{{ $follower->profile_picture   ? asset($follower->profile_picture) : asset('assets/profilePicture/DefaultPfp.jpg') }}"
                      class="w-12 h-12 rounded-full" alt="Profile picture of {{ $follower->name }}">
                    <div>
                      <p class="font-bold text-gray-900">{{ $follower->name }}</p>
                      <p class="text-sm text-gray-500">{{ $follower->username }}</p>
                      @if($follower->bio)
                      <p class="text-sm text-gray-600 mt-1">{{ $follower->bio }}</p>
                      @endif
                    </div>
                    </a>
                  </div>

                  @if(!Auth::user()->following->contains($follower->id))
                  <form method="POST" action="{{ route('follow', $follower->id) }}"
                    onsubmit="toggleFollowButton(this.querySelector('button'), {{ $follower->id }}, false); return false;">
                    @csrf
                    <button type="submit"
                      class="bg-black text-white px-4 py-2 rounded-full text-sm font-medium hover:bg-gray-800"
                      data-following="false">
                      Follow back
                    </button>
                  </form>
                  @else
                  <form method="POST" action="{{ route('unfollow', $follower->id) }}"
                    onsubmit="toggleFollowButton(this.querySelector('button'), {{ $follower->id }}, true); return false;">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                      class="border border-gray-300 text-gray-700 px-4 py-2 rounded-full text-sm font-medium hover:bg-gray-50"
                      data-following="true">
                      Following
                    </button>
                  </form>
                  @endif

                </div>
              </div>
            </div>
            @endforeach
          </div>

          <!-- Following Tab -->
          <div id="followingContent" class="connection-content hidden">
            @foreach(Auth::user()->following as $followedUser)
            <div class="space-y-0">
              <div class="p-4 border-b border-gray-100 hover:bg-gray-50 transition-colors">
                <div class="flex items-center justify-between">
                  <div class="flex items-center space-x-3">
                    <a href="{{ route('profile.show', $followedUser->id) }}" class="flex items-center space-x-3">
                    <img src="{{ $followedUser->profile_picture ? asset($followedUser->profile_picture) : asset('assets/profilePicture/DefaultPfp.jpg') }}"
                 class="w-12 h-12 rounded-full" alt="Profile picture of {{ $followedUser->name }}">
                    <div>
                      <p class="font-bold text-gray-900">{{ $followedUser->name }}</p>
                      <p class="text-sm text-gray-500">{{ $followedUser->username }}</p>
                      @if($followedUser->bio)
                      <p class="text-sm text-gray-600 mt-1">{{ $followedUser->bio }}</p>
                      @endif
                    </div>
                  </a>
                  </div>                 
                  <form method="POST" action="{{ route('unfollow', $followedUser->id) }}"
                    onsubmit="toggleFollowButton(event, this.querySelector('button'))">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                      class="border border-gray-300 text-gray-700 px-4 py-2 rounded-full text-sm font-medium hover:bg-gray-50"
                      data-following="true">
                      Following
                    </button>
                  </form>

                </div>
              </div>
            </div>
            @endforeach
          </div>

        </div>
      </div>

      <!-- Right Sidebar -->
      <div class="hidden lg:block w-80 p-4">
        <div class="bg-gray-50 rounded-2xl p-4 mb-4">
          <h2 class="text-xl font-bold mb-4">Your Connections</h2>
          <div class="space-y-3">
            <div class="flex justify-between">
              <span class="text-gray-600">Followers</span>
              <span class="font-bold text-lg">{{ Auth::user()->followers->count() }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-gray-600">Following</span>
              <span class="font-bold text-lg">{{ Auth::user()->following->count() }}</span>
            </div>
          </div>
        </div>

        <div class="bg-gray-50 rounded-2xl p-4">
          <h2 class="text-xl font-bold mb-4">Suggested for you</h2>
          <div class="space-y-3">
             @include('components.suggested-users')
          </div>
        </div>

      </div>
    </div>

  </div>

  <script src="../js/connections.js"></script>
  <script src="../js/activeSidebar.js"></script>
</body>

</html>
