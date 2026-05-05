<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import axios from 'axios'
import Navbar from '@/Components/Navbar.vue'
import Chart from 'chart.js/auto'

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

        if (!isNaN(cpu)) cpuHistory.value.push(cpu)
        if (!isNaN(mem)) memoryHistory.value.push(mem)

        // keep last 10 points
        if (cpuHistory.value.length > 10) cpuHistory.value.shift()
        if (memoryHistory.value.length > 10) memoryHistory.value.shift()

        updateChart()
    } catch (err) {
        console.error('System usage fetch error:', err)
    }
}

// ================= UPDATE CHART =================
const updateChart = () => {
    if (!chartInstance) return

    chartInstance.data.labels = cpuHistory.value.map((_, i) => i + 1)

    chartInstance.data.datasets[0].data = cpuHistory.value
    chartInstance.data.datasets[1].data = memoryHistory.value

    chartInstance.update('none')
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
    if (chartInstance) chartInstance.destroy()
})
</script>

<template>
<div class="min-h-screen bg-gray-950 text-white">

    <Navbar />

    <!-- HEADER -->
    <div class="p-6 border-b border-gray-800">
        <h1 class="text-2xl font-bold">System Usage Monitor</h1>
        <p class="text-gray-400 text-sm">
            Real-time CPU & Memory tracking
        </p>
    </div>

    <!-- CHART -->
    <div class="p-6">
        <div class="bg-gray-900 p-4 rounded h-[350px]">
            <canvas ref="chartRef"></canvas>
        </div>
    </div>

</div>
</template>