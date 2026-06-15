<script setup lang="ts">
defineProps<{
  title?: string
}>()
</script>

<template>
  <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
    <div v-if="title || $slots.actions" class="flex items-center justify-between mb-4">
      <h3 v-if="title" class="text-lg font-semibold text-gray-900">{{ title }}</h3>
      <div v-if="$slots.actions" class="flex items-center gap-2">
        <slot name="actions" />
      </div>
    </div>
    <slot />
  </div>
</template>
