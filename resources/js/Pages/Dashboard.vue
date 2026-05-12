<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import axios from 'axios'
import AppLayout from '@/Layouts/AppLayout.vue'

defineOptions({
    layout: AppLayout
})

const dashboard = ref({
    server_status: 'OFFLINE',
    cpu_usage: 0,
    memory_usage: 0,
    active_users: 0,
    alerts: []
})

const loading = ref(true)
const lastUpdated = ref('')

let interval = null

const fetchData = async () => {

    try {

        const response = await axios.get('/system/dashboard')

        dashboard.value = response.data

        lastUpdated.value = new Date().toLocaleTimeString()

    } catch (error) {

        console.error(error)

    } finally {

        loading.value = false
    }
}

onMounted(() => {

    fetchData()

    interval = setInterval(fetchData, 3000)
})

onUnmounted(() => {

    clearInterval(interval)
})
</script>

<template>
<div class="p-6 text-white">

    <!-- HEADER -->
    <div class="flex items-center justify-between mb-6">

        <div>

            <h1 class="text-3xl font-bold">
                SOC Monitoring Dashboard
            </h1>

            <p class="text-gray-400 text-sm mt-1">
                Real-time infrastructure monitoring
            </p>

        </div>

        <div class="text-sm text-gray-400">

            Last Updated:
            <span class="text-white">
                {{ lastUpdated }}
            </span>

        </div>

    </div>

    <!-- LOADING -->
    <div
        v-if="loading"
        class="bg-gray-900 border border-gray-800 rounded-xl p-6 text-center text-gray-400"
    >
        Loading dashboard...
    </div>

    <!-- MAIN GRID -->
    <div
        v-else
        class="grid grid-cols-1 lg:grid-cols-3 gap-5"
    >

        <!-- SERVER STATUS -->
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5">

            <div class="text-gray-400 text-sm mb-2">
                System Status
            </div>

            <div
                class="text-3xl font-bold"
                :class="{
                    'text-green-400': dashboard.server_status === 'ONLINE',
                    'text-yellow-400': dashboard.server_status === 'DEGRADED',
                    'text-red-400': dashboard.server_status === 'OFFLINE'
                }"
            >
                {{ dashboard.server_status }}
            </div>

            <div class="mt-3 text-sm text-gray-400">

                <span v-if="dashboard.server_status === 'ONLINE'">
                    All systems operational
                </span>

                <span v-else-if="dashboard.server_status === 'DEGRADED'">
                    Performance degradation detected
                </span>

                <span v-else>
                    System outage detected
                </span>

            </div>

        </div>

        <!-- CPU -->
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5">

            <div class="text-gray-400 text-sm mb-2">
                CPU Usage
            </div>

            <div class="text-3xl font-bold text-blue-400">
                {{ dashboard.cpu_usage }}%
            </div>

            <div class="w-full bg-gray-800 rounded-full h-3 mt-4">

                <div
                    class="bg-blue-500 h-3 rounded-full"
                    :style="{ width: dashboard.cpu_usage + '%' }"
                ></div>

            </div>

        </div>

        <!-- MEMORY -->
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5">

            <div class="text-gray-400 text-sm mb-2">
                Memory Usage
            </div>

            <div class="text-3xl font-bold text-purple-400">
                {{ dashboard.memory_usage }}%
            </div>

            <div class="w-full bg-gray-800 rounded-full h-3 mt-4">

                <div
                    class="bg-purple-500 h-3 rounded-full"
                    :style="{ width: dashboard.memory_usage + '%' }"
                ></div>

            </div>

        </div>

        <!-- ACTIVE USERS -->
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5">

            <div class="text-gray-400 text-sm mb-2">
                Active Users
            </div>

            <div class="text-3xl font-bold text-cyan-400">
                {{ dashboard.active_users }}
            </div>

        </div>

        <!-- LIVE ALERTS -->
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5 lg:col-span-2">

            <div class="text-gray-400 text-sm mb-4">
                Live Alerts
            </div>

            <!-- NO ALERTS -->
            <div
                v-if="dashboard.alerts.length === 0"
                class="bg-green-900 border border-green-500 text-green-300 rounded p-4"
            >
                🟢 No active alerts
            </div>

            <!-- ALERTS -->
            <div
                v-for="alert in dashboard.alerts"
                :key="alert.id"
                class="mb-3 rounded p-4 border"
                :class="{
                    'bg-red-900 border-red-500 text-red-300': alert.level === 'CRITICAL',
                    'bg-yellow-900 border-yellow-500 text-yellow-300': alert.level === 'WARNING',
                    'bg-green-900 border-green-500 text-green-300': alert.level === 'NORMAL'
                }"
            >

                <div class="font-bold text-lg">
                    {{ alert.level }}
                </div>

                <div class="mt-1">
                    {{ alert.message }}
                </div>

                <div class="mt-2 text-xs opacity-70">
                    {{ alert.created_at }}
                </div>

            </div>

        </div>

    </div>

</div>
</template>