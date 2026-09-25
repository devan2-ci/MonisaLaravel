<header
    class="h-16 bg-white border-b px-6 flex items-center justify-between"
>

    <div class="flex items-center gap-3">

        <!-- Mobile Button -->
        <button
            @click="mobileOpen = true"
            class="lg:hidden"
        >
            <svg
                class="w-6 h-6"
                fill="none"
                viewBox="0 0 24 24"
                stroke="currentColor"
            >
                <path
                    stroke-linecap="round"
                    stroke-linejoin="round"
                    stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16"
                />
            </svg>
        </button>

        <h1 class="font-semibold text-lg">
            Dashboard {{ auth()->user()->name }}
        </h1>

    </div>

    <div class="flex items-center gap-4">

        <button class="relative">
            🔔
        </button>

        <!-- Profile Dropdown -->
        <div
            x-data="{ open: false }"
            class="relative"
        >

            <button
                @click="open = !open"
                class="flex items-center gap-2 hover:cursor-pointer"
            >
                <img
                    src="https://i.pravatar.cc/40"
                    alt="Profile"
                    class="w-10 h-10 rounded-lg object-cover border"
                >
            </button>

            <!-- Dropdown -->
            <div
                x-show="open"
                @click.away="open = false"
                x-transition
                class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border z-50"
            >

                <!-- User Info -->
                <div class="px-4 py-3 border-b">
                    <p class="font-semibold text-slate-800">
                        {{ auth()->user()->name ?? 'Administrator' }}
                    </p>

                    <p class="text-sm text-slate-500 truncate">
                        {{ auth()->user()->email ?? 'admin@mail.com' }}
                    </p>
                </div>

                <!-- Menu -->
                <div class="py-2">

                    <a
                        href=""
                        class="flex items-center gap-3 px-4 py-2 text-sm text-slate-700 hover:bg-slate-100"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="w-5 h-5"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M5.121 17.804A9 9 0 1118.36 4.566a9 9 0 01-13.238 13.238z"/>
                        </svg>

                        Profile
                    </a>

                </div>

                <!-- Logout -->
                <div class="border-t">

                    <form
                        method="POST"
                        action="{{ route('auth.logout') }}"
                    >
                        @csrf

                        <button
                            type="submit"
                            class="w-full flex items-center gap-3 px-4 py-2 text-sm text-red-600 hover:bg-red-50 hover:cursor-pointer"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg"
                                class="w-5 h-5"
                                fill="none"
                                viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7"/>
                            </svg>

                            Logout
                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</header>
