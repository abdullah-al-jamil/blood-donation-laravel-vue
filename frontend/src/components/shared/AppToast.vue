<script setup lang="ts">
import { useToastStore } from '@/stores/toast'

const toast = useToastStore()
</script>

<template>
  <div class="fixed top-4 right-4 z-[100] flex flex-col gap-2">
    <TransitionGroup name="toast">
      <div
        v-for="msg in toast.messages"
        :key="msg.id"
        @click="toast.remove(msg.id)"
        class="cursor-pointer px-4 py-3 rounded-lg shadow-lg text-white text-sm max-w-sm transition-all duration-300"
        :class="[
          msg.type === 'success' && 'bg-green-600',
          msg.type === 'error' && 'bg-red-600',
          msg.type === 'warning' && 'bg-yellow-600',
          msg.type === 'info' && 'bg-blue-600',
        ]"
      >
        {{ msg.message }}
      </div>
    </TransitionGroup>
  </div>
</template>

<style scoped>
.toast-enter-active { animation: toast-in 0.3s ease-out; }
.toast-leave-active { animation: toast-out 0.3s ease-in; }
@keyframes toast-in {
  from { transform: translateX(100%); opacity: 0; }
  to { transform: translateX(0); opacity: 1; }
}
@keyframes toast-out {
  from { transform: translateX(0); opacity: 1; }
  to { transform: translateX(100%); opacity: 0; }
}
</style>
