<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const props = defineProps({
    topics: {
        type: Array,
        default: () => [],
    },
});

const form = useForm({
    name: '',
    parent_id: '',
});

const successMessage = ref('');

function submit() {
    successMessage.value = '';

    form.post('/topics', {
        onSuccess: () => {
            form.reset();
            successMessage.value = 'Topic created successfully!'
        },
    });
}
</script>

<template>

    <Head :title="Create Topic" />
    <AppLayout>
        <div class="max-w-5xl mx-auto p-6">
            <!-- Page heading -->
            <div class="mb-8">
                <Link href="/docs" class="text-sm text-blue-600 hover:text-blue-800">
                    &larr; Back to Documentation
                </Link>

                <h1 class="text-3xl font-bold text-gray-900 mt-4">
                    Topic Management
                </h1>

                <p class="text-gray-500 mt-2">
                    Organize your documentation into topics and subtopics.
                </p>
            </div>

            <!-- Create topic form -->
            <div class="bg-white border border-gray-200 rounded-xl p-6 mb-8">
                <h2 class="text-xl font-semibold text-gray-900 mb-5">
                    Create New Topic
                </h2>

                <form @submit.prevent="submit">
                    <!-- Topic name -->
                    <div class="mb-5">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Topic Name
                        </label>

                        <input id="name" v-model="form.name" type="text" placeholder="e.g. Frontend Development"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" />

                        <p v-if="form.errors.name" class="text-sm text-red-600 mt-2">
                            {{ form.errors.name }}
                        </p>
                    </div>

                    <!-- Optional parent topic -->
                    <div class="mb-5">
                        <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Parent Topic
                        </label>

                        <select id="parent_id" v-model="form.parent_id"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">None — Create a main topic</option>

                            <option v-for="topic in props.topics" :key="topic.id" :value="topic.id">
                                {{ topic.name }}
                            </option>
                        </select>

                        <p class="text-xs text-gray-500 mt-2">
                            Select a parent to create a subtopic.
                        </p>

                        <p v-if="form.errors.parent_id" class="text-sm text-red-600 mt-2">
                            {{ form.errors.parent_id }}
                        </p>
                    </div>

                    <!-- Success message -->
                    <p v-if="successMessage" class="text-sm text-green-600 mb-4">
                        {{ successMessage }}
                    </p>

                    <!-- Submit button -->
                    <button type="submit" :disabled="form.processing"
                        class="px-5 py-2.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50">
                        {{ form.processing ? 'Saving...' : 'Create Topic' }}
                    </button>
                </form>
            </div>

            <!-- Existing topics -->
            <div class="bg-white border border-gray-200 rounded-xl p-6">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="text-xl font-semibold text-gray-900">
                        Existing Topics
                    </h2>

                    <span class="text-sm text-gray-500">
                        {{ props.topics.length }} topics
                    </span>
                </div>

                <div v-if="props.topics.length === 0" class="text-gray-500 text-sm py-6 text-center">
                    No topics yet. Create your first topic above.
                </div>

                <div v-else class="divide-y divide-gray-100">
                    <div v-for="topic in props.topics" :key="topic.id" class="py-4 flex items-start gap-3">
                        <div class="mt-1 text-gray-400">
                            <span v-if="topic.parent_id">↳</span>
                            <span v-else>📁</span>
                        </div>

                        <div class="flex-1 min-w-0">
                            <p class="font-medium text-gray-900">
                                {{ topic.name }}
                            </p>

                            <p class="text-sm text-gray-500 mt-1">
                                <span v-if="topic.parent">
                                    Subtopic of {{ topic.parent.name }}
                                </span>
                                <span v-else>
                                    Main topic
                                </span>
                            </p>
                        </div>

                        <Link :href="`/topics/${topic.slug}`" class="text-sm text-blue-600 hover:text-blue-800">
                            View
                        </Link>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
