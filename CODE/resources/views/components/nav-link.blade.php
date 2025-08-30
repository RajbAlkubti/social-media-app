@php
    $isActive = request()->is($active ?? '');
@endphp

<a href="{{ $href }}" {{ $attributes->merge([
    'class' => 'flex items-center space-x-3 px-3 py-3 rounded-full transition-colors hover:bg-gray-100 nav-item' . ($isActive ? ' active' : '')
]) }}>
    <i class="{{ $icon }} text-xl"></i>
    <span class="text-xl font-medium hidden xl:block">{{ $label }}</span>
</a>

