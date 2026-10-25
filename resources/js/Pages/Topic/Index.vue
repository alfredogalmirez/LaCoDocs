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
    <Head title="Topic Management" />
    
    <AppLayout>
        <div class="max-w-4xl mx-auto p-8 space-y-8">

            <!-- Page Header & Back Link -->
            <div>
                <Link href="/docs" class="text-xs font-semibold text-gray-500 hover:text-gray-900 transition flex items-center gap-1.5 mb-2">
                    &larr; Back to Documentation
                </Link>
                <h1 class="text-3xl font-bold tracking-tight text-gray-900">
                    Topic Management
                </h1>
                <p class="text-sm text-gray-500 mt-1">
                    Organize your documentation library into main topics and subtopics.
                </p>
            </div>

            <!-- 1. Existing Topics Section -->
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-gray-100 pb-4">
                    <h2 class="text-lg font-bold text-gray-900">
                        Existing Topics
                    </h2>
                    <span class="text-xs bg-gray-100 text-gray-600 px-2.5 py-1 rounded-md font-semibold">
                        {{ props.topics.length }} total
                    </span>
                </div>

                <div v-if="props.topics.length === 0"
                    class="text-gray-400 text-xs py-8 text-center border border-dashed border-gray-200 rounded-lg">
                    No topics created yet. Use the form below to add your first one.
                </div>

                <div v-else class="divide-y divide-gray-100">
                    <div v-for="topic in hierarchicalTopics" :key="topic.id"
                        class="py-3.5 flex items-center justify-between gap-4 transition"
                        :class="{ 'pl-6 bg-gray-50/60 rounded-lg my-1 border border-gray-100': topic.parent_id }">

                        <!-- Topic Info with visual nesting offset -->
                        <div class="flex items-start gap-3 min-w-0">
                            <div class="mt-0.5 text-gray-400">
                                <span v-if="topic.parent_id" class="text-gray-900 font-bold">↳</span>
                                <svg v-else class="w-5 h-5 text-gray-700" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                                </svg>
                            </div>
                            <div class="min-w-0">
                                <p class="font-bold text-gray-900 text-sm truncate">
                                    {{ topic.name }}
                                </p>
                                <p class="text-xs text-gray-400 mt-0.5">
                                    <span v-if="topic.parent" class="text-gray-700 font-medium">Subtopic of {{ topic.parent.name }}</span>
                                    <span v-else>Main topic</span>
                                </p>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex items-center gap-3 text-xs shrink-0">
                            <Link :href="`/topics/${topic.slug}`" class="text-gray-700 hover:text-gray-900 font-semibold hover:underline">
                                View
                            </Link>

                            <Link :href="`/topics/${topic.slug}/edit`"
                                class="text-gray-500 hover:text-gray-900 font-semibold">
                                Edit
                            </Link>

                            <button @click="deleteTopic(topic)" type="button"
                                class="text-red-500 hover:text-red-700 font-semibold">
                                Delete
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 2. Create Topic Form -->
            <div class="bg-white border border-gray-200 rounded-xl p-6 shadow-sm space-y-4">
                <h2 class="text-lg font-bold text-gray-900 border-b border-gray-100 pb-4">
                    Create New Topic
                </h2>

                <form @submit.prevent="submit" class="space-y-4">
                    <div>
                        <label for="name" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                            Topic Name
                        </label>
                        <input id="name" v-model="form.name" type="text" placeholder="e.g. Frontend Architecture"
                            class="w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900 text-sm" />
                        <p v-if="form.errors.name" class="text-xs text-red-600 mt-1">
                            {{ form.errors.name }}
                        </p>
                    </div>

                    <div>
                        <label for="parent_id" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                            Parent Topic (Optional)
                        </label>
                        <select id="parent_id" v-model="form.parent_id"
                            class="w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900 text-sm">
                            <option value="">None (Main Topic)</option>
                            <option v-for="topic in props.topics" :key="topic.id" :value="topic.id">
                                {{ topic.name }}
                            </option>
                        </select>
                        <p v-if="form.errors.parent_id" class="text-xs text-red-600 mt-1">
                            {{ form.errors.parent_id }}
                        </p>
                    </div>

                    <div v-if="successMessage" class="p-3 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-medium">
                        {{ successMessage }}
                    </div>

                    <div class="flex justify-end pt-2">
                        <button type="submit" :disabled="form.processing"
                            class="px-5 py-2.5 bg-gray-900 hover:bg-gray-800 text-white rounded-lg transition disabled:opacity-50 text-xs font-semibold uppercase tracking-wider shadow-sm">
                            {{ form.processing ? 'Saving...' : 'Create Topic' }}
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </AppLayout>
</template>