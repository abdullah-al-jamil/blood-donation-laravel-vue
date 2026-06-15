<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps<{
  currentPage: number
  lastPage: number
  total: number
}>()

const emit = defineEmits<{
  page: [page: number]
}>()

const pages = computed(() => {
  const arr: number[] = []
  const start = Math.max(1, props.currentPage - 2)
  const end = Math.min(props.lastPage, props.currentPage + 2)
  for (let i = start; i <= end; i++) arr.push(i)
  return arr
})
</script>

<template>
  <div v-if="lastPage > 1" class="flex items-center justify-between mt-4">
    <p class="text-sm text-gray-600">Total: {{ total }}</p>
    <div class="flex items-center gap-1">
      <button
        :disabled="currentPage <= 1"
        @click="emit('page', currentPage - 1)"
        class="px-3 py-1 text-sm rounded border border-gray-300 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
      >
        Prev
      </button>
      <button
        v-for="p in pages"
        :key="p"
        @click="emit('page', p)"
        class="px-3 py-1 text-sm rounded border"
        :class="p === currentPage ? 'bg-red-600 text-white border-red-600' : 'border-gray-300 hover:bg-gray-50'"
      >
        {{ p }}
      </button>
      <button
        :disabled="currentPage >= lastPage"
        @click="emit('page', currentPage + 1)"
        class="px-3 py-1 text-sm rounded border border-gray-300 hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed"
      >
        Next
      </button>
    </div>
  </div>
</template>
