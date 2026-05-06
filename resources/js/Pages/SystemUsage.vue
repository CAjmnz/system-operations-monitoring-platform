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
const chartRef = ref(null)

let chartInstance = null
let interval = null

// ================= FETCH DATA =================
const fetchData = async () => {
    try {
        const res = await axios.get('/system/dashboard')

        const cpu = Number(res.data.cpu_usage)
        const mem = Number(res.data.memory_usage)

        // IMMUTABLE UPDATE (SAFE FOR VUE + CHART.JS)
        if (!isNaN(cpu)) {
            cpuHistory.value = [...cpuHistory.value, cpu].slice(-10)
        }

        if (!isNaN(mem)) {
            memoryHistory.value = [...memoryHistory.value, mem].slice(-10)
        }

        updateChart()

    } catch (err) {
        console.error('System usage fetch error:', err)
    }
}

// ================= UPDATE CHART =================
const updateChart = () => {
    if (!chartInstance) return

    const cpuData = cpuHistory.value.slice()
    const memData = memoryHistory.value.slice()

    chartInstance.data.labels = cpuData.map((_, i) => i + 1)

    chartInstance.data.datasets[0].data = [...cpuData]
    chartInstance.data.datasets[1].data = [...memData]

    chartInstance.update()
}

// ================= INIT CHART =================
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
                        color: '#fff'
                    }
                }
            },
            scales: {
                x: {
                    ticks: { color: '#9ca3af' }
                },
                y: {
                    ticks: { color: '#9ca3af' }
                }
            }
        }
    })
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
    <div class="p-6">

        <!-- HEADER -->
        <div class="border-b border-gray-800 mb-4 pb-2">
            <h1 class="text-2xl font-bold">System Usage Monitor</h1>
            <p class="text-gray-400 text-sm">
                Real-time CPU & Memory tracking
            </p>
        </div>

        <!-- CHART -->
        <div class="bg-gray-900 p-4 rounded h-[350px]">
            <canvas ref="chartRef"></canvas>
        </div>

    </div>
</template>