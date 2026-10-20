<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, useForm, router, Head } from '@inertiajs/vue3';
import { computed, ref } from 'vue';

const props = defineProps({
    topics: {
        type: Array,
        default: () => [],
    },
});

const hierarchicalTopics = computed(() => {
    const mainTopics = props.topics.filter(t => !t.parent_id);
    const sortedList = [];

    mainTopics.forEach(main => {
        sortedList.push(main);

        const children = props.topics.filter(t => t.parent_id === main.id);
        sortedList.push(...children);
    });

    const processedIds = new Set(sortedList.map(t => t.id));
    const remaining = props.topics.filter(t => !processedIds.has(t.id));
    sortedList.push(...remaining);

    return sortedList;

});

const form = useForm({
    name: '',
    parent_id: '',
});

const successMessage = ref('');

function submit() {
    successMessage.value = '';

    form.post('/topics', {
        preserveScroll: true,
        onSuccess: () => {
            form.reset();
            successMessage.value = 'Topic created successfully!';
        },
    });
}

const deleteTopic = (topic) => {
    if (confirm(`Are you sure you want to delete "${topic.name}"? This will remove all associated documents and subtopics.`)) {
        router.delete(`/topics/${topic.slug}`, {
            preserveScroll: true,
        });
    }
};
</script>

<template>

    <Head title="Create Topic" />
    <AppLayout>
        <div class="max-w-5xl mx-auto p-6 space-y-8">

            <!-- Page Header & Back Link -->
            <div>
                <Link href="/docs" class="text-sm text-blue-600 hover:text-blue-800">
                    &larr; Back to Documentation
                </Link>
                <h1 class="text-3xl font-bold text-gray-900 mt-2">
                    Topic Management
                </h1>
                <p class="text-gray-500 text-sm mt-1">
                    Organize your documentation library into main topics and subtopics.
                </p>
            </div>

            <!-- 1. MAIN FOCUS: Existing Topics (Moved to the Top!) -->
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                <div class="flex items-center justify-between mb-5">
                    <h2 class="text-xl font-semibold text-gray-900">
                        Existing Topics
                    </h2>
                    <span class="text-xs bg-gray-100 text-gray-600 px-2.5 py-1 rounded-full font-medium">
                        {{ props.topics.length }} total
                    </span>
                </div>

                <div v-if="props.topics.length === 0"
                    class="text-gray-400 text-sm py-8 text-center border border-dashed rounded-lg">
                    No topics created yet. Use the form below to add your first one!
                </div>

                <div v-else class="divide-y divide-gray-100">
                    <div v-for="topic in hierarchicalTopics" :key="topic.id"
                        class="py-4 flex items-center justify-between gap-4 transition"
                        :class="{ 'pl-8 bg-gray-50/50 rounded-lg my-1 border border-gray-100': topic.parent_id }">

                        <!-- Topic Info with visual nesting offset -->
                        <div class="flex items-start gap-3 min-w-0">
                            <div class="mt-1 text-gray-400">
                                <span v-if="topic.parent_id" class="text-indigo-500 font-bold">↳</span>
                                <svg v-else class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="font-medium text-gray-900 truncate">
                                    {{ topic.name }}
                                </p>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    <span v-if="topic.parent" class="text-indigo-600 font-medium">Subtopic of {{
                                        topic.parent.name }}</span>
                                    <span v-else>Main topic</span>
                                </p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center gap-4 text-sm">
                            <Link :href="`/topics/${topic.slug}`" class="text-blue-600 hover:underline font-medium">
                                View
                            </Link>

                            <Link :href="`/topics/${topic.slug}/edit`"
                                class="text-gray-600 hover:text-gray-900 font-medium">
                                Edit
                            </Link>

                            <button @click="deleteTopic(topic)" type="button"
                                class="text-red-600 hover:text-red-800 font-medium">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. SECONDARY FOCUS: Create Topic Form (Moved to the Bottom) -->
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm">
                <h2 class="text-xl font-semibold text-gray-900 mb-5">
                    + Create New Topic
                </h2>

                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                            Topic Name
                        </label>
                        <input id="name" v-model="form.name" type="text" placeholder="e.g. Frontend Development"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500" />
                        <p v-if="form.errors.name" class="text-sm text-red-600 mt-1">
                            {{ form.errors.name }}
                        </p>
                    </div>

                    <div>
                        <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-1">
                            Parent Topic (Optional)
                        </label>
                        <select id="parent_id" v-model="form.parent_id"
                            class="w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500">
                            <option value="">None — Create a main topic</option>
                            <option v-for="topic in props.topics" :key="topic.id" :value="topic.id">
                                {{ topic.name }}
                            </option>
                        </select>
                        <p v-if="form.errors.parent_id" class="text-sm text-red-600 mt-1">
                            {{ form.errors.parent_id }}
                        </p>
                    </div>

                    <p v-if="successMessage" class="text-sm text-green-600">
                        {{ successMessage }}
                    </p>

                    <button type="submit" :disabled="form.processing"
                        class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50 text-sm font-medium">
                        {{ form.processing ? 'Saving...' : 'Create Topic' }}
                    </button>
                </form>
            </div>

        </div>
    </AppLayout>
</template>