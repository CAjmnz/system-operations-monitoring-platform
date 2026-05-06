<script setup>
import { Link, usePage } from '@inertiajs/vue3'

const page = usePage()
const user = page.props.auth.user

const isActive = (path) => page.url.startsWith(path)

const menu = [
    { name: 'Dashboard', path: '/dashboard', roles: ['user', 'admin'] },
    { name: 'System Usage', path: '/system/usage', roles: ['admin'] },
    { name: 'Logs', path: '/system/logs', roles: ['admin'] }
]
</script>

<template>
<div class="w-64 h-screen bg-gray-900 border-r border-gray-800 p-4">

    <h2 class="text-xl font-bold mb-6">SOMP</h2>

    <nav class="flex flex-col gap-2">

        <Link
            v-for="item in menu"
            :key="item.path"
            v-if="user && item.roles.includes(user.role)"
            :href="item.path"
            :class="[
                'px-3 py-2 rounded transition',
                isActive(item.path)
                    ? 'bg-blue-600 text-white'
                    : 'text-gray-300 hover:bg-gray-800'
            ]"
        >
            {{ item.name }}
        </Link>

    </nav>

</div>
</template>
