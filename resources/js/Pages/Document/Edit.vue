<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    document: Object,
    topics: Array,
});

// Initialize the form with the existing document data so it fills out the inputs
const form = useForm({
    title: props.document.title,
    topic_id: props.document.topic_id,
    content_blocks: props.document.content_blocks || {
        blocks: [
            { type: 'text', content: '', language: '' }
        ]
    }
});

// Add any block type, including individual 'step' blocks
const addBlock = (type) => {
    form.content_blocks.blocks.push({
        type: type,
        content: '',
        language: type === 'code' ? 'PHP' : '' || type === 'sql' ? 'Query' : ''
    });
};

const removeBlock = (index) => {
    if (form.content_blocks.blocks.length > 1) {
        form.content_blocks.blocks.splice(index, 1);
    }
};

const moveBlock = (index, direction) => {
    const newIndex = index + direction;
    if (newIndex >= 0 && newIndex < form.content_blocks.blocks.length) {
        const item = form.content_blocks.blocks.splice(index, 1)[0];
        form.content_blocks.blocks.splice(newIndex, 0, item);
    }
};

// Automatically calculate the sequential step number for any 'step' block
const getStepNumber = (currentIndex) => {
    let count = 0;
    for (let i = 0; i <= currentIndex; i++) {
        if (form.content_blocks.blocks[i].type === 'step') {
            count++;
        }
    }
    return count;
};

const submit = () => {
    form.put(`/docs/${props.document.slug}`);
};
</script>

