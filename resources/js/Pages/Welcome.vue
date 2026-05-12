<script setup>
import { Head, useForm, usePage } from '@inertiajs/vue3'
import { ref } from 'vue'

defineProps({
    canLogin: Boolean,
    canRegister: Boolean,
    laravelVersion: String,
    phpVersion: String,
})

const page = usePage()

const showLogin        = ref(false)
const showRegister     = ref(false)
const showAccessPrompt = ref(false)

const lockoutSeconds = ref(0)
let lockoutTimer = null

const startLockoutTimer = (seconds) => {
    lockoutSeconds.value = seconds
    clearInterval(lockoutTimer)
    lockoutTimer = setInterval(() => {
        if (lockoutSeconds.value > 0) {
            lockoutSeconds.value--
        } else {
            clearInterval(lockoutTimer)
        }
    }, 1000)
}

const loginForm = useForm({
    email:    '',
    password: '',
    remember: false,
})

const registerForm = useForm({
    name:                  '',
    email:                 '',
    password:              '',
    password_confirmation: '',
})

const submitLogin = () => {
    loginForm.post(route('login'), {
        onFinish: () => loginForm.reset('password'),
        onSuccess: () => { showLogin.value = false },
        onError: (errors) => {
            const match = errors.email?.match(/(\d+) seconds/)
            if (match) startLockoutTimer(parseInt(match[1]))
        },
    })
}

const submitRegister = () => {
    registerForm.post(route('register'), {
        onFinish: () => registerForm.reset('password', 'password_confirmation'),
        onSuccess: () => { showRegister.value = false },
    })
}

const logout = () => {
    useForm({}).post(route('logout'))
}

const handleDashboardAccess = () => {
    if (!page.props.auth.user) {
        showAccessPrompt.value = true
    } else {
        window.location.href = route('dashboard')
    }
}

const goToLoginFromPrompt = () => {
    showAccessPrompt.value = false
    showLogin.value = true
}

const goToRegisterFromPrompt = () => {
    showAccessPrompt.value = false
    showRegister.value = true
}
</script>

