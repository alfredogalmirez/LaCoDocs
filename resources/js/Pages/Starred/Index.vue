<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineProps({
    documents: Array,
});
</script>

<template>
    <Head title="Starred Guides" />
    
    <AppLayout>
        <div class="p-8 max-w-7xl mx-auto space-y-8">
            
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 pb-6 border-b border-gray-200">
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900">Starred Guides</h1>
                    <p class="mt-1 text-sm text-gray-500">Your curated collection of essential documentation and quick references.</p>
                </div>
                <div class="flex items-center gap-2">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-xs font-semibold">
                        <svg class="w-4 h-4 fill-current text-amber-500" viewBox="0 0 24 24">
                            <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.690h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.690l1.519-4.674z"/>
                        </svg>
                        {{ documents.length }} Starred Guides
                    </span>
                </div>
            </div>

            <!-- Empty State -->
            <div v-if="documents.length === 0" class="bg-white border border-dashed border-gray-300 rounded-2xl p-12 text-center space-y-4 shadow-sm">
                <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-500 flex items-center justify-center mx-auto border border-amber-200">
                    <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                        <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.690h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.690l1.519-4.674z"/>
                    </svg>
                </div>
                <div class="space-y-1">
                    <h2 class="text-base font-bold text-gray-900">No starred guides yet</h2>
                    <p class="text-xs text-gray-500 max-w-sm mx-auto">Click the star button on any document to pin it here for rapid access across your workspace.</p>
                </div>
                <div class="pt-2">
                    <Link href="/docs" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-gray-900 hover:bg-gray-800 text-white text-xs font-semibold uppercase tracking-wider transition shadow-sm">
                        Explore Documents
                    </Link>
                </div>
            </div>

            <!-- Cards Grid Layout -->
            <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="doc in documents" :key="doc.id" 
                    class="bg-white border border-gray-200 rounded-2xl p-6 shadow-sm hover:shadow-md hover:border-gray-300 transition flex flex-col justify-between space-y-4 group">
                    
                    <!-- Card Header: Topic Badge & Star Badge -->
                    <div class="flex items-center justify-between">
                        <span v-if="doc.topic" class="text-[11px] font-semibold text-gray-700 uppercase tracking-wider bg-gray-100 px-2.5 py-1 rounded-lg border border-gray-200">
                            {{ doc.topic.name }}
                        </span>
                        <span v-else class="text-[11px] font-semibold text-gray-400 uppercase tracking-wider bg-gray-50 px-2.5 py-1 rounded-lg border border-gray-100">
                            Uncategorized
                        </span>
                        
                        <div class="w-8 h-8 rounded-xl bg-amber-50 border border-amber-200 text-amber-500 flex items-center justify-center shadow-2xs">
                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.690h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.690l1.519-4.674z"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Card Body: Title & Date -->
                    <div class="space-y-1.5 min-h-[4.5rem]">
                        <Link :href="`/docs/${doc.slug}`" class="text-base font-bold text-gray-900 group-hover:text-gray-600 transition line-clamp-2">
                            {{ doc.title }}
                        </Link>
                        <p class="text-xs text-gray-400">
                            Updated {{ new Date(doc.updated_at).toLocaleDateString() }}
                        </p>
                    </div>

                    <!-- Card Footer: Quick Action -->
                    <div class="pt-4 border-t border-gray-100 flex items-center justify-between">
                        <span class="text-[11px] font-medium text-gray-400">Quick Reference</span>
                        <Link :href="`/docs/${doc.slug}`" class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-xl bg-gray-900 hover:bg-gray-800 text-white text-xs font-semibold transition shadow-sm">
                            Read Guide &rarr;
                        </Link>
                    </div>

                </div>
            </div>

        </div>
    </AppLayout>
</template>