<script setup>
import { Link } from '@inertiajs/vue3'
</script>

<template>
<div class="w-64 h-screen bg-gray-900 text-white flex flex-col p-4">

    <h2 class="text-xl font-bold mb-6">SOMP</h2>

    <Link href="/dashboard" class="mb-2 hover:text-gray-400">
        Dashboard
    </Link>

    <Link href="/system/usage" class="mb-2 hover:text-gray-400">
        Usage
    </Link>

    <Link href="/system/logs" class="mb-2 hover:text-gray-400">
        Logs
    </Link>

</div>
</template>