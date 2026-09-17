<!-- Overlay Mobile -->
<div
    x-show="mobileOpen"
    x-transition.opacity
    @click="mobileOpen = false"
    class="fixed inset-0 bg-black/50 z-40 lg:hidden"
></div>

<aside
    class="fixed lg:static inset-y-0 left-0 z-50
           bg-slate-900 text-white
           transition-all duration-300 ease-in-out"

    :class="[
        mobileOpen
            ? 'translate-x-0'
            : '-translate-x-full lg:translate-x-0',

        sidebarOpen
            ? 'w-64'
            : 'w-20'
    ]"
>

    <!-- Logo -->
    <div class="h-16 flex items-center justify-between px-4 border-b border-slate-800">

        <div class="flex items-center gap-3">

            <div class="w-10 h-10 bg-pink-500 rounded flex items-center justify-center">
                M
            </div>

            <span
                x-show="sidebarOpen"
                x-transition
                class="font-semibold text-lg"
            >
                Monisa
            </span>

        </div>

        <!-- Desktop Collapse -->
        <button
            @click="sidebarOpen = !sidebarOpen"
            class="hidden lg:block"
        >
            <svg class="w-5 h-5">
                <path
                    fill="none"
                    stroke="currentColor"
                    stroke-width="2"
                    d="M15 19l-7-7 7-7"
                />
            </svg>
        </button>

        <!-- Mobile Close -->
        <button
            @click="mobileOpen = false"
            class="lg:hidden"
        >
            ✕
        </button>

    </div>

    <!-- Menu -->
    <nav class="p-3 overflow-y-auto h-[calc(100vh-64px)]">
        @php

        $user = auth()->user();
        $roleIds = $user->roles->pluck('id')->toArray();

        $menus = \App\Models\Menu::query()
            ->whereNull('parent_id')
            ->where('is_active', 1)
            ->whereHas('roles', function ($q) use ($roleIds) {
                $q->whereIn('role_id', $roleIds);
            })
            ->with([
                'children' => function ($q) use ($roleIds) {
                    $q->where('is_active', true)
                        ->whereHas('roles', function ($q2) use ($roleIds) {
                            $q2->whereIn('role_id', $roleIds);
                        })
                        ->orderBy('order');
                }
            ])
            ->orderBy('order')
            ->get();
        @endphp

        @foreach($menus as $menu)
            <x-menu-item :menu="$menu" />
        @endforeach

    </nav>

    <div class="border-t border-slate-800 p-4">
        <form
            action="{{ route('auth.logout') }}"
            method="POST"
            >
            @csrf

            <button
                type="submit"
                onclick="return confirm('Yakin ingin logout?')"
                class="w-full flex items-center gap-3 rounded-lg px-4 py-3 text-slate-400 hover:bg-red-600 hover:text-white transition">

                <i class="fa-solid fa-right-from-bracket"></i>

                <span x-show="sidebarOpen">

                    Logout

                </span>

            </button>

        </form>
    </div>

</aside>
