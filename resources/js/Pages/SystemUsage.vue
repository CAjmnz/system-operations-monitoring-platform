<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import axios from 'axios'
import Chart from 'chart.js/auto'
import AppLayout from '@/Layouts/AppLayout.vue'

defineOptions({
    layout: AppLayout
})

// ================= STATE =================
const cpuHistory = ref([])
const memoryHistory = ref([])

const alerts = ref([])

const systemStatus = ref('ONLINE')

const chartRef = ref(null)

let chartInstance = null
let interval = null

const historyLimit = 50

// ================= FETCH =================
const fetchData = async () => {

    try {

        const res = await axios.get('/system/dashboard')

        const cpu = Number(res.data.cpu_usage || 0)

        const mem = Number(res.data.memory_usage || 0)

        cpuHistory.value = [
            ...cpuHistory.value,
            cpu
        ].slice(-historyLimit)

        memoryHistory.value = [
            ...memoryHistory.value,
            mem
        ].slice(-historyLimit)

        alerts.value = Array.isArray(res.data.alerts)
            ? res.data.alerts
            : []

        systemStatus.value = res.data.server_status || 'ONLINE'

        updateChart()

    } catch (err) {

        console.error('SystemUsage fetch error:', err)
    }
}

// ================= CHART =================
const initChart = () => {

    if (!chartRef.value) return

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
                    labels: {
                        color: '#ffffff'
                    }
                }
            },

            scales: {

                x: {
                    ticks: {
                        color: '#9ca3af'
                    }
                },

                y: {
                    ticks: {
                        color: '#9ca3af'
                    },
                    min: 0,
                    max: 100
                }
            }
        }
    })
}

const updateChart = () => {

    if (!chartInstance) return

    chartInstance.data.labels = cpuHistory.value.map((_, i) => i + 1)

    chartInstance.data.datasets[0].data = [...cpuHistory.value]

    chartInstance.data.datasets[1].data = [...memoryHistory.value]

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

    if (chartInstance) {

        chartInstance.destroy()

        chartInstance = null
    }
})
</script>

<template>
<div class="p-6 text-white">

    <!-- HEADER -->
    <div class="flex items-center justify-between mb-6">

        <div>

            <h1 class="text-3xl font-bold">
                System Usage Monitor
            </h1>

            <p class="text-sm text-gray-400 mt-1">
                Real-time SOC/NOC infrastructure monitoring
            </p>

        </div>

        <!-- SYSTEM STATUS -->
        <div
            class="px-4 py-2 rounded-xl border text-sm font-bold"
            :class="{
                'bg-green-900 border-green-500 text-green-300': systemStatus === 'ONLINE',
                'bg-yellow-900 border-yellow-500 text-yellow-300': systemStatus === 'DEGRADED',
                'bg-red-900 border-red-500 text-red-300': systemStatus === 'OFFLINE'
            }"
        >
            {{ systemStatus }}
        </div>

    </div>

    <!-- ALERTS -->
    <div class="bg-gray-900 border border-gray-800 p-4 rounded-2xl mb-5">

        <div class="flex items-center justify-between mb-4">

            <h2 class="text-xl font-bold">
                Live Alerts
            </h2>

            <span class="text-sm text-gray-400">
                {{ alerts.length }} Active
            </span>

        </div>

        <!-- NO ALERT -->
        <div
            v-if="alerts.length === 0"
            class="bg-green-900 border border-green-500 text-green-300 rounded-xl p-4"
        >
            🟢 No active alerts detected
        </div>

        <!-- ALERT ITEMS -->
        <div
            v-for="alert in alerts"
            :key="alert.id"
            class="mb-3 rounded-xl border p-4"
            :class="{
                'bg-red-900 border-red-500 text-red-300': alert.level === 'CRITICAL',
                'bg-yellow-900 border-yellow-500 text-yellow-300': alert.level === 'WARNING',
                'bg-green-900 border-green-500 text-green-300': alert.level === 'NORMAL'
            }"
        >

            <div class="flex items-center justify-between">

                <div class="font-bold text-lg">
                    {{ alert.level }}
                </div>

                <div class="text-xs opacity-70">
                    {{ alert.created_at }}
                </div>

            </div>

            <div class="mt-2">
                {{ alert.message }}
            </div>

        </div>

    </div>

    <!-- CHART -->
    <div class="bg-gray-900 border border-gray-800 p-4 rounded-2xl h-[400px]">

        <canvas ref="chartRef"></canvas>

    </div>

</div>
</template>