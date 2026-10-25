<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post('/login', {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>

    <Head title="Log in to LaCoDocs" />

    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-12 bg-gray-50 text-gray-900">

        <!-- Left Side: Branding / Developer Preview Panel -->
        <div
            class="hidden lg:flex lg:col-span-6 bg-gray-900 text-white p-12 flex-col justify-between relative overflow-hidden">
            <!-- Subtle background grid lines pattern -->
            <div
                class="absolute inset-0 opacity-10 bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] [background-size:16px_16px]">
            </div>

            <!-- Top Logo / Brand -->
            <div class="relative z-10 flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg bg-gray-900 flex items-center justify-center text-white font-bold">
                    <div class="w-9 h-9 rounded-lg bg-gray-900 flex items-center justify-center text-white font-bold">
                        <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <path d="M8 12h8"></path>
                            <path d="M8 16h5"></path>
                            <path d="M8 8h1"></path>
                        </svg>
                    </div>
                </div>
                <span class="text-xl font-bold tracking-tight">LaCoDocs</span>
            </div>

            <!-- Center Showcase Card -->
            <div class="relative z-10 max-w-md my-auto space-y-6">
                <div
                    class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gray-500/10 border border-gray-500/20 text-white-400 text-xs font-semibold tracking-wide uppercase">
                    Knowledge Management System
                </div>
                <h1 class="text-4xl font-extrabold tracking-tight leading-tight">
                    Your architecture, code snippets, and guides, unified.
                </h1>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Organize your technical notes into structured topics, step-by-step instructions, and
                    syntax-highlighted code blocks.
                </p>

                <!-- Mini Code Snippet UI Preview Card -->
                <div class="bg-gray-800/80 border border-gray-700/60 rounded-xl p-4 shadow-2xl backdrop-blur">
                    <div
                        class="flex items-center justify-between pb-3 mb-3 border-b border-gray-700/60 text-xs text-gray-400 font-mono">
                        <span>deployment-guide.md</span>
                        <span class="text-indigo-400">bash</span>
                    </div>
                    <pre class="text-xs font-mono text-gray-300 overflow-x-auto">
<code>git add .
git commit -m "Initial commit: LaCoDocs MVP"
git push -u origin main
                        </code>
                    </pre>
                </div>
            </div>

            <!-- Footer Copyright -->
            <div class="relative z-10 text-xs text-gray-500">
                &copy; {{ new Date().getFullYear() }} LaCoDocs. Built for developers by Alfredo Almirez.
            </div>
        </div>

        <!-- Right Side: Login Form -->
        <div class="lg:col-span-6 flex flex-col justify-center px-6 py-12 lg:px-20 xl:px-24">
            <div class="mx-auto w-full max-w-sm space-y-8">

                <!-- Mobile Logo (shown only on small screens) -->
                <div class="flex lg:hidden items-center gap-3 mb-6">
                    <div class="w-9 h-9 rounded-lg bg-indigo-600 flex items-center justify-center font-bold text-white">
                        L
                    </div>
                    <span class="text-lg font-bold">LaCoDocs</span>
                </div>

                <div>
                    <h2 class="text-2xl font-bold tracking-tight text-gray-900">Welcome back</h2>
                    <p class="text-sm text-gray-500 mt-1">Please enter your credentials to access your workspace.</p>
                </div>

                <!-- Session Status Notice -->
                <div v-if="status"
                    class="p-3 rounded-lg bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-medium">
                    {{ status }}
                </div>

                <form @submit.prevent="submit" class="space-y-5">
                    <!-- Email Field -->
                    <div>
                        <label for="email"
                            class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Email
                            Address</label>
                        <div class="relative">
                            <span
                                class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <!-- Envelope SVG Icon -->
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </span>
                            <input id="email" type="email" v-model="form.email" required autofocus
                                autocomplete="username" placeholder="name@example.com"
                                class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 transition" />
                        </div>
                        <p v-if="form.errors.email" class="text-xs text-red-600 mt-1.5">{{ form.errors.email }}</p>
                    </div>

                    <!-- Password Field -->
                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="password"
                                class="block text-xs font-semibold text-gray-700 uppercase tracking-wider">Password</label>
                            <Link v-if="canResetPassword" :href="route('password.request')"
                                class="text-xs text-indigo-600 hover:text-indigo-800 font-medium">
                                Forgot password?
                            </Link>
                        </div>
                        <div class="relative">
                            <span
                                class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <!-- Lock SVG Icon -->
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </span>
                            <input id="password" type="password" v-model="form.password" required
                                autocomplete="current-password" placeholder="••••••••"
                                class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-indigo-600 transition" />
                        </div>
                        <p v-if="form.errors.password" class="text-xs text-red-600 mt-1.5">{{ form.errors.password }}
                        </p>
                    </div>

                    <!-- Remember Me Checkbox -->
                    <div class="flex items-center">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" v-model="form.remember"
                                class="rounded border-gray-300 text-indigo-600 focus:ring-indigo-500 w-4 h-4" />
                            <span class="text-xs text-gray-600 font-medium">Remember me on this device</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" :disabled="form.processing"
                        class="w-full py-2.5 px-4 bg-gray-900 hover:bg-gray-800 text-white font-medium rounded-lg text-sm transition shadow-sm disabled:opacity-50 flex items-center justify-center gap-2">
                        <span v-if="form.processing">Signing in...</span>
                        <span v-else>Sign in to workspace</span>
                    </button>
                </form>

                <!-- Footer Sign up Link -->
                <div class="text-center pt-4 border-t border-gray-200">
                    <p class="text-xs text-gray-500">
                        Don't have an account yet?
                        <Link :href="route('register')"
                            class="text-indigo-600 hover:text-indigo-800 font-semibold ml-1">
                            Create account
                        </Link>
                    </p>
                </div>

            </div>
        </div>

    </div>
</template>