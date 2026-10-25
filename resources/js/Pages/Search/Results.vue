<script setup>
import { Head, Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    query: String,
    documents: Array,
    topics: Array,
});
</script>

<template>
    <Head :title="`Search: ${query}`" />
    
    <AppLayout>
        <div class="p-8 max-w-4xl mx-auto space-y-8">

            <!-- Search Header -->
            <div class="border-b border-gray-200 pb-6">
                <h1 class="text-3xl font-bold tracking-tight text-gray-900">
                    Search Results for "<span class="text-gray-600">{{ query }}</span>"
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Found <span class="font-semibold text-gray-900">{{ documents.length }}</span> document(s) and <span class="font-semibold text-gray-900">{{ topics.length }}</span> topic(s).
                </p>
            </div>

            <!-- Documents Results -->
            <div class="space-y-4">
                <h2 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Documents</h2>

                <div v-if="documents.length === 0" class="bg-white border border-gray-200 rounded-xl p-6 text-sm text-gray-500">
                    No matching documents found.
                </div>

                <div v-for="doc in documents" :key="doc.id"
                    class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm hover:border-gray-400 transition flex items-center justify-between">
                    <div>
                        <Link :href="`/docs/${doc.slug}`"
                            class="flex items-center gap-2.5 text-base font-bold text-gray-900 hover:text-gray-600 transition">
                            <svg class="w-5 h-5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                            {{ doc.title }}
                        </Link>
                        <p class="text-xs text-gray-400 mt-1 pl-7">
                            Last updated {{ new Date(doc.updated_at).toLocaleDateString() }}
                        </p>
                    </div>

                    <Link :href="`/docs/${doc.slug}`" class="px-3 py-1.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-700 hover:bg-gray-900 hover:text-white transition shrink-0">
                        View Guide &rarr;
                    </Link>
                </div>
            </div>

            <!-- Topics Results -->
            <div class="space-y-4 pt-4">
                <h2 class="text-xs font-bold text-gray-400 uppercase tracking-wider">Topics & Folders</h2>

                <div v-if="topics.length === 0" class="bg-white border border-gray-200 rounded-xl p-6 text-sm text-gray-500">
                    No matching topics found.
                </div>

                <div v-for="topic in topics" :key="topic.id"
                    class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm hover:border-gray-400 transition flex items-center justify-between">
                    <Link :href="`/topics/${topic.slug}`"
                        class="flex items-center gap-2.5 text-base font-bold text-gray-900 hover:text-gray-600 transition">
                        <svg class="w-5 h-5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                        </svg>
                        {{ topic.name }}
                    </Link>

                    <Link :href="`/topics/${topic.slug}`" class="px-3 py-1.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-700 hover:bg-gray-900 hover:text-white transition shrink-0">
                        View Topic &rarr;
                    </Link>
                </div>
            </div>

        </div>
    </AppLayout>
</template>