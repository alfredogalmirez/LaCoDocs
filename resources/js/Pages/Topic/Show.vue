<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link } from '@inertiajs/vue3';

defineProps({
    topic: Object
});
</script>

<template>
    <AppLayout>
        <div class="max-w-5xl mx-auto py-6">

            <!-- Breadcrumbs Navigation -->
            <nav class="mb-6 flex items-center text-sm text-gray-500 gap-2">
                <Link href="/docs" class="hover:text-indigo-600">Knowledge Base</Link>
                <span>/</span>
                <span class="text-gray-900 font-medium">{{ topic.name }}</span>
            </nav>

            <!-- Header Section with Action -->
            <div
                class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white border border-gray-200 rounded-xl p-6 shadow-sm mb-8">
                <div>
                    <div class="flex items-center gap-3">
                        <span class="text-3xl">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                            </svg>
                        </span>
                        <h1 class="text-2xl font-bold text-gray-900">{{ topic.name }}</h1>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">
                        Explore sub-topics and guides contained within this section.
                    </p>
                </div>

                <!-- Contextual Add Button -->
                <Link :href="`/docs/create?topic_id=${topic.id}`"
                    class="bg-indigo-600 hover:bg-indigo-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition shadow-sm">
                    + Add Document
                </Link>
            </div>

            <!-- Sub-Topics Section (Grid Layout) -->
            <div class="mb-10" v-if="topic.children && topic.children.length > 0">
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <span>Sub-Topics</span>
                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full font-normal">
                        {{ topic.children.length }}
                    </span>
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <Link v-for="child in topic.children" :key="child.id" :href="`/topics/${child.slug}`"
                        class="bg-white border border-gray-200 rounded-xl p-4 hover:border-indigo-300 hover:shadow-sm transition flex items-center justify-between group">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold text-sm">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                </svg>
                            </div>
                            <span class="font-medium text-gray-900 group-hover:text-indigo-600 transition">
                                {{ child.name }}
                            </span>
                        </div>
                        <span class="text-gray-400 group-hover:translate-x-1 transition-transform">→</span>
                    </Link>
                </div>
            </div>

            <!-- Documents Section -->
            <div>
                <h2 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                    <span>Documents</span>
                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-0.5 rounded-full font-normal">
                        {{ topic.documents?.length ?? 0 }}
                    </span>
                </h2>

                <!-- List of Documents -->
                <div v-if="topic.documents && topic.documents.length > 0" class="space-y-3">
                    <Link v-for="document in topic.documents" :key="document.id" :href="`/docs/${document.slug}`"
                        class="bg-white border border-gray-200 rounded-xl p-4 hover:border-indigo-300 hover:shadow-sm transition flex items-center justify-between group block">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-lg bg-gray-50 text-gray-600 flex items-center justify-center">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-medium text-gray-900 group-hover:text-indigo-600 transition">
                                    {{ document.title }}
                                </h3>
                                <p class="text-xs text-gray-400">Click to read full guide</p>
                            </div>
                        </div>
                        <span class="text-gray-400 text-sm group-hover:translate-x-1 transition-transform">Read →</span>
                    </Link>
                </div>

                <!-- Empty State (Guides user when no docs exist) -->
                <div v-else class="bg-white border border-dashed border-gray-300 rounded-xl p-8 text-center shadow-sm">
                    <p class="text-gray-500 text-sm mb-3">No documents found under this topic yet.</p>
                    <Link :href="`/docs/create?topic_id=${topic.id}`"
                        class="text-indigo-600 font-medium text-sm hover:underline">
                        Create the first document in this section →
                    </Link>
                </div>
            </div>

        </div>
    </AppLayout>
</template>