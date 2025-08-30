@php
    $suggestedUsers = $suggestedUsers ?? \App\Models\User::where('id', '!=', auth()->id())
                ->where('user_type', '!=', 'admin') // ← Exclude admins
                ->inRandomOrder()
                ->take(3)
                ->get();
                
    $authUser = auth()->user();
@endphp

@foreach ($suggestedUsers as $user)
    <div class="flex items-center justify-between mb-4">
        <div class="flex items-center space-x-3 min-w-0">
            <a href="{{ route('profile.show', $user->id) }}" class="shrink-0">
                <img src="{{ $user->profile_picture_url ?? asset('assets/profilePicture/DefaultPfp.jpg') }}"
                     class="w-10 h-10 rounded-full object-cover">
            </a>

            <div class="flex flex-col overflow-hidden">
                <a href="{{ route('profile.show', $user->id) }}"
                   class="font-bold text-gray-900 hover:underline truncate max-w-[150px] sm:max-w-[200px]">
                    {{ $user->name }}
                </a>
                <a href="{{ route('profile.show', $user->id) }}"
                   class="text-gray-500 hover:underline truncate max-w-[150px] sm:max-w-[200px]">
                    &#64;{{ $user->username }}
                </a>
            </div>
        </div>

        <div class="shrink-0 ml-2">
            @if ($authUser->following->contains($user->id))
                <form action="{{ route('unfollow', $user->id) }}" method="POST" class="inline">
                    @csrf
                    @method('DELETE')
                    <button class="bg-gray-300 text-black px-4 py-1.5 rounded-full text-sm font-medium hover:bg-gray-400">
                        Following
                    </button>
                </form>
            @else
                <form action="{{ route('follow', $user->id) }}" method="POST" class="inline">
                    @csrf
                    <button class="bg-black text-white px-4 py-1.5 rounded-full text-sm font-medium hover:bg-gray-800">
                        Follow
                    </button>
                </form>
            @endif
        </div>
    </div>
@endforeach
