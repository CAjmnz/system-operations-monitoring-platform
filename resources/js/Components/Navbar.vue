<script setup>
import { Link, useForm } from '@inertiajs/vue3'

const logout = () => {
    useForm({}).post(route('logout'))
}
</script>

<template>
<div class="flex justify-between items-center p-4 bg-gray-900 border-b border-gray-800">

    <div class="flex gap-4">
        <Link href="/dashboard" class="text-white hover:text-gray-300">Dashboard</Link>
        <Link href="/system/usage" class="text-white hover:text-gray-300">Usage</Link>
        <Link href="/system/logs" class="text-white hover:text-gray-300">Logs</Link>
    </div>

    <button @click="logout" class="bg-red-500 px-3 py-1 rounded hover:bg-red-600">
        Logout
    </button>

</div>
</template>