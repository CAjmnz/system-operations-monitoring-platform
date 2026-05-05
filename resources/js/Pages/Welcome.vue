<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
});

const showLogin = ref(false);
const showRegister = ref(false);

const loginForm = useForm({
    email: '',
    password: '',
    remember: false,
});

const registerForm = useForm({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submitLogin = () => {
    loginForm.post(route('login'), {
        onFinish: () => loginForm.reset('password'),
    });
};

const submitRegister = () => {
    registerForm.post(route('register'), {
        onFinish: () => registerForm.reset('password', 'password_confirmation'),
    });
};

const logout = () => {
    useForm({}).post(route('logout'));
};
</script>

<template>
<Head title="Welcome" />

<div class="min-h-screen bg-gray-100 dark:bg-gray-900 flex flex-col">

    <!-- ================= HERO ================= -->
    <div class="relative flex-1 flex items-center justify-center px-6 overflow-hidden">

        <!-- BACKGROUND WAVES -->
        <img
            src="https://capsule-render.vercel.app/api?type=waving&height=200&color=gradient&text=WELCOME%20TO%20S.O.M.P"
            class="absolute top-0 left-0 w-full pointer-events-none"
        />

        <img
            src="https://capsule-render.vercel.app/api?type=waving&color=gradient&section=footer"
            class="absolute bottom-0 left-0 w-full opacity-30 pointer-events-none"
        />

        <!-- AUTH BUTTONS (TOP RIGHT) -->
        <div class="absolute top-0 right-0 p-6 flex space-x-2 z-20">

            <button
                v-if="!$page.props.auth.user"
                @click="showLogin = true"
                class="px-3 py-1 bg-white text-black rounded shadow"
            >
                Login
            </button>

            <button
                v-if="!$page.props.auth.user"
                @click="showRegister = true"
                class="px-3 py-1 bg-black text-white rounded shadow"
            >
                Register
            </button>

            <button
                v-if="$page.props.auth.user"
                @click="logout"
                class="px-3 py-1 bg-red-500 text-white rounded shadow"
            >
                Logout
            </button>

        </div>

        <!-- CONTENT -->
        <div class="relative z-10 text-center max-w-3xl">

            <h1 class="text-4xl font-bold text-black-800 dark:text-white">
                What is SOMP?
            </h1>

            <p class="mt-6 text-gray-500 dark:text-gray-300 leading-relaxed">
                <strong>System Operations & Monitoring Platform (SOMP)</strong> is a centralized system designed to monitor, manage, and analyze infrastructure performance in real time.
            </p>

            <p class="mt-4 text-gray-500 dark:text-gray-300 leading-relaxed">
                It consolidates system metrics, logs, alerts, and operational data into one unified dashboard for faster decision-making and improved reliability.
            </p>

            <p class="mt-4 text-gray-500 dark:text-gray-300 leading-relaxed">
                Built for scalability, SOMP supports modular monitoring and future AI-assisted diagnostics.
            </p>

            <!-- CTA -->
            <div class="mt-10 flex justify-center space-x-4">

                <Link
                    :href="route('dashboard')"
                    class="px-6 py-2 bg-blue-600 text-white rounded shadow hover:bg-blue-700"
                >
                    Access Dashboard
                </Link>

                <button class="px-6 py-2 bg-gray-800 text-white rounded shadow hover:bg-gray-900">
                    View System Status
                </button>

            </div>

        </div>

    </div>
</div>

<!-- ================= LOGIN MODAL (GLASS EFFECT) ================= -->
<div
    v-if="showLogin"
    class="fixed inset-0 z-[9999] flex items-center justify-center
           bg-black/40 backdrop-blur-md"
>

    <div class="bg-white/20 backdrop-blur-xl border border-white/30
                p-6 rounded-2xl w-96 shadow-2xl text-white">

        <h2 class="text-xl font-bold mb-4">Login</h2>

        <input
            v-model="loginForm.email"
            type="email"
            placeholder="Email"
            class="w-full mb-2 p-2 rounded bg-white/80 text-black"
        />

        <input
            v-model="loginForm.password"
            type="password"
            placeholder="Password"
            class="w-full mb-4 p-2 rounded bg-white/80 text-black"
        />

        <button
            @click="submitLogin"
            class="w-full bg-blue-600 text-white py-2 rounded"
        >
            Login
        </button>

        <button
            @click="showLogin = false"
            class="mt-2 text-sm text-white/80"
        >
            Close
        </button>

    </div>
</div>

<!-- ================= REGISTER MODAL (GLASS EFFECT) ================= -->
<div
    v-if="showRegister"
    class="fixed inset-0 z-[9999] flex items-center justify-center
           bg-black/40 backdrop-blur-md"
>

    <div class="bg-white/20 backdrop-blur-xl border border-white/30
                p-6 rounded-2xl w-96 shadow-2xl text-white">

        <h2 class="text-xl font-bold mb-4">Register</h2>

        <input
            v-model="registerForm.name"
            type="text"
            placeholder="Name"
            class="w-full mb-2 p-2 rounded bg-white/80 text-black"
        />

        <input
            v-model="registerForm.email"
            type="email"
            placeholder="Email"
            class="w-full mb-2 p-2 rounded bg-white/80 text-black"
        />

        <input
            v-model="registerForm.password"
            type="password"
            placeholder="Password"
            class="w-full mb-2 p-2 rounded bg-white/80 text-black"
        />

        <input
            v-model="registerForm.password_confirmation"
            type="password"
            placeholder="Confirm Password"
            class="w-full mb-4 p-2 rounded bg-white/80 text-black"
        />

        <button
            @click="submitRegister"
            class="w-full bg-green-600 text-white py-2 rounded"
        >
            Register
        </button>

        <button
            @click="showRegister = false"
            class="mt-2 text-sm text-white/80"
        >
            Close
        </button>

    </div>
</div>

</template>