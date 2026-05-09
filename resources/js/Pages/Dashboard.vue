<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import AppLayout from '@/Layouts/AppLayout.vue'

defineOptions({
    layout: AppLayout
})

const serverStatus = ref('')
const cpuUsage = ref(0)
const memoryUsage = ref(0)
const activeUsers = ref(0)
const alerts = ref('')

const fetchData = async () => {
    try {
        const res = await axios.get('/system/dashboard')

        serverStatus.value = res.data.server_status
        cpuUsage.value = res.data.cpu_usage
        memoryUsage.value = res.data.memory_usage
        activeUsers.value = res.data.active_users
        alerts.value = res.data.alerts
    } catch (err) {
        console.error('Dashboard fetch error:', err)
    }
}

onMounted(() => {
    fetchData()
    setInterval(fetchData, 3000)
})
</script>

<template>
<div class="grid grid-cols-2 md:grid-cols-3 gap-4">

    <div class="p-4 bg-gray-900 rounded">
        Server: {{ serverStatus }}
    </div>

    <div class="p-4 bg-gray-900 rounded">
        CPU: {{ cpuUsage }}%
    </div>

    <div class="p-4 bg-gray-900 rounded">
        Memory: {{ memoryUsage }}%
    </div>

    <div class="p-4 bg-gray-900 rounded">
        Users: {{ activeUsers }}
    </div>

    <div class="p-4 bg-gray-900 rounded">
        Alerts: {{ alerts }}
    </div>

</div>
</template>