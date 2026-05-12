<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue'
import axios from 'axios'
import AppLayout from '@/Layouts/AppLayout.vue'

defineOptions({
    layout: AppLayout
})

/*
|--------------------------------------------------------------------------
| STATE
|--------------------------------------------------------------------------
*/
const serverStatus = ref('OFFLINE')
const cpuUsage = ref(0)
const memoryUsage = ref(0)
const activeUsers = ref(0)
const alerts = ref('No alerts')
const loading = ref(true)
const lastUpdated = ref('')

let interval = null

/*
|--------------------------------------------------------------------------
| FETCH DASHBOARD DATA
|--------------------------------------------------------------------------
*/
const fetchData = async () => {
    try {
        const res = await axios.get('/system/dashboard')

        serverStatus.value = res.data.server_status
        cpuUsage.value = res.data.cpu_usage
        memoryUsage.value = res.data.memory_usage
        activeUsers.value = res.data.active_users
        alerts.value = res.data.alerts

        lastUpdated.value = new Date().toLocaleTimeString()
    } catch (err) {
        console.error('Dashboard fetch error:', err)
    } finally {
        loading.value = false
    }
}

/*
|--------------------------------------------------------------------------
| STATUS COLORS
|--------------------------------------------------------------------------
*/
const serverStatusClass = computed(() => {
    return serverStatus.value === 'Online'
        ? 'text-green-400'
        : 'text-red-400'
})

const cpuClass = computed(() => {
    if (cpuUsage.value >= 80) return 'text-red-400'
    if (cpuUsage.value >= 60) return 'text-yellow-400'
    return 'text-green-400'
})

const memoryClass = computed(() => {
    if (memoryUsage.value >= 80) return 'text-red-400'
    if (memoryUsage.value >= 60) return 'text-yellow-400'
    return 'text-green-400'
})

/*
|--------------------------------------------------------------------------
| LIFECYCLE
|--------------------------------------------------------------------------
*/
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
                System Dashboard
            </h1>

            <p class="text-sm text-gray-400 mt-1">
                Real-time monitoring overview
            </p>
        </div>

        <div class="text-sm text-gray-400">
            Last updated:
            <span class="text-white">
                {{ lastUpdated || 'Loading...' }}
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

    <!-- DASHBOARD -->
    <div
        v-else
        class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5"
    >

        <!-- SERVER STATUS -->
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5 shadow-lg">

            <div class="text-sm text-gray-400 mb-2">
                Server Status
            </div>

            <div
                class="text-3xl font-bold"
                :class="serverStatusClass"
            >
                {{ serverStatus }}
            </div>

        </div>

        <!-- CPU -->
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5 shadow-lg">

            <div class="text-sm text-gray-400 mb-2">
                CPU Usage
            </div>

            <div
                class="text-3xl font-bold"
                :class="cpuClass"
            >
                {{ cpuUsage }}%
            </div>

            <div class="w-full bg-gray-800 rounded-full h-3 mt-4">
                <div
                    class="bg-blue-500 h-3 rounded-full transition-all duration-500"
                    :style="{ width: cpuUsage + '%' }"
                ></div>
            </div>

        </div>

        <!-- MEMORY -->
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5 shadow-lg">

            <div class="text-sm text-gray-400 mb-2">
                Memory Usage
            </div>

            <div
                class="text-3xl font-bold"
                :class="memoryClass"
            >
                {{ memoryUsage }}%
            </div>

            <div class="w-full bg-gray-800 rounded-full h-3 mt-4">
                <div
                    class="bg-purple-500 h-3 rounded-full transition-all duration-500"
                    :style="{ width: memoryUsage + '%' }"
                ></div>
            </div>

        </div>

        <!-- ACTIVE USERS -->
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5 shadow-lg">

            <div class="text-sm text-gray-400 mb-2">
                Active Users
            </div>

            <div class="text-3xl font-bold text-cyan-400">
                {{ activeUsers }}
            </div>

        </div>

        <!-- ALERTS (FIXED UI ONLY) -->
        <div class="bg-gray-900 border border-gray-800 rounded-2xl p-5 shadow-lg md:col-span-2 xl:col-span-2">

            <div class="text-sm text-gray-400 mb-2">
                Alerts
            </div>

            <div class="space-y-2">

                <div
                    v-if="alerts.includes('CPU') || alerts.includes('cpu')"
                    class="px-3 py-2 rounded bg-red-900 text-red-300 border border-red-500"
                >
                    🔴 {{ alerts }}
                </div>

                <div
                    v-else-if="alerts.includes('Memory') || alerts.includes('memory')"
                    class="px-3 py-2 rounded bg-yellow-900 text-yellow-300 border border-yellow-500"
                >
                    🟡 {{ alerts }}
                </div>

                <div
                    v-else
                    class="px-3 py-2 rounded bg-green-900 text-green-300 border border-green-500"
                >
                    🟢 {{ alerts }}
                </div>

            </div>

        </div>

    </div>

</div>
</template>