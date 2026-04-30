<template>
  <div class="bg-white shadow rounded-xl p-4 border">
    <h2 class="text-gray-500 text-sm">{{ title }}</h2>
    <p class="text-2xl font-bold mt-2">{{ value }}</p>
  </div>
</template>

<script setup>
defineProps({
  title: String,
  value: [String, Number]
})
</script>