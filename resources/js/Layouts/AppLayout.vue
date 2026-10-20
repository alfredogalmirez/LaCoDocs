<script setup>
import { Link, router, useForm, usePage } from '@inertiajs/vue3';
import { ref } from 'vue';

// Grab the authenticated user data from Inertia shared props
const page = usePage();
const user = page.props.auth.user;
const logoutForm = useForm({});

const dropdownOpen = ref(false);

const searchQuery = ref('');

const handleSearch = () => {
    if (!searchQuery.value.trim()) return;

    router.get('/search', { q: searchQuery.value }, {
        preserveScroll: true,
    });
}

const logout = () => {
    logoutForm.post('/logout');
};
</script>

<template>
    <div class="flex min-h-screen bg-gray-50 text-gray-900">
        <!-- Sidebar -->
        <aside class="w-64 border-r border-gray-200 bg-white flex flex-col justify-between">
            <div>
                <!-- App Logo -->
                <div class="h-16 flex items-center px-6 border-b border-gray-200">
                    <span class="text-xl font-bold tracking-tight text-indigo-600 flex items-center gap-2">
                        <!-- Lightning Icon -->
                        <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                        LaCoDocs
                    </span>
                </div>

                <!-- Navigation Links -->
                <nav class="p-4 space-y-1">
                    <Link href="/"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                        </svg>
                        Dashboard
                    </Link>

                    <div class="pt-5 pb-2 px-3 text-xs font-semibold text-gray-400 uppercase tracking-wider">
                        Workspace
                    </div>

                    <Link href="/topics"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                        </svg>
                        Topics & Folders
                    </Link>

                    <Link href="/docs"
                        class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100 hover:text-gray-900 transition">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        All Documents
                    </Link>
                </nav>
            </div>

            <!-- Optional Sidebar Footer / User Info Context -->
            <div class="p-4 border-t border-gray-100 text-xs text-gray-400 flex items-center justify-between">
                <span>LaCoDocs</span>
                <span>v1.0</span>
            </div>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 flex flex-col min-w-0">
            <!-- Top Header -->
            <header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-8 shadow-sm">
                <!-- Search Bar -->
                <div class="w-96">
                    <form @submit.prevent="handleSearch">
                        <input type="text" v-model="searchQuery" placeholder="Search documentation, code blocks, SQL..."
                            class="w-full rounded-lg border border-gray-300 px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition">
                    </form>
                </div>

                <!-- Header Actions -->
                <div class="flex items-center gap-4">
                    <Link href="/docs/create"
                        class="w-9 h-9 rounded-full bg-indigo-600 hover:bg-indigo-700 text-white flex items-center justify-center font-bold text-lg transition shadow-sm"
                        title="Create Document">
                        +
                    </Link>

                    <div class="relative">
                        <button @click="dropdownOpen = !dropdownOpen"
                            class="flex items-center focus:outline-none transtition transform active:scale-95"
                            title="Account Menu">
                            <div
                                class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-700 font-bold flex items-center justify-center text-sm border border-indigo-200 uppercase">
                                {{ user.name.charAt(0) }}
                            </div>
                        </button>

                        <div v-if="dropdownOpen"
                            class="absolute right-0 mt-2 bg-white border border-gray-200 rounded-xl shadow-lg py-1 z-50">
                            <div class="px-4 py-3 border-b border-gray-100">
                                <p class="text-xs text-gray-500">Signed in as</p>
                                <p class="text-sm font-semibold text-gray-900 truncate">{{ user.email }}</p>
                            </div>

                            <div class="py-1">
                                <Link href="/profile" @click="dropdownOpen = false"
                                    class="flex items-center gap-2 block px-4 py-2 text-sm text-gray-700 hover:bg-gray-50 transition">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    Profile Settings
                                </Link>
                            </div>

                            <!-- Logout Option -->
                            <div class="border-t border-gray-100 pt-1">
                                <button @click="logout" :disabled="logoutForm.processing"
                                    class="flex items-center gap-2 w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition font-medium">
                                    <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Logout
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content Slot -->
            <div class="p-8 flex-1">
                <slot />
            </div>
        </main>
    </div>
</template>