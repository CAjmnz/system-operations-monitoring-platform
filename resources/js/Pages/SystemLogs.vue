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
const newLog = ref({
    level: 'INFO',
    message: ''
})

let interval = null

const getLogClass = (level) => {
    switch ((level || '').toUpperCase()) {
        case 'INFO': return 'text-green-400'
        case 'WARNING': return 'text-yellow-400'
        case 'ERROR': return 'text-red-400'
        default: return 'text-white'
    }
}

const fetchLogs = async () => {
    const res = await axios.get('/api/logs')
    logs.value = res.data.logs || []
}

const submitLog = async () => {
    if (!newLog.value.message) return

    await axios.post('/api/logs', {
        level: newLog.value.level,
        message: newLog.value.message
    })

    newLog.value.message = ''
    fetchLogs()
}

const filteredLogs = computed(() => {
    return logs.value.filter(log => {
        const level = (log.level || '').toUpperCase()

        return (
            (filter.value === 'ALL' || level === filter.value) &&
            log.message.toLowerCase().includes(search.value.toLowerCase())
        )
    })
})

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

    <h1 class="text-2xl font-bold mb-4">System Logs</h1>

    <!-- CREATE LOG -->
    <div class="flex gap-2 mb-4">
        <select v-model="newLog.level" class="bg-gray-800 p-2 rounded">
            <option>INFO</option>
            <option>WARNING</option>
            <option>ERROR</option>
        </select>

        <input
            v-model="newLog.message"
            class="bg-gray-800 p-2 w-full rounded"
            placeholder="Enter log message..."
        />

        <button
            @click="submitLog"
            class="bg-blue-600 px-4 rounded"
        >
            Add
        </button>
    </div>

    <!-- FILTER -->
    <div class="flex gap-2 mb-3">
        <select v-model="filter" class="bg-gray-800 p-2 rounded">
            <option>ALL</option>
            <option>INFO</option>
            <option>WARNING</option>
            <option>ERROR</option>
        </select>

        <input
            v-model="search"
            class="bg-gray-800 p-2 w-full rounded"
            placeholder="Search logs..."
        />
    </div>

    <!-- LOG LIST -->
    <div
        v-for="(log, i) in filteredLogs"
        :key="i"
        class="border-b border-gray-800 py-1 text-sm"
        :class="getLogClass(log.level)"
    >
        [{{ log.level }}] {{ log.message }}
    </div>

</div>
</template>