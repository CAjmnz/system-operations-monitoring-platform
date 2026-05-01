<script setup>
defineProps({
  title: String,
  value: [String, Number]
})
</script>

<template>
  <div class="bg-gray-900 border border-gray-800 rounded-xl p-5 shadow-md">

    <h3 class="text-gray-400 text-sm uppercase tracking-wide">
      {{ title }}
    </h3>

    <p class="text-white text-2xl font-bold mt-2">
      {{ value }}
    </p>

  </div>
</template>