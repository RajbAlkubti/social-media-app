<div class="space-y-1">
    <x-nav-link href="/home" icon="fas fa-home" label="Home" active="home" />
    <x-nav-link href="/explore" icon="fas fa-search" label="Explore" active="explore" />
    <x-nav-link href="/connections" icon="fas fa-users" label="Connections" active="connections" />
    <x-nav-link href="{{route('profile.show',Auth::user())}}" icon="fas fa-user" label="Profile" active="profile" />
    <x-nav-link href="/settings" icon="fas fa-cog" label="Settings" active="settings" />
</div>
