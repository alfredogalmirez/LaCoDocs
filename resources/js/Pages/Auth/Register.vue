<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';

const form = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => form.reset('password', 'password_confirmation'),
    });
};
</script>

<template>
    <Head title="Create account - LaCoDocs" />

    <div class="min-h-screen grid grid-cols-1 lg:grid-cols-12 bg-gray-50 text-gray-900">
        
        <!-- Left Side: Registration Form (Flipped Position) -->
        <div class="lg:col-span-6 flex flex-col justify-center px-6 py-12 lg:px-20 xl:px-24 order-2 lg:order-1">
            <div class="mx-auto w-full max-w-sm space-y-8">
                
                <!-- Mobile Logo (shown only on small screens) -->
                <div class="flex lg:hidden items-center gap-3 mb-6">
                    <div class="w-9 h-9 rounded-lg bg-gray-900 flex items-center justify-center text-white font-bold">
                        <svg class="w-5 h-5 text-white" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                            <polyline points="14 2 14 8 20 8"></polyline>
                            <path d="M8 12h8"></path>
                            <path d="M8 16h5"></path>
                            <path d="M8 8h1"></path>
                        </svg>
                    </div>
                    <span class="text-lg font-bold">LaCoDocs</span>
                </div>

                <div>
                    <h2 class="text-2xl font-bold tracking-tight text-gray-900">Create an account</h2>
                    <p class="text-sm text-gray-500 mt-1">Enter your details to set up your developer workspace.</p>
                </div>

                <form @submit.prevent="submit" class="space-y-5">
                    
                    <!-- Full Name Field -->
                    <div>
                        <label for="name" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Full Name</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <!-- User SVG Icon -->
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </span>
                            <input id="name" type="text" v-model="form.name" required autofocus autocomplete="name"
                                placeholder="Alfredo Almirez"
                                class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition" />
                        </div>
                        <p v-if="form.errors.name" class="text-xs text-red-600 mt-1.5">{{ form.errors.name }}</p>
                    </div>

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Email Address</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <!-- Envelope SVG Icon -->
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                            </span>
                            <input id="email" type="email" v-model="form.email" required autocomplete="username"
                                placeholder="name@example.com"
                                class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition" />
                        </div>
                        <p v-if="form.errors.email" class="text-xs text-red-600 mt-1.5">{{ form.errors.email }}</p>
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <!-- Lock SVG Icon -->
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </span>
                            <input id="password" type="password" v-model="form.password" required autocomplete="new-password"
                                placeholder="••••••••"
                                class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition" />
                        </div>
                        <p v-if="form.errors.password" class="text-xs text-red-600 mt-1.5">{{ form.errors.password }}</p>
                    </div>

                    <!-- Confirm Password Field -->
                    <div>
                        <label for="password_confirmation" class="block text-xs font-semibold text-gray-700 uppercase tracking-wider mb-2">Confirm Password</label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-gray-400">
                                <!-- Lock SVG Icon -->
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </span>
                            <input id="password_confirmation" type="password" v-model="form.password_confirmation" required autocomplete="new-password"
                                placeholder="••••••••"
                                class="w-full pl-10 pr-4 py-2.5 rounded-lg border border-gray-300 text-sm focus:outline-none focus:ring-2 focus:ring-gray-900 focus:border-gray-900 transition" />
                        </div>
                        <p v-if="form.errors.password_confirmation" class="text-xs text-red-600 mt-1.5">{{ form.errors.password_confirmation }}</p>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" :disabled="form.processing"
                        class="w-full py-2.5 px-4 bg-gray-900 hover:bg-gray-800 text-white font-medium rounded-lg text-sm transition shadow-sm disabled:opacity-50 flex items-center justify-center gap-2">
                        <span v-if="form.processing">Creating account...</span>
                        <span v-else>Create account</span>
                    </button>
                </form>

                <!-- Footer Sign In Link -->
                <div class="text-center pt-4 border-t border-gray-200">
                    <p class="text-xs text-gray-500">
                        Already have an account? 
                        <Link :href="route('login')" class="text-gray-900 hover:underline font-semibold ml-1">
                            Sign in
                        </Link>
                    </p>
                </div>

            </div>
        </div>

        <!-- Right Side: Branding / Developer Preview Panel (Flipped Position) -->
        <div class="hidden lg:flex lg:col-span-6 bg-gray-900 text-white p-12 flex-col justify-between relative overflow-hidden order-1 lg:order-2">
            <!-- Subtle background grid lines pattern -->
            <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#e5e7eb_1px,transparent_1px)] [background-size:16px_16px]"></div>
            
            <!-- Top Logo / Brand -->
            <div class="relative z-10 flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-white flex items-center justify-center text-gray-900 shadow-sm font-bold">
                    <svg class="w-5 h-5 text-gray-900" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                        <polyline points="14 2 14 8 20 8"></polyline>
                        <path d="M8 12h8"></path>
                        <path d="M8 16h5"></path>
                        <path d="M8 8h1"></path>
                    </svg>
                </div>
                <span class="text-xl font-bold tracking-tight">LaCoDocs</span>
            </div>

            <!-- Center Showcase Card -->
            <div class="relative z-10 max-w-md my-auto space-y-6">
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-gray-800 border border-gray-700 text-gray-300 text-xs font-semibold tracking-wide uppercase">
                    Developer Workspace
                </div>
                <h1 class="text-4xl font-extrabold tracking-tight leading-tight">
                    Start documenting your code architecture today.
                </h1>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Create your account to organize technical guides, structured code blocks, and deployment steps in one unified hub.
                </p>

                <!-- Mini Code Snippet UI Preview Card -->
                <div class="bg-gray-800/80 border border-gray-700/60 rounded-xl p-4 shadow-2xl backdrop-blur">
                    <div class="flex items-center justify-between pb-3 mb-3 border-b border-gray-700/60 text-xs text-gray-400 font-mono">
                        <span>initialize-workspace.sh</span>
                        <span class="text-gray-300">bash</span>
                    </div>
                    <pre class="text-xs font-mono text-gray-300 overflow-x-auto"><code>composer create-project laravel/laravel lacodocs
cd lacodocs
php artisan serve</code></pre>
                </div>
            </div>

            <!-- Footer Copyright -->
            <div class="relative z-10 text-xs text-gray-500">
                &copy; {{ new Date().getFullYear() }} LaCoDocs. Built for developers by Alfredo Almirez.
            </div>
        </div>

    </div>
</template>