<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'

const stats = [
  { label: 'Total Donations', value: 15000, suffix: '+', icon: 'blood' },
  { label: 'Active Donors', value: 8500, suffix: '+', icon: 'donors' },
  { label: 'Hospitals Connected', value: 200, suffix: '+', icon: 'hospital' },
  { label: 'Lives Saved', value: 45000, suffix: '+', icon: 'lives' },
]

const counts = stats.map(() => ref(0))

const sectionRef = ref<HTMLElement | null>(null)
let observer: IntersectionObserver | null = null
let animated = false

function animateCounters() {
  if (animated) return
  animated = true
  stats.forEach((stat, i) => {
    const duration = 2000
    const steps = 60
    const increment = stat.value / steps
    let current = 0
    const interval = setInterval(() => {
      current += increment
      if (current >= stat.value) {
        counts[i]!.value = stat.value
        clearInterval(interval)
      } else {
        counts[i]!.value = Math.floor(current)
      }
    }, duration / steps)
  })
}

onMounted(() => {
  observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          animateCounters()
          if (observer) observer.disconnect()
        }
      })
    },
    { threshold: 0.3 }
  )
  if (sectionRef.value) observer.observe(sectionRef.value)
})

onUnmounted(() => {
  if (observer) observer.disconnect()
})
</script>

<template>
  <section ref="sectionRef" class="py-20 bg-gradient-to-br from-red-700 to-red-800 relative overflow-hidden">
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_left,_rgba(255,255,255,0.1)_0%,_transparent_50%)]" />
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_bottom_right,_rgba(0,0,0,0.1)_0%,_transparent_50%)]" />

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-16">
        <span class="text-red-200 font-semibold text-sm tracking-wider uppercase">Our Impact</span>
        <h2 class="mt-3 text-3xl sm:text-4xl font-bold text-white">Live Donation Statistics</h2>
        <p class="mt-4 text-red-100 max-w-2xl mx-auto text-lg">
          Tracking the collective impact of our donor community in real time.
        </p>
      </div>

      <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
        <div v-for="(stat, i) in stats" :key="stat.label" class="text-center bg-white/10 backdrop-blur-sm rounded-2xl p-8 border border-white/10">
          <div class="w-14 h-14 mx-auto bg-white/15 rounded-xl flex items-center justify-center mb-4">
            <svg v-if="stat.icon === 'blood'" class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
            </svg>
            <svg v-else-if="stat.icon === 'donors'" class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
            <svg v-else-if="stat.icon === 'hospital'" class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
            </svg>
            <svg v-else class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
          </div>
          <p class="text-4xl lg:text-5xl font-bold text-white">{{ counts[i] }}{{ stat.suffix }}</p>
          <p class="text-red-200 mt-2 text-sm">{{ stat.label }}</p>
        </div>
      </div>
    </div>
  </section>
</template>