<template>
    <Head title="Welcome" />

    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 flex flex-col">

        <!-- HERO -->
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

            <!-- AUTH BUTTONS TOP RIGHT -->
            <div class="absolute top-0 right-0 p-6 flex space-x-2 z-20">
                <button
                    v-if="!page.props.auth.user"
                    @click="showLogin = true"
                    class="px-3 py-1 bg-white text-black rounded shadow"
                >Login</button>

                <button
                    v-if="!page.props.auth.user"
                    @click="showRegister = true"
                    class="px-3 py-1 bg-black text-white rounded shadow"
                >Register</button>

                <button
                    v-if="page.props.auth.user"
                    @click="logout"
                    class="px-3 py-1 bg-red-500 text-white rounded shadow"
                >Logout</button>
            </div>

            <!-- MAIN CONTENT -->
            <div class="relative z-10 text-center max-w-3xl">
                <h1 class="text-4xl font-bold text-black-800 dark:text-white">
                    What is SOMP?
                </h1>
                <p class="mt-6 text-gray-500 dark:text-gray-300 leading-relaxed">
                    <strong>System Operations & Monitoring Platform (SOMP)</strong> is a centralized
                    system designed to monitor, manage, and analyze infrastructure performance in real time.
                </p>
                <p class="mt-4 text-gray-500 dark:text-gray-300 leading-relaxed">
                    It consolidates system metrics, logs, alerts, and operational data into one unified
                    dashboard for faster decision-making and improved reliability.
                </p>
                <p class="mt-4 text-gray-500 dark:text-gray-300 leading-relaxed">
                    Built for scalability, SOMP supports modular monitoring and future AI-assisted diagnostics.
                </p>

                <!-- CTA BUTTONS -->
                <div class="mt-10 flex justify-center space-x-4">
                    <button
                        @click="handleDashboardAccess"
                        class="px-6 py-2 bg-blue-600 text-white rounded shadow hover:bg-blue-700"
                    >Access Dashboard</button>

                    <button class="px-6 py-2 bg-gray-800 text-white rounded shadow hover:bg-gray-900">
                        View System Status
                    </button>
                </div>
            </div>

        </div>
    </div>

    <!-- ACCESS PROMPT MODAL -->
    <div
        v-if="showAccessPrompt"
        class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/40 backdrop-blur-md"
    >
        <div class="bg-white/20 backdrop-blur-xl border border-white/30 p-6 rounded-2xl w-96 shadow-2xl text-white text-center">
            <div class="text-4xl mb-3">🔒</div>
            <h2 class="text-xl font-bold mb-2">Access Restricted</h2>
            <p class="text-white/80 text-sm mb-6">
                You need to be logged in to access the dashboard.<br/>
                Please login or create an account to continue.
            </p>
            <div class="flex space-x-3">
                <button
                    @click="goToLoginFromPrompt"
                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2 rounded font-semibold transition"
                >Login</button>
                <button
                    @click="goToRegisterFromPrompt"
                    class="flex-1 bg-green-600 hover:bg-green-700 text-white py-2 rounded font-semibold transition"
                >Register</button>
            </div>
            <button
                @click="showAccessPrompt = false"
                class="mt-3 text-sm text-white/60 hover:text-white/90 transition"
            >Cancel</button>
        </div>
    </div>

    <!-- LOGIN MODAL -->
    <div
        v-if="showLogin"
        class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/40 backdrop-blur-md"
    >
        <div class="bg-white/20 backdrop-blur-xl border border-white/30 p-6 rounded-2xl w-96 shadow-2xl text-white">

            <h2 class="text-xl font-bold mb-4">Login</h2>

            <!-- Lockout Banner -->
            <div
                v-if="lockoutSeconds > 0"
                class="mb-4 bg-red-500/30 border border-red-400 text-red-200 text-sm rounded p-3 text-center"
            >
                🔒 Too many attempts. Try again in <strong>{{ lockoutSeconds }}s</strong>
            </div>

            <!-- Email -->
            <input
                v-model="loginForm.email"
                type="email"
                placeholder="Email"
                :disabled="lockoutSeconds > 0"
                class="w-full p-2 rounded bg-white/80 text-black disabled:opacity-50"
                :class="loginForm.errors.email ? 'border-2 border-red-400' : 'mb-2'"
            />
            <p v-if="loginForm.errors.email" class="text-red-300 text-xs mb-2 mt-1">
                {{ loginForm.errors.email }}
            </p>

            <!-- Password -->
            <input
                v-model="loginForm.password"
                type="password"
                placeholder="Password"
                :disabled="lockoutSeconds > 0"
                class="w-full p-2 rounded bg-white/80 text-black disabled:opacity-50"
                :class="loginForm.errors.password ? 'border-2 border-red-400' : 'mb-4'"
            />
            <p v-if="loginForm.errors.password" class="text-red-300 text-xs mb-4 mt-1">
                {{ loginForm.errors.password }}
            </p>

            <button
                @click="submitLogin"
                :disabled="loginForm.processing || lockoutSeconds > 0"
                class="w-full bg-blue-600 text-white py-2 rounded disabled:opacity-50 transition"
            >
                {{ lockoutSeconds > 0 ? `Locked (${lockoutSeconds}s)` : loginForm.processing ? 'Logging in...' : 'Login' }}
            </button>

            <button @click="showLogin = false" class="mt-2 text-sm text-white/80">
                Close
            </button>
        </div>
    </div>

    <!-- REGISTER MODAL -->
    <div
        v-if="showRegister"
        class="fixed inset-0 z-[9999] flex items-center justify-center bg-black/40 backdrop-blur-md"
    >
        <div class="bg-white/20 backdrop-blur-xl border border-white/30 p-6 rounded-2xl w-96 shadow-2xl text-white">

            <h2 class="text-xl font-bold mb-4">Register</h2>

            <!-- Name -->
            <input
                v-model="registerForm.name"
                type="text"
                placeholder="Name"
                class="w-full p-2 rounded bg-white/80 text-black"
                :class="registerForm.errors.name ? 'border-2 border-red-400' : 'mb-2'"
            />
            <p v-if="registerForm.errors.name" class="text-red-300 text-xs mb-2 mt-1">
                {{ registerForm.errors.name }}
            </p>

            <!-- Email -->
            <input
                v-model="registerForm.email"
                type="email"
                placeholder="Email"
                class="w-full p-2 rounded bg-white/80 text-black"
                :class="registerForm.errors.email ? 'border-2 border-red-400' : 'mb-2'"
            />
            <p v-if="registerForm.errors.email" class="text-red-300 text-xs mb-2 mt-1">
                {{ registerForm.errors.email }}
            </p>

            <!-- Password -->
            <input
                v-model="registerForm.password"
                type="password"
                placeholder="Password"
                class="w-full p-2 rounded bg-white/80 text-black"
                :class="registerForm.errors.password ? 'border-2 border-red-400' : 'mb-2'"
            />
            <p v-if="registerForm.errors.password" class="text-red-300 text-xs mb-2 mt-1">
                {{ registerForm.errors.password }}
            </p>

            <!-- Confirm Password -->
            <input
                v-model="registerForm.password_confirmation"
                type="password"
                placeholder="Confirm Password"
                class="w-full p-2 rounded bg-white/80 text-black"
                :class="registerForm.errors.password_confirmation ? 'border-2 border-red-400' : 'mb-4'"
            />
            <p v-if="registerForm.errors.password_confirmation" class="text-red-300 text-xs mb-4 mt-1">
                {{ registerForm.errors.password_confirmation }}
            </p>

            <button
                @click="submitRegister"
                :disabled="registerForm.processing"
                class="w-full bg-green-600 text-white py-2 rounded disabled:opacity-50 transition"
            >
                {{ registerForm.processing ? 'Registering...' : 'Register' }}
            </button>

            <button @click="showRegister = false" class="mt-2 text-sm text-white/80">
                Close
            </button>
        </div>
    </div>

</template>