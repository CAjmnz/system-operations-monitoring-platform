<script setup>
import { useForm, router } from '@inertiajs/vue3'

const logout = () => {
    useForm({}).post(route('logout'))
}

const goHome = () => {
    router.visit('/') // ✅ correct navigation
}
</script>

<template>
<div class="flex justify-end items-center gap-2 p-4 bg-gray-900 border-b border-gray-800">

    <!-- BACK BUTTON -->
    <button
        @click="goHome"
        class="bg-gray-700 px-3 py-1 rounded hover:bg-gray-600"
    >
        Back
    </button>

    <!-- LOGOUT -->
    <button
        @click="logout"
        class="bg-red-500 px-3 py-1 rounded hover:bg-white-600"
    >
        Logout
    </button>

</div>
</template>