<template>
    <Head :title="`Edit ${document.title}`" />
    
    <AppLayout>
        <div class="max-w-4xl mx-auto p-8 space-y-6">
            <div class="flex items-center justify-between">
                <Link :href="`/docs/${document.slug}`" class="text-xs font-semibold text-gray-500 hover:text-gray-900 transition flex items-center gap-1.5">
                    &larr; Back
                </Link>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900">Edit Document</h1>
            </div>

            <form @submit.prevent="submit" class="space-y-6">
                <!-- Title & Topic -->
                <div class="bg-white p-6 rounded-xl border border-gray-200 shadow-sm space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Document Title</label>
                        <input type="text" v-model="form.title" placeholder="e.g. Setting up Deployment Workflow" 
                            class="w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900 text-sm" />
                        <p v-if="form.errors.title" class="text-xs text-red-600 mt-1">{{ form.errors.title }}</p>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-1.5">Select Topic / Folder</label>
                        <select v-model="form.topic_id" class="w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900 text-sm">
                            <option value="">Choose a topic...</option>
                            <option v-for="topic in props.topics" :key="topic.id" :value="topic.id">
                                {{ topic.name }}
                            </option>
                        </select>
                        <p v-if="form.errors.topic_id" class="text-xs text-red-600 mt-1">{{ form.errors.topic_id }}</p>
                    </div>
                </div>

                <!-- Dynamic Content Blocks Builder -->
                <div class="space-y-4">
                    <div class="flex items-center justify-between">
                        <h2 class="text-lg font-bold text-gray-900">Content Blocks</h2>
                        <span class="text-xs text-gray-400">Mix steps, explanations, and code in any order</span>
                    </div>

                    <!-- Loop through blocks -->
                    <div v-for="(block, index) in form.content_blocks.blocks" :key="index" 
                        class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm relative space-y-3">
                        
                        <!-- Block Header Controls -->
                        <div class="flex items-center justify-between border-b border-gray-100 pb-3">
                            <div class="flex items-center gap-2">
                                <span class="px-2.5 py-1 bg-gray-100 text-gray-900 text-[10px] font-bold rounded uppercase tracking-wider">
                                    <template v-if="block.type === 'step'">
                                        Step #{{ getStepNumber(index) }}
                                    </template>
                                    <template v-else>
                                        {{ block.type }} block
                                    </template>
                                </span>
                            </div>

                            <div class="flex items-center gap-1">
                                <button type="button" @click="moveBlock(index, -1)" :disabled="index === 0"
                                    class="p-1.5 text-gray-500 hover:bg-gray-100 rounded disabled:opacity-30 text-xs" title="Move Up">
                                    &uarr;
                                </button>
                                <button type="button" @click="moveBlock(index, 1)" :disabled="index === form.content_blocks.blocks.length - 1"
                                    class="p-1.5 text-gray-500 hover:bg-gray-100 rounded disabled:opacity-30 text-xs" title="Move Down">
                                    &darr;
                                </button>
                                <button type="button" @click="removeBlock(index)" :disabled="form.content_blocks.blocks.length === 1"
                                    class="p-1.5 text-red-500 hover:bg-red-50 rounded disabled:opacity-30 ml-2 text-xs font-semibold" title="Delete Block">
                                    Remove
                                </button>
                            </div>
                        </div>

                        <!-- Block Type Selector -->
                        <div class="flex gap-2 items-center">
                            <label class="text-xs font-semibold text-gray-500">Type:</label>
                            <select v-model="block.type" class="text-xs rounded-lg border-gray-300 py-1 focus:border-gray-900 focus:ring-gray-900">
                                <option value="text">Explanation / Text</option>
                                <option value="step">Single Step</option>
                                <option value="code">Code Snippet</option>
                                <option value="sql">SQL Query</option>
                            </select>

                            <input v-if="block.type === 'code' || block.type === 'sql'" 
                                type="text" v-model="block.language" placeholder="Language (e.g. PHP, Bash)" 
                                class="text-xs rounded-lg border-gray-300 py-1 px-2 w-36 focus:border-gray-900 focus:ring-gray-900" />
                        </div>

                        <!-- Block Content Input -->
                        <div>
                            <!-- If it's a step block -->
                            <div v-if="block.type === 'step'" class="flex items-center gap-3">
                                <span class="w-7 h-7 rounded-full bg-gray-900 text-white font-bold text-xs flex items-center justify-center shrink-0 shadow-sm">
                                    {{ getStepNumber(index) }}
                                </span>
                                <input type="text" v-model="block.content" 
                                    :placeholder="`Enter instruction for step ${getStepNumber(index)}...`"
                                    class="w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900 text-sm" />
                            </div>

                            <!-- If it's text, code, or sql -->
                            <textarea v-else v-model="block.content" rows="4" 
                                :placeholder="block.type === 'text' ? 'Write your explanation paragraph here...' : 'Enter your code snippet here...'"
                                class="w-full rounded-lg border-gray-300 focus:border-gray-900 focus:ring-gray-900 text-sm font-mono"></textarea>
                        </div>
                    </div>

                    <!-- Add Block Buttons -->
                    <div class="flex flex-wrap gap-2 pt-2">
                        <button type="button" @click="addBlock('text')" class="px-3.5 py-2 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 rounded-lg text-xs font-semibold transition shadow-sm">
                            + Add Text
                        </button>
                        <button type="button" @click="addBlock('step')" class="px-3.5 py-2 bg-gray-900 hover:bg-gray-800 text-white rounded-lg text-xs font-semibold transition shadow-sm">
                            + Add Step
                        </button>
                        <button type="button" @click="addBlock('code')" class="px-3.5 py-2 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 rounded-lg text-xs font-semibold transition shadow-sm">
                            + Add Code
                        </button>
                        <button type="button" @click="addBlock('sql')" class="px-3.5 py-2 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 rounded-lg text-xs font-semibold transition shadow-sm">
                            + Add SQL
                        </button>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex justify-end pt-4">
                    <button type="submit" :disabled="form.processing"
                        class="px-6 py-2.5 bg-gray-900 hover:bg-gray-800 text-white font-semibold rounded-lg text-xs tracking-wider uppercase transition shadow-sm disabled:opacity-50">
                        {{ form.processing ? 'Updating...' : 'Update Document' }}
                    </button>
                </div>
            </form>
        </div>
    </AppLayout>
</template>