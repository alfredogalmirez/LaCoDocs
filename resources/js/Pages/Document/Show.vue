<script setup>
import { Head, Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref } from 'vue';
import ConfirmModal from '@/Components/ConfirmModal.vue';

const props = defineProps({
    document: Object
});

// Dynamically calculate the sequential step number for individual 'step' blocks
const getStepNumber = (currentIndex) => {
    let count = 0;
    const blocks = props.document.content_blocks?.blocks || [];
    for (let i = 0; i <= currentIndex; i++) {
        if (blocks[i].type === 'step') {
            count++;
        }
    }
    return count;
};

const showDeleteModal = ref(false);

const deleteDocument = () => {
        router.delete(route('docs.destroy', props.document.slug), {
            onSuccess: () => {
                showDeleteModal = false;   
            }
        });
}

const goBack = () => {
    window.history.back();
}

const toggleStar = () => {
    router.put(route('docs.star', props.document.slug), {}, {
        preserveScroll: true,
    });
};
</script>

<template>

    <Head :title="document.title" />
    <AppLayout>
        <div class="p-8 max-w-4xl mx-auto bg-white min-h-screen border-x border-gray-200 shadow-sm space-y-6">

            <!-- Back Button & Actions -->
            <div class="flex justify-between items-center border-b border-gray-200 pb-4">
                <button @click="goBack"
                    class="text-xs font-semibold text-gray-500 hover:text-gray-900 transition flex items-center gap-1.5">
                    &larr; Back
                </button>

                <div class="flex items-center gap-2">
                    <button @click="toggleStar" type="button"
                        class="border border-gray-200 hover:bg-gray-100 text-gray-700 flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold transition">
                        <svg class="w-4 h-4"
                            :class="document.is_starred ? 'text-gray-950 fill-current' : 'text-gray-400'" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.690h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.690l1.519-4.674z" />
                        </svg>
                        {{ document.is_starred ? 'Starred' : 'Star Guide' }}
                    </button>

                    <Link :href="route('docs.edit', document.slug)"
                        class="px-3 py-1.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-700 hover:bg-gray-100 transition">
                        Edit
                    </Link>

                    <button @click="showDeleteModal = true" type="button"
                        class="px-3 py-1.5 rounded-lg border border-red-200 bg-red-50 text-red-600 hover:bg-red-100 text-xs font-semibold transition flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Delete
                    </button>
                    
                    <ConfirmModal 
                        :show="showDeleteModal"
                        title="Delete Document"
                        message="Are you sure you want to delete this document? All associated content will be permanently removed."
                        comfirm-text="Yes, Delete"
                        confirm-button-class="bg-red-600 hover:bg-red-700 text-white focus-ring-red-500"
                        @cancel="showDeleteModal = false"
                        @confirm="deleteDocument"
                    />
                </div>
            </div>

            <!-- Document Header -->
            <div>
                <h1 class="text-3xl font-bold tracking-tight text-gray-900 mb-1.5">{{ document.title }}</h1>
                <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider">
                    Topic: <span class="text-gray-700">{{ document.topic.name }}</span>
                </p>
            </div>

            <!-- Document Content Blocks -->
            <div class="space-y-6 pt-2">

                <div v-for="(block, index) in document.content_blocks.blocks" :key="index">

                    <!-- TEXT -->
                    <p v-if="block.type === 'text'" class="text-gray-700 whitespace-pre-wrap leading-relaxed text-sm">
                        {{ block.content }}
                    </p>

                    <!-- CODE -->
                    <div v-else-if="block.type === 'code'" class="space-y-1.5">
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                            Code ({{ block.language || 'snippet' }})
                        </div>
                        <pre
                            class="bg-gray-900 text-white p-4 rounded-xl overflow-x-auto text-xs font-mono shadow-sm"><code>{{ block.content }}</code></pre>
                    </div>

                    <!-- SQL -->
                    <div v-else-if="block.type === 'sql'" class="space-y-1.5">
                        <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wider">
                            SQL Query
                        </div>
                        <pre
                            class="bg-gray-50 border border-gray-200 text-gray-800 p-4 rounded-xl overflow-x-auto text-xs font-mono shadow-sm"><code>{{ block.content }}</code></pre>
                    </div>

                    <!-- STEP (Individual Interleaved Step) -->
                    <div v-else-if="block.type === 'step'"
                        class="flex items-start gap-3 bg-gray-50 border border-gray-200 p-4 rounded-xl shadow-sm">
                        <span
                            class="w-6 h-6 rounded-full bg-gray-900 text-white font-bold text-xs flex items-center justify-center shrink-0 mt-0.5 shadow-sm">
                            {{ getStepNumber(index) }}
                        </span>
                        <div class="text-gray-800 text-sm font-medium leading-relaxed self-center">
                            {{ block.content }}
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </AppLayout>
</template>