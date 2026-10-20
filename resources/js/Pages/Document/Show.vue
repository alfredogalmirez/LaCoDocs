<script setup>
import { Link, router } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    document: Object
});

const deleteDocument = () => {
    if (confirm(`Are you sure you want to delete "${props.document.title}"? This action cannot be undone.`)) {
        router.delete(route('docs.destroy', props.document.slug), {
            preserveScroll: true,
        });
    }
}
</script>

<template>
    <AppLayout>
        <div class="p-8 max-w-4xl mx-auto bg-white min-h-screen border-x border-gray-200 shadow-sm">
            <!-- Back Button -->
            <div class="flex justify-between items-center mb-8 border-b pb-4">
                <Link href="/docs" class="text-blue-600 hover:text-blue-800 font-medium flex items-center gap-2">
                    &larr; Back to Documentation
                </Link>

                <div class="flex items-center gap-3">
                    <Link :href="route('docs.edit', document.slug)"
                        class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-3 py-1.5 rounded-lg text-sm font-medium transition">
                        Edit
                    </Link>

                    <button @click="deleteDocument" type="button"
                        class="bg-red-50 hover:bg-red-100 text-red-600 flex items-center gap-1 px-3 py-1.5 rounded-lg text-sm font-medium transition">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Delete
                    </button>
                </div>
            </div>

            <!-- Document Header -->
            <h1 class="text-4xl font-bold text-gray-900 mb-2">{{ document.title }}</h1>
            <p class="text-sm text-gray-500 mb-8 border-b pb-4">
                Topic: {{ document.topic.name }}
            </p>

            <!-- Document Content (Currently raw JSON) -->
            <div class="space-y-6">

                <div v-for="(block, index) in document.content_blocks.blocks" :key="index">

                    <!-- TEXT -->
                    <p v-if="block.type === 'text'" class="text-gray-700 whitespace-pre-wrap">
                        {{ block.content }}
                    </p>

                    <!-- CODE -->
                    <div v-else-if="block.type === 'code'">
                        <div class="text-sm text-gray-500 mb-2">
                            {{ block.language }}
                        </div>

                        <pre
                            class="bg-gray-900 text-white p-5 rounded-lg overflow-x-auto"><code>{{ block.content }}</code></pre>
                    </div>

                    <!-- SQL -->
                    <div v-else-if="block.type === 'sql'">
                        <div class="text-sm text-gray-500 mb-2">
                            SQL
                        </div>

                        <pre
                            class="bg-gray-50 border border-gray-200 p-5 rounded-lg overflow-x-auto"><code>{{ block.content }}</code></pre>
                    </div>

                    <!-- STEPS -->
                    <div v-else-if="block.type === 'steps'">
                        <h2 class="text-lg font-semibold text-gray-900 mb-3">
                            Steps
                        </h2>

                        <ol class="list-decimal list-inside space-y-2 text-gray-700">
                            <li v-for="(step, stepIndex) in block.content.split('\n').filter(line => line.trim())"
                                :key="stepIndex">
                                {{ step }}
                            </li>
                        </ol>
                    </div>

                </div>

            </div>
        </div>
    </AppLayout>
</template>