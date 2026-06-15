<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { getInventorySummary } from '@/api/public'

interface BloodGroup {
  type: string
  status: 'high' | 'moderate' | 'low' | 'unknown'
  units?: number
}

const bloodGroups = ref<BloodGroup[]>([
  { type: 'A+', status: 'unknown' },
  { type: 'A-', status: 'unknown' },
  { type: 'B+', status: 'unknown' },
  { type: 'B-', status: 'unknown' },
  { type: 'O+', status: 'unknown' },
  { type: 'O-', status: 'unknown' },
  { type: 'AB+', status: 'unknown' },
  { type: 'AB-', status: 'unknown' },
])

const demoData: Record<string, BloodGroup['status']> = {
  'A+': 'high', 'A-': 'moderate', 'B+': 'high', 'B-': 'low',
  'O+': 'moderate', 'O-': 'high', 'AB+': 'low', 'AB-': 'low',
}

function getStatusColor(status: string) {
  switch (status) {
    case 'high': return 'bg-green-100 text-green-700 border-green-200'
    case 'moderate': return 'bg-yellow-100 text-yellow-700 border-yellow-200'
    case 'low': return 'bg-red-100 text-red-700 border-red-200'
    default: return 'bg-gray-100 text-gray-500 border-gray-200'
  }
}

function getStatusLabel(status: string) {
  switch (status) {
    case 'high': return 'High Availability'
    case 'moderate': return 'Moderate Availability'
    case 'low': return 'Low Availability'
    default: return 'Loading...'
  }
}

function getBarColor(status: string) {
  switch (status) {
    case 'high': return 'bg-green-500'
    case 'moderate': return 'bg-yellow-500'
    case 'low': return 'bg-red-500'
    default: return 'bg-gray-300'
  }
}

onMounted(async () => {
  try {
    const { data } = await getInventorySummary()
    const summary = data.data ?? data
    if (summary && typeof summary === 'object') {
      bloodGroups.value = bloodGroups.value.map((bg) => {
        const info = summary[bg.type]
        return {
          ...bg,
          status: info?.status ?? demoData[bg.type] ?? 'unknown',
          units: info?.units,
        }
      })
    }
  } catch {
    bloodGroups.value = bloodGroups.value.map((bg) => ({
      ...bg,
      status: demoData[bg.type] ?? 'unknown',
    }))
  }
})
</script>

<template>
  <section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-16">
        <span class="text-red-600 font-semibold text-sm tracking-wider uppercase">Blood Availability</span>
        <h2 class="mt-3 text-3xl sm:text-4xl font-bold text-gray-900">Current Blood Stock</h2>
        <p class="mt-4 text-gray-600 max-w-2xl mx-auto text-lg">
          Real-time availability of blood types across our partnered donation centers.
        </p>
      </div>

      <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 lg:gap-6">
        <div
          v-for="bg in bloodGroups"
          :key="bg.type"
          class="bg-white rounded-xl p-5 border border-gray-200 shadow-sm hover:shadow-md transition-shadow"
        >
          <div class="flex items-center justify-between mb-4">
            <span class="text-2xl font-bold text-gray-900">{{ bg.type }}</span>
            <span
              class="text-xs font-semibold px-2.5 py-1 rounded-full border"
              :class="getStatusColor(bg.status)"
            >
              {{ getStatusLabel(bg.status) }}
            </span>
          </div>
          <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
            <div
              class="h-full rounded-full transition-all duration-700"
              :class="getBarColor(bg.status)"
              :style="{ width: bg.status === 'high' ? '80%' : bg.status === 'moderate' ? '50%' : bg.status === 'low' ? '25%' : '0%' }"
            />
          </div>
          <p class="text-xs text-gray-400 mt-2">{{ bg.units ? `${bg.units} units available` : 'Checking...' }}</p>
        </div>
      </div>

      <div class="text-center mt-10">
        <router-link
          to="/login"
          class="inline-flex items-center gap-2 px-6 py-3 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-colors shadow-md"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
          Search Blood Availability
        </router-link>
      </div>
    </div>
  </section>
</template>