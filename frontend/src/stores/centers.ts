import { defineStore } from 'pinia'
import { ref } from 'vue'
import * as publicApi from '@/api/public'
import * as adminApi from '@/api/admin'

export const useCentersStore = defineStore('centers', () => {
  const list = ref<any[]>([])
  const current = ref<any>(null)
  const loading = ref(false)

  async function fetchCenters() {
    loading.value = true
    try {
      const { data } = await publicApi.getCenters()
      list.value = data.data ?? data
    } finally {
      loading.value = false
    }
  }

  async function fetchCenter(id: number) {
    loading.value = true
    try {
      const { data } = await publicApi.getCenter(id)
      current.value = data.data ?? data
    } finally {
      loading.value = false
    }
  }

  return { list, current, loading, fetchCenters, fetchCenter }
})
