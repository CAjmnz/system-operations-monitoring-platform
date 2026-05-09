<script setup>
defineProps({
    totalRequests: Number,
    latest: Array,
});
</script>

<template>
    <div class="p-6">
        <h1 class="text-2xl font-bold mb-4">System Usage</h1>

        <div class="bg-white p-4 rounded shadow mb-4">
            <h2 class="text-lg font-semibold">
                Total Requests: {{ totalRequests }}
            </h2>
        </div>

        <div class="bg-white p-4 rounded shadow">
            <h2 class="font-semibold mb-2">Latest Activity</h2>

            <ul>
                <li v-for="log in latest" :key="log.id">
                    {{ log.method }} - {{ log.route }} - {{ log.ip_address }}
                </li>
            </ul>
        </div>
    </div>
</template>