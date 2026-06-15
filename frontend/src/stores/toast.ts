import { defineStore } from 'pinia'
import { ref } from 'vue'

export interface ToastMessage {
  id: number
  message: string
  type: 'success' | 'error' | 'warning' | 'info'
}

let nextId = 1

export const useToastStore = defineStore('toast', () => {
  const messages = ref<ToastMessage[]>([])

  function add(message: string, type: ToastMessage['type'] = 'info') {
    const id = nextId++
    messages.value.push({ id, message, type })
    setTimeout(() => remove(id), 3000)
  }

  function remove(id: number) {
    messages.value = messages.value.filter((m) => m.id !== id)
  }

  return { messages, add, remove }
})
