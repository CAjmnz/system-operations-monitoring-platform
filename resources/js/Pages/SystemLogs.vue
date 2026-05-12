<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue'
import axios from 'axios'
import AppLayout from '@/Layouts/AppLayout.vue'

defineOptions({
    layout: AppLayout
})

const logs = ref([])
const filter = ref('ALL')
const search = ref('')
const loading = ref(true)
const lastUpdated = ref('')

const newLog = ref({
    level: 'INFO',
    message: ''
})

let interval = null

/*
|--------------------------------------------------------------------------
| LEVEL COLORS
|--------------------------------------------------------------------------
*/
const getLogClass = (level) => {
    switch ((level || '').toUpperCase()) {
        case 'INFO':
            return 'bg-green-500/20 text-green-400 border-green-500/30'

        case 'WARNING':
            return 'bg-yellow-500/20 text-yellow-400 border-yellow-500/30'

        case 'ERROR':
            return 'bg-red-500/20 text-red-400 border-red-500/30'

        default:
            return 'bg-gray-500/20 text-gray-300 border-gray-500/30'
    }
}

/*
|--------------------------------------------------------------------------
| FETCH LOGS
|--------------------------------------------------------------------------
*/
const fetchLogs = async () => {
    try {
        const res = await axios.get('/api/logs')

        logs.value = res.data.logs || []

        lastUpdated.value = new Date().toLocaleTimeString()
    } catch (error) {
        console.error('Failed to fetch logs:', error)
    } finally {
        loading.value = false
    }
}

/*
|--------------------------------------------------------------------------
| ADD LOG
|--------------------------------------------------------------------------
*/
const submitLog = async () => {
    if (!newLog.value.message.trim()) return

    try {
        await axios.post('/api/logs', {
            level: newLog.value.level,
            message: newLog.value.message
        })

        newLog.value.message = ''

        fetchLogs()
    } catch (error) {
        console.error('Failed to submit log:', error)
    }
}

/*
|--------------------------------------------------------------------------
| FILTERED LOGS
|--------------------------------------------------------------------------
*/
const filteredLogs = computed(() => {
    return logs.value.filter((log) => {
        const level = (log.level || '').toUpperCase()

        return (
            (filter.value === 'ALL' || level === filter.value) &&
            (log.message || '')
                .toLowerCase()
                .includes(search.value.toLowerCase())
        )
    })
})

/*
|--------------------------------------------------------------------------
| LIFECYCLE
|--------------------------------------------------------------------------
*/
onMounted(() => {
    fetchLogs()

    interval = setInterval(fetchLogs, 3000)
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
                System Logs
            </h1>

            <p class="text-sm text-gray-400 mt-1">
                Monitor platform activity and events
            </p>
        </div>

        <div class="text-sm text-gray-400">
            Last updated:
            <span class="text-white">
                {{ lastUpdated || 'Loading...' }}
            </span>
        </div>
    </div>

    <!-- CREATE LOG -->
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-4 mb-6">

        <h2 class="text-lg font-semibold mb-3">
            Create Log
        </h2>

        <div class="flex gap-2">

            <select
                v-model="newLog.level"
                class="bg-gray-800 border border-gray-700 p-2 rounded-lg"
            >
                <option>INFO</option>
                <option>WARNING</option>
                <option>ERROR</option>
            </select>

            <input
                v-model="newLog.message"
                class="bg-gray-800 border border-gray-700 p-2 rounded-lg w-full"
                placeholder="Enter log message..."
            />

            <button
                @click="submitLog"
                class="bg-blue-600 hover:bg-blue-700 transition px-5 rounded-lg"
            >
                Add
            </button>
        </div>
    </div>

    <!-- FILTERS -->
    <div class="bg-gray-900 border border-gray-800 rounded-xl p-4 mb-6">

        <div class="flex gap-2">

            <select
                v-model="filter"
                class="bg-gray-800 border border-gray-700 p-2 rounded-lg"
            >
                <option>ALL</option>
                <option>INFO</option>
                <option>WARNING</option>
                <option>ERROR</option>
            </select>

            <input
                v-model="search"
                class="bg-gray-800 border border-gray-700 p-2 rounded-lg w-full"
                placeholder="Search logs..."
            />
        </div>
    </div>

    <!-- LOG TABLE -->
    <div class="bg-gray-900 border border-gray-800 rounded-xl overflow-hidden">

        <!-- TABLE HEADER -->
        <div class="grid grid-cols-12 gap-4 bg-gray-800 px-4 py-3 text-sm font-semibold text-gray-300">

            <div class="col-span-2">
                Level
            </div>

            <div class="col-span-7">
                Message
            </div>

            <div class="col-span-3">
                Created
            </div>
        </div>

        <!-- LOADING -->
        <div
            v-if="loading"
            class="p-6 text-center text-gray-400"
        >
            Loading logs...
        </div>

        <!-- EMPTY -->
        <div
            v-else-if="filteredLogs.length === 0"
            class="p-6 text-center text-gray-500"
        >
            No logs found
        </div>

        <!-- LOGS -->
        <div
            v-else
            class="max-h-[500px] overflow-y-auto"
        >
            <div
                v-for="log in filteredLogs"
                :key="log.id"
                class="grid grid-cols-12 gap-4 px-4 py-3 border-b border-gray-800 text-sm items-center hover:bg-gray-800/40 transition"
            >

                <!-- LEVEL -->
                <div class="col-span-2">
                    <span
                        class="px-3 py-1 rounded-full border text-xs font-bold"
                        :class="getLogClass(log.level)"
                    >
                        {{ log.level }}
                    </span>
                </div>

                <!-- MESSAGE -->
                <div class="col-span-7 break-words">
                    {{ log.message }}
                </div>

                <!-- CREATED -->
                <div class="col-span-3 text-gray-400 text-xs">
                    {{ new Date(log.created_at).toLocaleString() }}
                </div>

            </div>
        </div>

    </div>

</div>
</template>