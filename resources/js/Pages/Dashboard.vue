<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import Navbar from '@/Components/Navbar.vue'
import SystemCard from '@/Components/SystemCard.vue'

const serverStatus = ref('')
const cpuUsage = ref(0)
const memoryUsage = ref(0)
const activeUsers = ref(0)
const alerts = ref('')

const fetchData = async () => {
    const res = await axios.get('/system/dashboard')

    serverStatus.value = res.data.server_status
    cpuUsage.value = res.data.cpu_usage
    memoryUsage.value = res.data.memory_usage
    activeUsers.value = res.data.active_users
    alerts.value = res.data.alerts
}

onMounted(fetchData)
</script>

<template>
<div class="min-h-screen bg-gray-950 text-white">

    <Navbar />

    <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <SystemCard title="Server Status" :value="serverStatus" />
        <SystemCard title="CPU Usage" :value="cpuUsage + '%'" />
        <SystemCard title="Memory Usage" :value="memoryUsage + '%'" />
        <SystemCard title="Active Users" :value="activeUsers" />
        <SystemCard title="System Alerts" :value="alerts" />
    </div>

</div>
</template>