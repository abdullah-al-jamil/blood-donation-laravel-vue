import { defineStore } from 'pinia'
import { ref } from 'vue'
import * as donorApi from '@/api/donor'

export const useAppointmentsStore = defineStore('appointments', () => {
  const list = ref<any[]>([])
  const loading = ref(false)

  async function fetchAppointments() {
    loading.value = true
    try {
      const { data } = await donorApi.getAppointments()
      list.value = data.data ?? data
    } finally {
      loading.value = false
    }
  }

  async function createAppointment(formData: any) {
    const { data } = await donorApi.createAppointment(formData)
    list.value.push(data.data ?? data)
    return data
  }

  async function updateAppointment(id: number, formData: any) {
    const { data } = await donorApi.updateAppointment(id, formData)
    const idx = list.value.findIndex((a: any) => a.id === id)
    if (idx !== -1) list.value[idx] = data.data ?? data
    return data
  }

  async function cancelAppointment(id: number) {
    await donorApi.cancelAppointment(id)
    list.value = list.value.filter((a: any) => a.id !== id)
  }

  return { list, loading, fetchAppointments, createAppointment, updateAppointment, cancelAppointment }
})
