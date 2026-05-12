<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import axios from 'axios'
import Chart from 'chart.js/auto'
import AppLayout from '@/Layouts/AppLayout.vue'

defineOptions({ layout: AppLayout })

// ================= STATE =================
const cpuHistory = ref([])
const memoryHistory = ref([])
const alerts = ref([])
const systemStatus = ref('HEALTHY')

const chartRef = ref(null)

let chartInstance = null
let interval = null

const historyLimit = 50

// ================= FETCH =================
const fetchData = async () => {
    try {
        const res = await axios.get('/system/dashboard')

        const cpu = Number(res.data.cpu_usage)
        const mem = Number(res.data.memory_usage)

        cpuHistory.value = [...cpuHistory.value, cpu].slice(-historyLimit)
        memoryHistory.value = [...memoryHistory.value, mem].slice(-historyLimit)

        alerts.value = res.data.alerts
        systemStatus.value = res.data.status

        updateChart()

    } catch (err) {
        console.error(err)
    }
}

// ================= CHART =================
const initChart = () => {
    chartInstance = new Chart(chartRef.value, {
        type: 'line',
        data: {
            labels: [],
            datasets: [
                {
                    label: 'CPU (%)',
                    data: [],
                    borderColor: '#ef4444',
                    tension: 0.3
                },
                {
                    label: 'Memory (%)',
                    data: [],
                    borderColor: '#3b82f6',
                    tension: 0.3
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { labels: { color: '#fff' } }
            },
            scales: {
                x: { ticks: { color: '#9ca3af' } },
                y: { ticks: { color: '#9ca3af' } }
            }
        }
    })
}

const updateChart = () => {
    if (!chartInstance) return

    chartInstance.data.labels = cpuHistory.value.map((_, i) => i + 1)

    chartInstance.data.datasets[0].data = cpuHistory.value
    chartInstance.data.datasets[1].data = memoryHistory.value

    chartInstance.update()
}

// ================= LIFECYCLE =================
onMounted(() => {
    initChart()
    fetchData()
    interval = setInterval(fetchData, 3000)
})

onUnmounted(() => {
    clearInterval(interval)
    chartInstance?.destroy()
})
</script>

<template>
<div class="p-6 text-white">

    <!-- STATUS -->
    <div class="bg-gray-900 p-4 rounded mb-4">
        <h2 class="text-lg font-bold">
            System Status:
            <span :class="systemStatus === 'HEALTHY' ? 'text-green-400' : 'text-red-400'">
                {{ systemStatus }}
            </span>
        </h2>
    </div>

    <!-- ALERTS -->
    <div class="bg-gray-900 p-4 rounded mb-4">
        <h2 class="text-lg font-bold mb-2">Live Alerts</h2>

        <div v-if="alerts.length === 0" class="text-gray-400">
            No active alerts
        </div>

        <div v-for="(a, i) in alerts" :key="i"
             class="p-2 mb-2 rounded"
             :class="a.level === 'CRITICAL' ? 'bg-red-600' : 'bg-yellow-600'">

            <strong>{{ a.level }}</strong> - {{ a.message }}
        </div>
    </div>

    <!-- CHART -->
    <div class="bg-gray-900 p-4 rounded h-[350px]">
        <canvas ref="chartRef"></canvas>
    </div>

</div>
</template>