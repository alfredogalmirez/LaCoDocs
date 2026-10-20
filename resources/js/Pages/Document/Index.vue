<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, router } from '@inertiajs/vue3';

defineProps({
    topics: Array
});

const deleteTopic = (topic) => {
    if (confirm(`Are you sure you want to delete the topic "${topic.name}"? This will remove all the associates.`)) {
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
    <AppLayout>
        <div>
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900">Documents</h1>
                <p class="mt-2 text-gray-500">Your personal developer knowledge base.</p>
            </div>

            <!-- Topics Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div v-for="topic in topics" :key="topic.id"
                    class="bg-white border border-gray-200 rounded-xl p-5 flex flex-col justify-between shadow-sm">

                    <div>
                        <!-- Topic Header -->
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center gap-3">
                                <div
                                    class="w-10 h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                    </svg>
                                </div>
                                <div>
                                    <h2 class="font-semibold text-gray-900">{{ topic.name }}</h2>
                                    <p class="text-xs text-gray-400">{{ topic.documents?.length ?? 0 }} documents</p>
                                </div>
                            </div>

                            <div class="flex items-center gap-2">

                                <Link :href="`/topics/${topic.slug}`"
                                    class="text-xs text-indigo-600 hover:underline font-medium">
                                    View All →
                                </Link>
                            </div>
                        </div>

                        <!-- List of Documents inside this Topic -->
                        <ul class="space-y-2 mb-4" v-if="topic.documents && topic.documents.length > 0">
                            <li v-for="doc in topic.documents.slice(0, 3)" :key="doc.id">
                                <Link :href="`/docs/${doc.slug}`"
                                    class="text-sm text-gray-600 hover:text-indigo-600 truncate block">
                                    • {{ doc.title }}
                                </Link>
                            </li>
                        </ul>
                        <p v-else class="text-xs text-gray-400 italic mb-4">No documents in this topic yet.</p>
                    </div>

                    <!-- Card Footer Actions -->
                    <div class="pt-3 border-t border-gray-100 flex justify-between items-center text-xs">
                        <span>Updated {{ timeAgo(topic.updated_at) }}</span>
                        <Link :href="`/docs/create?topic_id=${topic.id}`"
                            class="hover:text-indigo-600 font-medium hover:underline">
                            + Add Doc
                        </Link>

                        <button @click.stop.prevent="deleteTopic(topic)"
                            class="text-gray-400 hover:text-red-600 p-1 rounded transition flex items-center gap-1"
                            title="Delete Topic">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Delete
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </AppLayout>
</template>