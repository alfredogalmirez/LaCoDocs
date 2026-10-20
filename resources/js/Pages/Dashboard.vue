<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';

defineProps({
    stats: Object,
    recentDocuments: Array,
    topics: Array
});

const page = usePage();
const user = computed(() => page.props.auth.user);

// Relative time helper
const timeAgo = (dateString) => {
    if (!dateString) return 'Recently';
    const seconds = Math.floor((new Date() - new Date(dateString)) / 1000);

    let interval = seconds / 86400;
    if (interval > 1) return Math.floor(interval) + (Math.floor(interval) === 1 ? ' day ago' : ' days ago');

    interval = seconds / 3600;
    if (interval > 1) return Math.floor(interval) + (Math.floor(interval) === 1 ? ' hour ago' : ' hours ago');

    interval = seconds / 60;
    if (interval > 1) return Math.floor(interval) + (Math.floor(interval) === 1 ? ' minute ago' : ' minutes ago');

    return 'Just now';
};
</script>

<template>

    <Head title="Dashboard" />

    <AppLayout>
        <div class="max-w-6xl mx-auto space-y-8">

            <!-- Welcome Header & Quick Actions -->
            <div
                class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white border border-gray-200 rounded-2xl p-6 shadow-sm">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                        Welcome back, {{ user.name }}!
                    </h1>
                    <p class="text-sm text-gray-500 mt-1">
                        Here is an overview of your personal knowledge base and recent notes.
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <Link href="/topics"
                        class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm font-medium rounded-xl transition">
                        Manage Topics
                    </Link>
                    <Link href="/docs/create"
                        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl shadow-sm transition flex items-center gap-2">
                        <span>+</span> New Document
                    </Link>
                </div>
            </div>

            <!-- Stats Row -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm flex items-center gap-4">
                    <div
                        class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Documents</p>
                        <p class="text-2xl font-bold text-gray-900 mt-0.5">{{ stats.documents }}</p>
                    </div>
                </div>

                <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm flex items-center gap-4">
                    <div
                        class="w-12 h-12 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">Total Topics</p>
                        <p class="text-2xl font-bold text-gray-900 mt-0.5">{{ stats.topics }}</p>
                    </div>
                </div>
            </div>

            <!-- Two Column Layout: Recent Docs & Quick Topics -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                <!-- Left: Recent Documents (Takes up 2 columns) -->
                <div class="lg:col-span-2 bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-5">
                        <h2 class="text-lg font-semibold text-gray-900">Recent Documents</h2>
                        <Link href="/docs" class="text-xs text-indigo-600 font-medium hover:underline">
                            View All →
                        </Link>
                    </div>

                    <div v-if="recentDocuments.length === 0"
                        class="text-center py-10 text-gray-400 text-sm border border-dashed rounded-lg">
                        No documents created yet. Start writing your first guide!
                    </div>

                    <div v-else class="divide-y divide-gray-100">
                        <div v-for="doc in recentDocuments" :key="doc.id"
                            class="py-3.5 flex items-center justify-between gap-4">
                            <div class="min-w-0">
                                <Link :href="`/docs/${doc.slug}`"
                                    class="font-medium text-gray-900 hover:text-indigo-600 truncate block transition">
                                    {{ doc.title }}
                                </Link>
                                <p class="text-xs text-gray-400 mt-0.5 flex items-center gap-2">
                                    <span class="text-indigo-600 font-medium">{{ doc.topic?.name ?? 'General' }}</span>
                                    <span>&bull;</span>
                                    <span>Updated {{ timeAgo(doc.updated_at) }}</span>
                                </p>
                            </div>

                            <Link :href="`/docs/${doc.slug}`"
                                class="text-xs px-3 py-1.5 border border-gray-200 rounded-lg text-gray-600 hover:bg-gray-50 transition shrink-0">
                                Read
                            </Link>
                        </div>
                    </div>
                </div>

                <!-- Right: Quick Topics Sidebar (Takes up 1 column) -->
                <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                    <div class="flex items-center justify-between mb-5">
                        <h2 class="text-lg font-semibold text-gray-900">Workspace Folders</h2>
                        <Link href="/topics" class="text-xs text-indigo-600 font-medium hover:underline">
                            Manage
                        </Link>
                    </div>

                    <div v-if="topics.length === 0" class="text-center py-8 text-gray-400 text-sm">
                        No topics found.
                    </div>

                    <div v-else class="space-y-2.5">
                        <Link v-for="topic in topics" :key="topic.id" :href="`/topics/${topic.slug}`"
                            class="flex items-center justify-between p-3 rounded-xl border border-gray-100 hover:border-indigo-200 hover:bg-indigo-50/30 transition group">

                            <div class="flex items-center gap-2.5 min-w-0">
                                <span class="text-gray-400 group-hover:text-indigo-600 transition">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                    </svg>
                                </span>
                                <span class="text-sm font-medium text-gray-700 group-hover:text-gray-900 truncate">{{
                                    topic.name }}</span>
                            </div>

                            <span class="text-xs bg-gray-100 text-gray-500 px-2 py-0.5 rounded-full">
                                {{ topic.documents_count ?? 0 }}
                            </span>
                        </Link>
                    </div>
                </div>

            </div>

        </div>
    </AppLayout>
</template>