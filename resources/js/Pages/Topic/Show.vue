<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    topic: Object
});

const goBack = () => {
    window.history.back();
}
</script>

<template>
    <Head :title="topic.name" />
    
    <AppLayout>
        <div class="max-w-5xl mx-auto p-8 space-y-8">

            <!-- Breadcrumbs Navigation -->
            <nav class="flex items-center text-sm text-gray-500">
                <button @click="goBack" class="text-xs font-semibold text-gray-500 hover:text-gray-900 transition flex items-center gap-1.5">
                    &larr; Back
                </button>
            </nav>

            <!-- Header Section with Action -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                <div>
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-gray-100 text-gray-900 flex items-center justify-center shrink-0 border border-gray-200">
                            <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                            </svg>
                        </div>
                        <h1 class="text-2xl font-bold tracking-tight text-gray-900">{{ topic.name }}</h1>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">
                        Explore sub-topics and guides contained within this section.
                    </p>
                </div>

                <!-- Contextual Add Button -->
                <Link :href="`/docs/create?topic_id=${topic.id}`"
                    class="inline-flex items-center justify-center px-4 py-2.5 rounded-lg bg-gray-900 hover:bg-gray-800 text-white font-semibold text-xs tracking-wider uppercase transition shadow-sm shrink-0">
                    <span class="text-base font-bold leading-none mr-1.5">+</span> Add Document
                </Link>
            </div>

            <!-- Sub-Topics Section (Grid Layout) -->
            <div class="space-y-4" v-if="topic.children && topic.children.length > 0">
                <div class="flex items-center justify-between">
                    <h2 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Sub-Topics</h2>
                    <span class="text-xs bg-gray-100 text-gray-600 px-2.5 py-0.5 rounded-md font-semibold">
                        {{ topic.children.length }}
                    </span>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <Link v-for="child in topic.children" :key="child.id" :href="`/topics/${child.slug}`"
                        class="bg-white border border-gray-200 rounded-xl p-4 hover:border-gray-400 transition flex items-center justify-between group">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-8 h-8 rounded-lg bg-gray-100 text-gray-700 flex items-center justify-center shrink-0 border border-gray-200">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                </svg>
                            </div>
                            <span class="font-bold text-gray-900 text-sm truncate group-hover:text-gray-600 transition">
                                {{ child.name }}
                            </span>
                        </div>
                        <span class="text-gray-400 group-hover:translate-x-1 transition-transform shrink-0 ml-2">&rarr;</span>
                    </Link>
                </div>
            </div>

            <!-- Documents Section -->
            <div class="space-y-4">
                <div class="flex items-center justify-between">
                    <h2 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Documents</h2>
                    <span class="text-xs bg-gray-100 text-gray-600 px-2.5 py-0.5 rounded-md font-semibold">
                        {{ topic.documents?.length ?? 0 }}
                    </span>
                </div>

                <!-- List of Documents -->
                <div v-if="topic.documents && topic.documents.length > 0" class="space-y-3">
                    <Link v-for="document in topic.documents" :key="document.id" :href="`/docs/${document.slug}`"
                        class="bg-white border border-gray-200 rounded-xl p-4 hover:border-gray-400 transition flex items-center justify-between group block">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-8 h-8 rounded-lg bg-gray-100 text-gray-700 flex items-center justify-center shrink-0 border border-gray-200">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <h3 class="font-bold text-gray-900 text-sm truncate group-hover:text-gray-600 transition">
                                    {{ document.title }}
                                </h3>
                                <p class="text-xs text-gray-400">Click to read full guide</p>
                            </div>
                        </div>
                        <span class="text-xs font-semibold text-gray-700 group-hover:translate-x-1 transition-transform shrink-0 ml-2">Read &rarr;</span>
                    </Link>
                </div>

                <!-- Empty State -->
                <div v-else class="bg-white border border-dashed border-gray-300 rounded-xl p-8 text-center shadow-sm space-y-2">
                    <p class="text-gray-500 text-xs font-medium">No documents found under this topic yet.</p>
                    <div>
                        <Link :href="`/docs/create?topic_id=${topic.id}`"
                            class="text-xs font-semibold text-gray-900 hover:underline">
                            Create the first document in this section &rarr;
                        </Link>
                    </div>
                </div>
            </div>

        </div>
    </AppLayout>
</template>