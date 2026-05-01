<script setup>
import { ref, onMounted, onUnmounted, computed } from 'vue'
import axios from 'axios'
import SystemCard from '@/Components/SystemCard.vue'
import Chart from 'chart.js/auto'

// ================= STATE =================
const serverStatus = ref('')
const cpuUsage = ref(0)
const memoryUsage = ref(0)
const activeUsers = ref(0)
const alerts = ref('')

const cpuHistory = ref([])
const memoryHistory = ref([])

const logs = ref([])
const filter = ref('ALL')
const search = ref('')

const chartRef = ref(null)
let chartInstance = null
let interval = null

// ================= FETCH DATA =================
const fetchData = async () => {
  try {
    const res = await axios.get('/system/dashboard')

    serverStatus.value = res.data.server_status
    cpuUsage.value = res.data.cpu_usage
    memoryUsage.value = res.data.memory_usage
    activeUsers.value = res.data.active_users
    alerts.value = res.data.alerts

    // 🔥 FORCE NUMERIC (CRITICAL FIX)
    const cpu = Number(res.data.cpu_usage)
    const mem = Number(res.data.memory_usage)

    if (!isNaN(cpu)) cpuHistory.value.push(cpu)
    if (!isNaN(mem)) memoryHistory.value.push(mem)

    // keep last 10 points
    if (cpuHistory.value.length > 10) cpuHistory.value.shift()
    if (memoryHistory.value.length > 10) memoryHistory.value.shift()

    updateChart()

  } catch (err) {
    console.error(err)
    serverStatus.value = 'Offline'
    alerts.value = 'Connection Error'
  }
}

// ================= LOGS =================
const fetchLogs = async () => {
  try {
    const res = await axios.get('/system/logs')
    logs.value = res.data.logs
  } catch (err) {
    console.error(err)
  }
}

// ================= FILTER =================
const filteredLogs = computed(() => {
  return logs.value.filter(log => {
    const matchFilter =
      filter.value === 'ALL' || log.level === filter.value

    const matchSearch =
      log.message.toLowerCase().includes(search.value.toLowerCase())

    return matchFilter && matchSearch
  })
})

// ================= CHART =================
const updateChart = () => {
  if (!chartInstance) return
  if (!chartInstance.data) return

  const cpuData = [...cpuHistory.value]
  const memoryData = [...memoryHistory.value]

  chartInstance.data.labels = cpuData.map((_, i) => i + 1)

  chartInstance.data.datasets[0].data = cpuData
  chartInstance.data.datasets[1].data = memoryData

  chartInstance.update('none') // prevents recursion issues
}

// ================= LIFECYCLE =================
onMounted(() => {
  setTimeout(() => {
    fetchData()
    fetchLogs()
  }, 300)

  interval = setInterval(() => {
    fetchData()
    fetchLogs()
  }, 3000)

  if (chartRef.value) {
    chartInstance = new Chart(chartRef.value, {
      type: 'line',
      data: {
        labels: [],
        datasets: [
          {
            label: 'CPU Usage (%)',
            data: [],
            borderColor: '#ef4444',
            backgroundColor: 'rgba(239,68,68,0.1)',
            tension: 0.3
          },
          {
            label: 'Memory Usage (%)',
            data: [],
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59,130,246,0.1)',
            tension: 0.3
          }
        ]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
          legend: {
            labels: { color: '#fff' }
          }
        },
        scales: {
          x: { ticks: { color: '#9ca3af' } },
          y: { ticks: { color: '#9ca3af' } }
        }
      }
    })
  }
})

onUnmounted(() => {
  clearInterval(interval)
  if (chartInstance) chartInstance.destroy()
})
</script>

<template>
  <div class="min-h-screen bg-gray-950 text-white">

    <!-- HEADER -->
    <div class="p-6 border-b border-gray-800">
      <h1 class="text-2xl font-bold">System Monitoring Dashboard</h1>
      <p class="text-gray-400 text-sm">Stage 3 Stable (Charts + Logs + Filters)</p>
    </div>

    <!-- CHART -->
    <div class="px-6 mt-6">
      <div class="bg-gray-900 border border-gray-800 rounded-lg p-4 h-[300px]">
        <h2 class="text-white mb-2 font-semibold">System Usage</h2>
        <canvas ref="chartRef"></canvas>
      </div>
    </div>

    <!-- SYSTEM CARDS -->
    <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
      <SystemCard title="Server Status" :value="serverStatus" />
      <SystemCard title="CPU Usage" :value="cpuUsage + '%'" />
      <SystemCard title="Memory Usage" :value="memoryUsage + '%'" />
      <SystemCard title="Active Users" :value="activeUsers" />
      <SystemCard title="System Alerts" :value="alerts" />
    </div>

    <!-- LOGS -->
    <div class="px-6 pb-6">
      <div class="bg-black border border-gray-800 rounded-lg p-4 h-[420px] overflow-y-auto">

        <h2 class="text-white font-semibold mb-3">Live System Logs</h2>

        <!-- FILTER -->
        <div class="flex gap-2 mb-3">
          <select v-model="filter" class="bg-gray-800 text-white p-2 rounded">
            <option>ALL</option>
            <option>INFO</option>
            <option>WARNING</option>
            <option>ERROR</option>
          </select>

          <input
            v-model="search"
            placeholder="Search logs..."
            class="bg-gray-800 text-white p-2 rounded w-full"
          />
        </div>

        <!-- LOG LIST -->
        <div
          v-for="(log, index) in filteredLogs"
          :key="index"
          class="text-sm font-mono border-b border-gray-900 py-1"
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

  </div>
</template>