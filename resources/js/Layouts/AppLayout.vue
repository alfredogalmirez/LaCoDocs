<script setup>
import { ref, watch } from 'vue';
import { Link, useForm, usePage } from '@inertiajs/vue3';
import { toast } from 'vue3-toastify';

const page = usePage();
const user = page.props.auth.user;

watch(
    () => page.props.flash,
    (flash) => {
        if (flash?.success) {
            toast.success(flash.success);
        }
        if (flash?.error) {
            toast.error(flash.error);
        }
    },
    { deep: true, immediate: true}
)

const dropdownOpen = ref(false);
const logoutForm = useForm({});

const logout = () => {
    logoutForm.post('/logout');
};
</script>

<template>
    <div class="min-h-screen bg-gray-50 text-gray-900 flex">

        <!-- Sidebar Navigation -->
        <aside class="w-64 bg-white border-r border-gray-200 flex flex-col justify-between shrink-0 hidden md:flex">
            <!-- Sidebar Header / Brand -->
            <div class="p-6">
                <Link href="/" class="flex items-center gap-3 group">
                    <div
                        class="w-9 h-9 rounded-xl bg-gray-900 flex items-center justify-center text-white shadow-sm hover:bg-gray-800 transition">
                        <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <!-- Document Sheet Outline with Folded Corner -->
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <!-- Code / Text Lines Inside -->
                            <path d="M8 12h8"></path>
                            <path d="M8 16h5"></path>
                            <path d="M8 8h1"></path>
                        </svg>
                    </div>
                    <span class="text-lg font-bold tracking-tight text-gray-900">LaCoDocs</span>
                </Link>

                <!-- Navigation Links -->
                <nav class="mt-8 space-y-1.5">
                    <p class="px-3 text-[10px] font-bold text-gray-400 uppercase tracking-wider mb-2">Workspace</p>

                    <Link href="/docs"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition"
                        :class="$page.url.startsWith('/docs') ? 'bg-gray-100 text-gray-900 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                        </svg>
                        Documents
                    </Link>

                    <Link href="/topics"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition"
                        :class="$page.url.startsWith('/topics') ? 'bg-gray-100 text-gray-900 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                        </svg>
                        Topics & Folders
                    </Link>

                    <Link href="/starred"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium transition"
                        :class="$page.url.startsWith('/starred') ? 'bg-gray-100 text-gray-900 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900'">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.690h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.690l1.519-4.674z" />
                        </svg>
                        Favorites
                    </Link>
                </nav>
            </div>

            <!-- Sidebar Footer / Quick Info -->
            <div class="p-6 border-t border-gray-100">
                <div class="bg-gray-50 border border-gray-200/80 rounded-xl p-3 text-xs text-gray-500">
                    <p class="font-semibold text-gray-700">Workspace Active</p>
                    <p class="mt-0.5 truncate">{{ user.email }}</p>
                </div>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 flex flex-col min-w-0">
            <!-- Top Header -->
            <header
                class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 lg:px-8 shadow-sm">
                <!-- Search Bar -->
                <div class="w-full max-w-md">
                    <form action="/search" method="GET">
                        <div class="relative">
                            <span
                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </span>
                            <input type="text" name="q" placeholder="Search documentation, code blocks, SQL..."
                                class="w-full pl-9 pr-4 py-2 rounded-lg border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition" />
                        </div>
                    </form>
                </div>

                <!-- Header Actions -->
                <div class="flex items-center gap-4">
                    <Link href="/docs/create"
                        class="px-3.5 py-2 rounded-lg bg-gray-900 hover:bg-gray-800 text-white font-semibold text-xs tracking-wide uppercase transition shadow-sm flex items-center gap-1.5">
                        <span class="text-base font-bold leading-none">+</span> New Document
                    </Link>

                    <!-- Avatar Dropdown Wrapper -->
                    <div class="relative">

                        <!-- Transparent Click-Outside Backdrop -->
                        <div v-if="dropdownOpen" @click="dropdownOpen = false" class="fixed inset-0 z-40"></div>

                        <!-- Avatar Button -->
                        <button @click="dropdownOpen = !dropdownOpen"
                            class="flex items-center focus:outline-none transition transform active:scale-95 relative z-50"
                            title="Account Menu">
                            <div
                                class="w-9 h-9 rounded-full bg-gray-100 text-gray-900 font-bold flex items-center justify-center text-sm border border-gray-300 uppercase">
                                {{ user.name.charAt(0) }}
                            </div>
                        </button>

                        <!-- Dropdown Menu Box -->
                        <div v-if="dropdownOpen"
                            class="absolute right-0 mt-2 w-56 bg-white border border-gray-200 rounded-xl shadow-xl py-1.5 z-50">

                            <!-- User Info Header inside Dropdown -->
                            <div class="px-4 py-2.5 border-b border-gray-100">
                                <p class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider">Signed in as
                                </p>
                                <p class="text-xs font-semibold text-gray-900 truncate mt-0.5">{{ user.email }}</p>
                            </div>

                            <!-- Menu Links -->
                            <div class="py-1">
                                <Link href="/profile" @click="dropdownOpen = false"
                                    class="flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-gray-700 hover:bg-gray-50 transition">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Profile Settings
                                </Link>
                            </div>

                            <!-- Logout Option -->
                            <div class="border-t border-gray-100 pt-1">
                                <button @click="logout" :disabled="logoutForm.processing"
                                    class="w-full text-left flex items-center gap-2.5 px-4 py-2 text-xs font-medium text-red-600 hover:bg-red-50 transition">
                                    <svg class="w-4 h-4 text-red-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Sign out
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content Slot -->
            <div class="flex-1">
                <slot />
            </div>
        </main>

    </div>
</template>