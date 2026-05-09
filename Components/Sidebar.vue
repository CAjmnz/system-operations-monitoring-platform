📄 resources/js/Components/Sidebar.vue
vue<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import { computed } from 'vue'

const page    = usePage()
const user    = computed(() => page.props.auth?.user)
const isAdmin = computed(() => page.props.auth?.user?.usertype === 'admin')

const isActive = (path) => page.url.startsWith(path)
</script>

<template>
<div class="w-64 h-screen bg-gray-900 border-r border-gray-800 p-4 flex flex-col">

    <h2 class="text-xl font-bold mb-6 text-white">S.O.M.P</h2>

    <nav class="flex flex-col gap-2">

        <!-- DEBUG - remove after fixing -->
        <p class="text-xs text-red-400 mb-2">isAdmin: {{ isAdmin }} | usertype: {{ page.props.auth?.user?.usertype }}</p>

        <!-- Dashboard: visible to ALL roles -->
        <Link
            href="/dashboard"
            :class="[
                'px-3 py-2 rounded transition text-sm',
                isActive('/dashboard')
                    ? 'bg-blue-600 text-white'
                    : 'text-gray-300 hover:bg-gray-800'
            ]"
        >
            📊 Dashboard
        </Link>

        <!-- Usage: ADMIN only -->
        <Link
            v-if="isAdmin"
            href="/system/usage"
            :class="[
                'px-3 py-2 rounded transition text-sm',
                isActive('/system/usage')
                    ? 'bg-blue-600 text-white'
                    : 'text-gray-300 hover:bg-gray-800'
            ]"
        >
            📈 System Usage
        </Link>

        <!-- Logs: visible to ALL roles -->
        <Link
            href="/system/logs"
            :class="[
                'px-3 py-2 rounded transition text-sm',
                isActive('/system/logs')
                    ? 'bg-blue-600 text-white'
                    : 'text-gray-300 hover:bg-gray-800'
            ]"
        >
            📜 Logs
        </Link>

    </nav>

    <!-- User info at bottom -->
    <div class="mt-auto pt-4 border-t border-gray-800">
        <p class="text-xs text-gray-500">Logged in as</p>
        <p class="text-sm text-white font-medium">{{ user?.name }}</p>
        <p class="text-xs text-gray-400 capitalize">{{ user?.usertype }}</p>
    </div>

</div>
</template>