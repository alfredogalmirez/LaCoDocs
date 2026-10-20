
<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    document: Object,
    topics: Array,
});

// Initialize the form using the existing document data.
const form = useForm({
    topic_id: props.document.topic_id,
    title: props.document.title,
    blocks: props.document.content_blocks?.blocks ?? [
        {
            type: 'text',
            content: '',
            language: '',
        },
    ],
});

function addBlock(type) {
    form.blocks.push({
        type: type,
        content: '',
        language: type === 'code' ? 'php' : '',
    });
}

function removeBlock(index) {
    form.blocks.splice(index, 1);
}

function submit() {
    form.transform((data) => ({
        topic_id: data.topic_id,
        title: data.title,
        content_blocks: {
            blocks: data.blocks,
        },
    })).put(`/docs/${props.document.slug}`);
}
</script>

<template>
    <AppLayout>
        <div class="max-w-4xl mx-auto">
            <div class="mb-8">
                <Link
                    :href="`/docs/${document.slug}`"
                    class="text-blue-600 hover:text-blue-800"
                >
                    &larr; Back to Document
                </Link>

                <h1 class="text-3xl font-bold text-gray-900 mt-4">
                    Edit Document
                </h1>
                <p class="text-gray-500 mt-2">
                    Update your document's title, topic, and content.
                </p>
            </div>

            <form @submit.prevent="submit">
                <!-- Topic -->
                <div class="mb-6">
                    <label class="block font-medium text-gray-700 mb-2">
                        Topic
                    </label>

                    <select
                        v-model="form.topic_id"
                        class="w-full border-gray-300 rounded-lg"
                    >
                        <option value="" disabled>Select a topic</option>

                        <option
                            v-for="topic in topics"
                            :key="topic.id"
                            :value="topic.id"
                        >
                            {{ topic.name }}
                        </option>
                    </select>

                    <p
                        v-if="form.errors.topic_id"
                        class="text-red-600 text-sm mt-1"
                    >
                        {{ form.errors.topic_id }}
                    </p>
                </div>

                <!-- Title -->
                <div class="mb-6">
                    <label class="block font-medium text-gray-700 mb-2">
                        Title
                    </label>

                    <input
                        v-model="form.title"
                        type="text"
                        class="w-full border-gray-300 rounded-lg"
                        placeholder="e.g. How to create a Controller"
                    />

                    <p
                        v-if="form.errors.title"
                        class="text-red-600 text-sm mt-1"
                    >
                        {{ form.errors.title }}
                    </p>
                </div>

                <!-- Content blocks -->
                <div class="mb-6">
                    <h2 class="text-lg font-semibold text-gray-900">
                        Document Content
                    </h2>
                    <p class="text-sm text-gray-500">
                        Edit explanations, code snippets, SQL queries, and steps.
                    </p>
                </div>

                <div class="space-y-6">
                    <div
                        v-for="(block, index) in form.blocks"
                        :key="index"
                        class="border border-gray-200 rounded-lg p-5"
                    >
                        <div class="flex items-center justify-between mb-4">
                            <label class="font-medium text-gray-700">
                                Content Block {{ index + 1 }}
                            </label>

                            <button
                                type="button"
                                @click="removeBlock(index)"
                                class="text-red-600 hover:text-red-800 text-sm"
                            >
                                Remove
                            </button>
                        </div>

                        <!-- Content type -->
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Content Type
                            </label>

                            <select
                                v-model="block.type"
                                class="w-full border-gray-300 rounded-lg"
                            >
                                <option value="text">Text / Explanation</option>
                                <option value="code">Code</option>
                                <option value="sql">SQL Query</option>
                                <option value="steps">Steps</option>
                            </select>
                        </div>

                        <!-- Programming language -->
                        <div v-if="block.type === 'code'" class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Programming Language
                            </label>

                            <select
                                v-model="block.language"
                                class="w-full border-gray-300 rounded-lg"
                            >
                                <option value="php">PHP</option>
                                <option value="javascript">JavaScript</option>
                                <option value="html">HTML</option>
                                <option value="css">CSS</option>
                                <option value="vue">Vue</option>
                                <option value="bash">Bash / Terminal</option>
                                <option value="json">JSON</option>
                            </select>
                        </div>

                        <!-- Content -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                {{
                                    block.type === 'sql' ? 'SQL Query' :
                                    block.type === 'code' ? 'Code' :
                                    block.type === 'steps' ? 'Steps (one per line)' :
                                    'Explanation'
                                }}
                            </label>

                            <textarea
                                v-model="block.content"
                                rows="6"
                                class="w-full border-gray-300 rounded-lg font-mono"
                                :placeholder="
                                    block.type === 'sql' ? 'SELECT * FROM users;' :
                                    block.type === 'code' ? 'Write your code...' :
                                    block.type === 'steps' ? 'Step 1: ...\nStep 2: ...' :
                                    'Write your explanation...'
                                "
                            ></textarea>
                        </div>
                    </div>
                </div>

                <!-- Add blocks -->
                <div class="flex flex-wrap gap-3 mt-6 mb-8">
                    <button
                        type="button"
                        @click="addBlock('text')"
                        class="px-4 py-2 border rounded-lg hover:bg-gray-50"
                    >
                        + Add Text
                    </button>

                    <button
                        type="button"
                        @click="addBlock('code')"
                        class="px-4 py-2 border rounded-lg hover:bg-gray-50"
                    >
                        + Add Code
                    </button>

                    <button
                        type="button"
                        @click="addBlock('sql')"
                        class="px-4 py-2 border rounded-lg hover:bg-gray-50"
                    >
                        + Add SQL
                    </button>

                    <button
                        type="button"
                        @click="addBlock('steps')"
                        class="px-4 py-2 border rounded-lg hover:bg-gray-50"
                    >
                        + Add Steps
                    </button>
                </div>

                <!-- Save changes -->
                <div>
                    <button
                        type="submit"
                        :disabled="form.processing"
                        class="px-5 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 disabled:opacity-50"
                    >
                        {{ form.processing ? 'Saving...' : 'Save Changes' }}
                    </button>

                    <p
                        v-if="form.errors['content_blocks.blocks']"
                        class="text-red-600 text-sm mt-2"
                    >
                        {{ form.errors['content_blocks.blocks'] }}
                    </p>
                </div>
            </form>
        </div>
    </AppLayout>
</template>
