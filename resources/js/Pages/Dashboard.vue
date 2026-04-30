<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import axios from 'axios'
import SystemCard from '@/Components/SystemCard.vue'

const serverStatus = ref('')
const cpuUsage = ref('')
const memoryUsage = ref('')
const activeUsers = ref(0)
const alerts = ref('')
const logs = ref([])

let interval = null

const fetchData = async () => {
  try {
    const res = await axios.get('/system/dashboard')

    serverStatus.value = res.data.server_status
    cpuUsage.value = res.data.cpu_usage
    memoryUsage.value = res.data.memory_usage
    activeUsers.value = res.data.active_users
    alerts.value = res.data.alerts

  } catch (err) {
    console.error('API error:', err)
    serverStatus.value = 'Offline'
    alerts.value = 'Connection Error'
  }
}

const fetchLogs = async () => {
  try {
    const res = await axios.get('/system/logs')
    logs.value = res.data.logs
  } catch (err) {
    console.error('Log fetch error:', err)
  }
}
const getLogColor = (log) => {
  const upperLog = log.toUpperCase()

  if (upperLog.includes('ERROR')) {
    return 'text-red-400'
  }

  if (upperLog.includes('WARNING') || upperLog.includes('WARN')) {
    return 'text-yellow-400'
  }

  if (upperLog.includes('INFO')) {
    return 'text-green-400'
  }

  return 'text-gray-300'
}
onMounted(() => {
  fetchData()
  fetchLogs()

  interval = setInterval(() => {
    fetchData()
    fetchLogs()
  }, 3000)
})

onUnmounted(() => {
  clearInterval(interval)
})
</script>

<template>
  <div>

    <!-- System Cards -->
    <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <SystemCard title="Server Status" :value="serverStatus" />
      <SystemCard title="CPU Usage" :value="cpuUsage" />
      <SystemCard title="Memory Usage" :value="memoryUsage" />
      <SystemCard title="Active Users" :value="activeUsers" />
      <SystemCard title="System Alerts" :value="alerts" />
    </div>

    <!-- Live Logs -->
<div class="mt-6 bg-black text-green-400 p-4 rounded-lg h-96 overflow-y-auto">
  <h2 class="text-white mb-2 font-bold">
    Live System Logs
  </h2>

  <div v-if="logs.length === 0" class="text-gray-400">
    Waiting for logs...
  </div>

  <div
    v-for="(log, index) in logs"
    :key="index"
    class="text-sm border-b border-gray-800 py-1 font-mono"
    :class="getLogColor(log)"
  >
    {{ log }}
  </div>
</div>
  </div>
</template>