<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    topic: Object,
    parentTopics: Array
});

const form = useForm({
    name: props.topic.name,
    parent_id: props.topic.parent_id || '',
});

const submit = () => {
    form.put(`/topics/${props.topic.slug}`);
};
</script>

<template>
    <Head title="Edit Topic" />

    <AppLayout>
        <div class="max-w-2xl mx-auto p-6">
            <!-- Back Link -->
            <div class="mb-6">
                <Link href="/topics" class="text-sm text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">
                    &larr; Back to Topics
                </Link>
            </div>

            <!-- Edit Form Card -->
            <div class="bg-white border border-gray-200 rounded-xl p-8 shadow-sm">
                <h1 class="text-2xl font-bold text-gray-900 mb-6">
                    Edit Topic: {{ topic.name }}
                </h1>

                <form @submit.prevent="submit" class="space-y-6">
                    <!-- Topic Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Topic Name
                        </label>
                        <input 
                            id="name" 
                            v-model="form.name" 
                            type="text"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm" 
                        />
                        <p v-if="form.errors.name" class="text-sm text-red-600 mt-1">
                            {{ form.errors.name }}
                        </p>
                    </div>

                    <!-- Parent Topic Selection -->
                    <div>
                        <label for="parent_id" class="block text-sm font-medium text-gray-700 mb-2">
                            Parent Topic (Optional)
                        </label>
                        <select 
                            id="parent_id" 
                            v-model="form.parent_id"
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm"
                        >
                            <option value="">None — Make this a main topic</option>
                            <option v-for="parent in parentTopics" :key="parent.id" :value="parent.id">
                                {{ parent.name }}
                            </option>
                        </select>
                        <p v-if="form.errors.parent_id" class="text-sm text-red-600 mt-1">
                            {{ form.errors.parent_id }}
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                        <Link 
                            href="/topics" 
                            class="px-4 py-2 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition"
                        >
                            Cancel
                        </Link>

                        <button 
                            type="submit" 
                            :disabled="form.processing"
                            class="px-5 py-2 bg-indigo-600 text-white rounded-lg text-sm font-medium hover:bg-indigo-700 transition disabled:opacity-50"
                        >
                            {{ form.processing ? 'Saving Changes...' : 'Update Topic' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>