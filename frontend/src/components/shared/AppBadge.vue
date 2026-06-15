<script setup lang="ts">
withDefaults(defineProps<{
  variant?: 'success' | 'warning' | 'danger' | 'info' | 'gray'
}>(), {
  variant: 'gray',
})
</script>

<template>
  <span
    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
    :class="[
      variant === 'success' && 'bg-green-100 text-green-800',
      variant === 'warning' && 'bg-yellow-100 text-yellow-800',
      variant === 'danger' && 'bg-red-100 text-red-800',
      variant === 'info' && 'bg-blue-100 text-blue-800',
      variant === 'gray' && 'bg-gray-100 text-gray-800',
    ]"
  >
    <slot />
  </span>
</template>
