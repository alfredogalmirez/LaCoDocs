<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';

const props = defineProps({
    topics: Array,
    selectedTopicId: [String, Number],
});

const form = useForm({
    title: '',
    topic_id: props.selectedTopicId || '',
    content_blocks: {
        blocks: [
            { type: 'text', content: '', language: '' } // Start with at least one block
        ]
    }
});

// Add a new block of a specific type
const addBlock = (type) => {
    form.content_blocks.blocks.push({
        type: type,
        content: '',
        language: type === 'code' || type === 'sql' ? 'php' : ''
    });
};

// Remove a block by index
const removeBlock = (index) => {
    if (form.content_blocks.blocks.length > 1) {
        form.content_blocks.blocks.splice(index, 1);
    }
};

// Move a block up or down
const moveBlock = (index, direction) => {
    const newIndex = index + direction;
    if (newIndex >= 0 && newIndex < form.content_blocks.blocks.length) {
        const item = form.content_blocks.blocks.splice(index, 1)[0];
        form.content_blocks.blocks.splice(newIndex, 0, item);
    }
};

const submit = () => {
    form.post('/docs');
};
</script>

<template>
    <Head title="Create Document" />
    
    <AppLayout>
        <div class="max-w-4xl mx-auto p-6 space-y-6">
            <div class="flex items-center justify-between">
                <Link href="/docs" class="text-sm text-indigo-600 hover:underline">&larr; Back to Documents</Link>
                <h1 class="text-2xl font-bold text-gray-900">Create New Guide</h1>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <!-- Document Title & Topic Selection -->
                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Document Title</label>
                        <input type="text" v-model="form.title" placeholder="e.g. Setting up Deployment Workflow" 
                            class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm" />
                        <p v-if="form.errors.title" class="text-sm text-red-600 mt-1">{{ form.errors.title }}</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Select Topic / Folder</label>
                        <select v-model="form.topic_id" class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="">Choose a topic...</option>
                            <option v-for="topic in props.topics" :key="topic.id" :value="topic.id">
                                {{ topic.name }}
                            </option>
                        </select>
                        <p v-if="form.errors.topic_id" class="text-sm text-red-600 mt-1">{{ form.errors.topic_id }}</p>
                    </div>
                </div>

                <!-- Dynamic Content Blocks Builder -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-semibold text-gray-900">Content Blocks</h2>
                        <span class="text-xs text-gray-500">Mix explanations, code, and steps freely</span>
                    </div>

                    <!-- Loop through each block -->
                    <div v-for="(block, index) in form.content_blocks.blocks" :key="index" 
                        class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm relative space-y-3">
                        
                        <!-- Block Header Controls -->
                        <div class="flex items-center justify-between border-b pb-3">
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 text-xs font-bold rounded uppercase">
                                    {{ block.type }} block #{{ index + 1 }}
                                </span>
                            </div>

                            <div class="flex items-center gap-1">
                                <!-- Move Up -->
                                <button type="button" @click="moveBlock(index, -1)" :disabled="index === 0"
                                    class="p-1.5 text-gray-500 hover:bg-gray-100 rounded disabled:opacity-30" title="Move Up">
                                    ▲
                                </button>
                                <!-- Move Down -->
                                <button type="button" @click="moveBlock(index, 1)" :disabled="index === form.content_blocks.blocks.length - 1"
                                    class="p-1.5 text-gray-500 hover:bg-gray-100 rounded disabled:opacity-30" title="Move Down">
                                    ▼
                                </button>
                                <!-- Delete Block -->
                                <button type="button" @click="removeBlock(index)" :disabled="form.content_blocks.blocks.length === 1"
                                    class="p-1.5 text-red-500 hover:bg-red-50 rounded disabled:opacity-30 ml-2" title="Delete Block">
                                    ✕ Remove
                                </button>
                            </div>
                        </div>

                        <!-- Block Type Selector -->
                        <div class="flex gap-2">
                            <label class="text-xs font-semibold text-gray-500 self-center">Change Type:</label>
                            <select v-model="block.type" class="text-xs rounded-lg border-gray-300 py-1">
                                <option value="text">Explanation / Text</option>
                                <option value="code">Code Snippet</option>
                                <option value="sql">SQL Query</option>
                                <option value="steps">Step-by-Step Instructions</option>
                            </select>

                            <input v-if="block.type === 'code' || block.type === 'sql'" 
                                type="text" v-model="block.language" placeholder="language (e.g. php, bash)" 
                                class="text-xs rounded-lg border-gray-300 py-1 px-2 w-36" />
                        </div>

                        <!-- Block Content Textarea -->
                        <div>
                            <textarea v-model="block.content" rows="4" 
                                :placeholder="block.type === 'text' ? 'Write your explanation paragraph here...' : block.type === 'steps' ? '1. First step...\n2. Second step...' : 'Enter your code snippet here...'"
                                class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm font-mono"></textarea>
                        </div>
                    </div>

                    <p v-if="form.errors['content_blocks.blocks']" class="text-sm text-red-600">
                        {{ form.errors['content_blocks.blocks'] }}
                    </p>

                    <!-- Add Block Buttons -->
                    <div class="flex flex-wrap gap-2 pt-2">
                        <button type="button" @click="addBlock('text')" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs font-semibold transition">
                            + Add Text Block
                        </button>
                        <button type="button" @click="addBlock('code')" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs font-semibold transition">
                            + Add Code Block
                        </button>
                        <button type="button" @click="addBlock('sql')" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs font-semibold transition">
                            + Add SQL Block
                        </button>
                        <button type="button" @click="addBlock('steps')" class="px-3 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg text-xs font-semibold transition">
                            + Add Steps Block
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end pt-4">
                    <button type="submit" :disabled="form.processing"
                        class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-lg text-sm shadow transition disabled:opacity-50">
                        {{ form.processing ? 'Publishing...' : 'Publish Guide' }}
                    </button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>