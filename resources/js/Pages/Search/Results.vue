<script setup>
import { Link } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    query: String,
    documents: Array,
    topics: Array,
});
</script>

<template>
    <AppLayout>
        <div class="max-w-4xl mx-auto space-y-6">

            <!-- Search Header -->
            <div>
                <h1 class="text-2xl font-bold text-gray-900 tracking-tight">
                    Search Results for "{{ query }}"
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Found {{ documents.length }} document(s) and {{ topics.length }} topic(s).
                </p>
            </div>

            <!-- Documents Results -->
            <div class="space-y-4">
                <h2 class="text-lg font-semibold text-gray-800 border-b pb-2">Documents</h2>

                <div v-if="documents.length === 0" class="text-sm text-gray-500 py-2">
                    No matching documents found.
                </div>

                <div v-for="doc in documents" :key="doc.id"
                    class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm hover:border-indigo-300 transition">
                    <Link :href="`/docs/${doc.slug}`" class="flex items-center gap-2 text-base font-semibold text-indigo-600 hover:underline">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        {{ doc.title }}
                    </Link>
                    <p class="text-xs text-gray-400 mt-1">
                        Last updated: {{ new Date(doc.updated_at).toLocaleDateString() }}
                    </p>
                </div>
            </div>

            <!-- Topics Results -->
            <div class="space-y-4 pt-4">
                <h2 class="text-lg font-semibold text-gray-800 border-b pb-2">Topics & Folders</h2>

                <div v-if="topics.length === 0" class="text-sm text-gray-500 py-2">
                    No matching topics found.
                </div>

                <div v-for="topic in topics" :key="topic.id"
                    class="bg-white border border-gray-200 rounded-xl p-4 shadow-sm hover:border-indigo-300 transition">
                    <Link :href="`/topics/${topic.slug}`"
                        class="text-base font-semibold text-gray-800 hover:text-indigo-600">
                        📁 {{ topic.name }}
                    </Link>
                </div>
            </div>

        </div>
    </AppLayout>
</template>