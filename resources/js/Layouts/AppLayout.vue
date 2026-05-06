<script setup>
import Sidebar from '@/Components/Sidebar.vue'
import Topbar from '@/Components/Topbar.vue'
</script>

<template>
<div class="flex h-screen bg-gray-950 text-white">

    <!-- SIDEBAR -->
    <Sidebar />

    <!-- MAIN CONTENT -->
    <div class="flex-1 flex flex-col">

        <!-- TOPBAR -->
        <Topbar />

        <!-- PAGE CONTENT -->
        <main class="flex-1 overflow-y-auto p-6">
            <slot />
        </main>

    </div>

</div>
</template>