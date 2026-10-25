<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, computed } from 'vue';

const props = defineProps({
    topics: Array
});

const selectedTopicId = ref('all');

// Filter topics based on top pill selection
const filteredTopics = computed(() => {
    if (selectedTopicId.value === 'all') return props.topics;
    return props.topics.filter(t => t.id === selectedTopicId.value);
});

const deleteTopic = (topic) => {
    if (confirm(`Are you sure you want to delete the topic "${topic.name}"? This will remove all associated documents.`)) {
        router.delete(route('topics.destroy', topic.slug), {
            preserveScroll: true,
        });
    }
}

// Helper function to convert timestamp into "X time ago"
const timeAgo = (dateString) => {
    if (!dateString) return 'Recently';
    const seconds = Math.floor((new Date() - new Date(dateString)) / 1000);

    let interval = seconds / 31536000;
    if (interval > 1) return Math.floor(interval) + (Math.floor(interval) === 1 ? ' year ago' : ' years ago');

    interval = seconds / 2592000;
    if (interval > 1) return Math.floor(interval) + (Math.floor(interval) === 1 ? ' month ago' : ' months ago');

    interval = seconds / 86400;
    if (interval > 1) return Math.floor(interval) + (Math.floor(interval) === 1 ? ' day ago' : ' days ago');

    interval = seconds / 3600;
    if (interval > 1) return Math.floor(interval) + (Math.floor(interval) === 1 ? ' hour ago' : ' hours ago');

    interval = seconds / 60;
    if (interval > 1) return Math.floor(interval) + (Math.floor(interval) === 1 ? ' minute ago' : ' minutes ago');

    return 'Just now';
};
</script>

<template>
    <Head title="Documents Workspace" />
    
    <AppLayout>
        <div class="p-8 max-w-[95rem] mx-auto space-y-6">
            
            <!-- Page Header -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 border-b border-gray-200 pb-6">
                <div>
                    <h1 class="text-3xl font-bold tracking-tight text-gray-900">Knowledge Workspace</h1>
                    <p class="mt-1 text-sm text-gray-500">Organize and track your technical guides across structured topic lanes.</p>
                </div>
            </div>

            <!-- Top Container: Sub-Topics / Topic Filter Pills Bar -->
            <div class="bg-white border border-gray-200 rounded-2xl p-4 shadow-sm flex flex-wrap items-center justify-between gap-4">
                <div class="flex items-center gap-2 overflow-x-auto pb-1 sm:pb-0">
                    <button @click="selectedTopicId = 'all'"
                        class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition shrink-0"
                        :class="selectedTopicId === 'all' ? 'bg-gray-900 text-white shadow-sm' : 'bg-gray-100 text-gray-600 hover:bg-gray-200'">
                        All Topics ({{ topics.reduce((acc, t) => acc + (t.documents?.length || 0), 0) }})
                    </button>
                    <button v-for="topic in topics" :key="topic.id"
                        @click="selectedTopicId = topic.id"
                        class="px-3.5 py-1.5 rounded-xl text-xs font-semibold transition shrink-0 border"
                        :class="selectedTopicId === topic.id ? 'bg-slate-900 text-white border-slate-900 shadow-sm' : 'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100'">
                        {{ topic.name }} <span class="ml-1 opacity-70">({{ topic.documents?.length || 0 }})</span>
                    </button>
                </div>

                <div class="text-xs font-medium text-gray-400">
                    Showing {{ filteredTopics.length }} topic lanes
                </div>
            </div>

            <!-- Empty State -->
            <div v-if="!topics || topics.length === 0" class="bg-white border border-dashed border-gray-300 rounded-2xl p-12 text-center space-y-4 shadow-sm">
                <div class="w-12 h-12 rounded-2xl bg-slate-50 text-slate-500 flex items-center justify-center mx-auto border border-slate-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                    </svg>
                </div>
                <div class="space-y-1">
                    <h2 class="text-base font-bold text-gray-900">No topics found</h2>
                    <p class="text-xs text-gray-500 max-w-sm mx-auto">Create your first topic to populate your documentation Kanban board.</p>
                </div>
            </div>

            <!-- Kanban / Column Lanes Grid Layout -->
            <div v-else class="flex gap-6 overflow-x-auto pb-6 items-start min-h-[650px]">
                
                <div v-for="topic in filteredTopics" :key="topic.id"
                    class="w-80 shrink-0 bg-gray-50/70 border border-gray-200 rounded-2xl p-4 flex flex-col justify-between shadow-sm">

                    <!-- Column Header -->
                    <div class="space-y-3">
                        <div class="flex items-center justify-between pb-3 border-b border-gray-200">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-800 flex items-center justify-center font-bold text-xs shrink-0 border border-slate-200">
                                    {{ topic.documents?.length || 0 }}
                                </div>
                                <h2 class="font-bold text-gray-900 text-sm truncate" :title="topic.name">{{ topic.name }}</h2>
                            </div>

                            <div class="flex items-center gap-1">
                                <Link :href="`/topics/${topic.slug}`"
                                    class="text-[11px] text-slate-600 hover:text-gray-900 font-semibold px-2 py-1 rounded hover:bg-gray-200/60 transition">
                                    View
                                </Link>
                                <button @click.stop.prevent="deleteTopic(topic)"
                                    class="text-gray-400 hover:text-red-600 p-1.5 rounded transition"
                                    title="Delete Topic">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Documents Tailing (Vertical Cards Stack inside Column) -->
                        <div class="space-y-3 min-h-[12rem]">
                            <div v-if="topic.documents && topic.documents.length > 0">
                                <div v-for="doc in topic.documents" :key="doc.id"
                                    class="bg-white border border-gray-200 hover:border-gray-300 rounded-xl p-3.5 shadow-2xs hover:shadow-md transition space-y-2 group mb-3">
                                    
                                    <div class="flex items-start justify-between gap-2">
                                        <Link :href="`/docs/${doc.slug}`"
                                            class="text-xs font-bold text-gray-900 group-hover:text-slate-800 transition line-clamp-2 leading-snug">
                                            {{ doc.title }}
                                        </Link>
                                        
                                        <span v-if="doc.is_starred" class="shrink-0 text-amber-500" title="Starred Guide">
                                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24">
                                                <path d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.690h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.690l1.519-4.674z"/>
                                            </svg>
                                        </span>
                                    </div>

                                    <div class="flex items-center justify-between text-[11px] text-gray-400 pt-1 border-t border-gray-100">
                                        <span>{{ timeAgo(doc.updated_at) }}</span>
                                        <Link :href="`/docs/${doc.slug}`" class="font-semibold text-slate-700 hover:underline">
                                            Read &rarr;
                                        </Link>
                                    </div>

                                </div>
                            </div>

                            <!-- Empty Lane Placeholder -->
                            <div v-else class="border border-dashed border-gray-200 rounded-xl p-6 text-center bg-white/40">
                                <p class="text-xs text-gray-400 italic">No documents in this topic lane yet.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Column Footer: + Add Doc Button Pinned at Bottom -->
                    <div class="pt-4 mt-4 border-t border-gray-200">
                        <Link :href="`/docs/create?topic_id=${topic.id}`"
                            class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2 rounded-xl bg-white hover:bg-gray-900 hover:text-white text-gray-700 border border-gray-200 text-xs font-semibold transition shadow-2xs">
                            <span class="text-sm font-bold leading-none">+</span> Add Document
                        </Link>
                    </div>

                </div>

            </div>
        </div>
    </AppLayout>
</template>