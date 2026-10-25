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
    <Head :title="`Edit Topic: ${topic.name}`" />

    <AppLayout>
        <div class="max-w-2xl mx-auto p-8 space-y-6">
            <!-- Back Link -->
            <div>
                <Link href="/topics" class="text-xs font-semibold text-gray-500 hover:text-gray-900 transition flex items-center gap-1.5">
                    &larr; Back to Topics
                </Link>
            </div>

            <!-- Edit Form Card -->
            <div class="bg-white border border-gray-200 rounded-xl p-8 shadow-sm space-y-6">
                <div class="border-b border-gray-100 pb-4">
                    <h1 class="text-2xl font-bold tracking-tight text-gray-900">
                        Edit Topic: {{ topic.name }}
                    </h1>
                    <p class="text-sm text-gray-500 mt-1">Modify the topic title or change its hierarchical parent folder.</p>
                </div>

                <form @submit.prevent="submit" class="space-y-5">
                    <!-- Topic Name -->
                    <div>
                        <label for="name" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                            Topic Name
                        </label>
                        <input 
                            id="name" 
                            v-model="form.name" 
                            type="text"
                            class="w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900 text-sm" 
                        />
                        <p v-if="form.errors.name" class="text-xs text-red-600 mt-1">
                            {{ form.errors.name }}
                        </p>
                    </div>

                    <!-- Parent Topic Selection -->
                    <div>
                        <label for="parent_id" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">
                            Parent Topic (Optional)
                        </label>
                        <select 
                            id="parent_id" 
                            v-model="form.parent_id"
                            class="w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900 text-sm"
                        >
                            <option value="">None (Main Topic)</option>
                            <option v-for="parent in parentTopics" :key="parent.id" :value="parent.id">
                                {{ parent.name }}
                            </option>
                        </select>
                        <p v-if="form.errors.parent_id" class="text-xs text-red-600 mt-1">
                            {{ form.errors.parent_id }}
                        </p>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center justify-end gap-3 pt-4 border-t border-gray-100">
                        <Link 
                            href="/topics" 
                            class="px-4 py-2.5 border border-gray-200 text-gray-700 rounded-lg text-xs font-semibold uppercase tracking-wider hover:bg-gray-50 transition"
                        >
                            Cancel
                        </Link>

                        <button 
                            type="submit" 
                            :disabled="form.processing"
                            class="px-5 py-2.5 bg-gray-900 text-white rounded-lg text-xs font-semibold uppercase tracking-wider hover:bg-gray-800 transition disabled:opacity-50 shadow-sm"
                        >
                            {{ form.processing ? 'Saving Changes...' : 'Update Topic' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </AppLayout>
</template>