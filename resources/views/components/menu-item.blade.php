@props([
    'menu'
])

@php
    $hasChildren = $menu->children->count() > 0;

    $isActive = false;

    if ($menu->route) {
        $isActive = request()->routeIs($menu->route);
    }

    foreach ($menu->children as $child) {
        if ($child->route &&
            request()->routeIs($child->route)) {
            $isActive = true;
            break;
        }
    }
@endphp

@if($hasChildren)

    <div
        x-data="{ open: {{ $isActive ? 'true' : 'false' }} }"
        class="mb-1"
    >

        <button
            @click="open = !open"
            class="w-full flex items-center justify-between px-4 py-3 rounded-lg transition
            {{ $isActive
                ? 'bg-slate-800 text-white'
                : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}"
        >

            <div class="flex items-center gap-3">

                @if($menu->icon)
                    <i class="{{ $menu->icon }}"></i>
                @endif

                <span
                    x-show="sidebarOpen"
                    class="hidden lg:block"
                >
                    {{ $menu->name }}
                </span>

                <span class="lg:hidden">
                    {{ $menu->name }}
                </span>

            </div>

            <svg
                class="w-4 h-4 transition-transform"
                :class="open ? 'rotate-90' : ''"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor">

                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M9 5l7 7-7 7"/>
            </svg>

        </button>

        <div
            x-show="open"
            x-transition
            class="mt-1 ml-6 space-y-1"
        >

            @foreach($menu->children as $child)

                @php
                    $href = '#';

                    if (!empty($child->route) && Route::has($child->route)) {
                        $href = route($child->route);
                    }
                @endphp

                <a
                    href="{{ $href }}"
                    class="block px-4 py-2 rounded-md text-sm transition
                    {{ request()->routeIs($child->route)
                        ? 'bg-sky-500 text-white'
                        : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}"
                >
                    {{ $child->name }}
                </a>

            @endforeach

        </div>

    </div>

@else
    @php
        $href = '';

        if (!empty($menu->route) && Route::has($menu->route)) {
            $href = route($menu->route);
        }
    @endphp

    <a
        href="{{ $href }}"
        class="flex items-center gap-3 px-4 py-3 rounded-lg transition
        {{ $isActive
            ? 'bg-slate-800 text-white'
            : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}"
    >

        @if($menu->icon)
            <i class="{{ $menu->icon }}"></i>
        @endif

        <span x-show="sidebarOpen">
            {{ $menu->name }}
        </span>

    </a>

@endif
