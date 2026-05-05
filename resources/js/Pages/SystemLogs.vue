<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue'
import axios from 'axios'
import Navbar from '@/Components/Navbar.vue'

// ================= STATE =================
const logs = ref([])
const filter = ref('ALL')
const search = ref('')
let interval = null

// ================= FETCH LOGS (API ONLY) =================
const fetchLogs = async () => {
    try {
        const res = await axios.get('/api/logs') // ✅ FIXED ROUTE
        logs.value = res.data.logs || []
    } catch (err) {
        console.error('Failed to fetch logs:', err)
    }
}

// ================= FILTER + SEARCH =================
const filteredLogs = computed(() => {
    return logs.value.filter(log => {
        const level = (log.level || '').toUpperCase()

        const matchFilter =
            filter.value === 'ALL' || level === filter.value

        const matchSearch =
            (log.message || '')
                .toLowerCase()
                .includes(search.value.toLowerCase())

        return matchFilter && matchSearch
    })
})

// ================= LIFECYCLE =================
onMounted(() => {
    fetchLogs()
    interval = setInterval(fetchLogs, 3000) // auto refresh
})

onUnmounted(() => {
    clearInterval(interval)
})
</script>

<template>
<div class="min-h-screen bg-gray-950 text-white">

    <Navbar />

    <div class="p-6">

        <!-- FILTER + SEARCH -->
        <div class="flex gap-2 mb-3">
            <select v-model="filter" class="bg-gray-800 p-2 rounded">
                <option>ALL</option>
                <option>INFO</option>
                <option>WARNING</option>
                <option>ERROR</option>
            </select>

            <input
                v-model="search"
                placeholder="Search logs..."
                class="bg-gray-800 p-2 w-full rounded"
            />
        </div>

        <!-- LOG LIST -->
        <div v-if="filteredLogs.length === 0" class="text-gray-500">
            No logs found...
        </div>

        <div
            v-for="(log, i) in filteredLogs"
            :key="i"
            class="border-b border-gray-800 py-1 font-mono text-sm"
            :class="{
                'text-green-400': log.level === 'INFO',
                'text-yellow-400': log.level === 'WARNING',
                'text-red-400': log.level === 'ERROR'
            }"
        >
            [{{ log.level }}] {{ log.message }}
        </div>

    </div>
</div>
</template>