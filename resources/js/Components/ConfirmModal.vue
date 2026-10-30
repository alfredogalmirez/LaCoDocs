<script setup>
defineProps({
    show: {
        type: Boolean,
        default: false,
    },
    title: {
        type: String,
        default: 'Are you sure?',
    },
    message: {
        type: String,
        default: 'This action cannot be undone.',
    },
    confirmText: {
        type: String,
        default: 'Confirm',
    },
    cancelText: {
        type: String,
        default: 'Cancel',
    },
    confirmButtonClass: {
        type: String,
        default: 'bg-red-600 hover:bg-red-700 text-white',
    }
})

defineEmits(['confirm', 'cancel']);
</script>

<template>
    <transition enter-active-class="transition duration-300 ease-out" enter-from-class="transform opacity-0 scale-95"
        enter-to-class="transform opacity-100 scale-100" leave-active-class="transition duration-200 ease-in"
        leave-from-class="tranform opacity-100 scale-100" leave-to-class="transform opacity-0 scale-95">
        
        <div v-if="show" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50 backdrop-blur-sm">
            <div class="w-full max-w-md p-6 bg-white rounded-xl shadow-2xl dark:bg-gray-800">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                    {{ title }}
                </h3>

                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                    {{ message }}
                </p>

                <div class="mt-6 flex justify-end space-x-3">
                    <button @click="$emit('cancel')" type="button"
                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                        {{ cancelText }}
                    </button>
                    <button @click="$emit('confirm')" type="button"
                        class="px-4 py-2 text-sm font-medium rounded-lg transition shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2"
                        :class="confirmButtonClass"
                        >
                        {{ confirmText }}
                    </button>
                </div>
            </div>
        </div>
    </transition>
</template>