<script setup>
import { Link } from '@inertiajs/vue3';
</script>

<template>
    <div class="p-4 bg-gray-900 text-white min-h-screen">
        <h1 class="text-xl font-bold mb-6">
            SOMP
        </h1>

        <nav class="flex flex-col gap-3">

            <!-- Dashboard -->
            <Link
                href="/dashboard"
                class="p-2 rounded hover:bg-gray-700"
            >
                Dashboard
            </Link>

            <!-- System Dashboard -->
            <Link
                href="/system/dashboard"
                class="p-2 rounded hover:bg-gray-700"
            >
                System Dashboard
            </Link>

            <!-- Logs -->
            <Link
                href="/api/logs"
                class="p-2 rounded hover:bg-gray-700"
            >
                Logs
            </Link>

        </nav>
    </div>
</template>