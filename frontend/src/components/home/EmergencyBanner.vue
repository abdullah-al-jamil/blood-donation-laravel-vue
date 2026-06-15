<script setup lang="ts">
const bloodGroups = ['A+', 'A-', 'B+', 'B-', 'O+', 'O-', 'AB+', 'AB-']

function getBadgeClass(type: string) {
  const base = 'inline-flex items-center justify-center w-10 h-10 rounded-lg text-xs font-bold transition-colors'
  const rare = type.includes('-') || type === 'AB+'
  return rare
    ? `${base} bg-red-100 text-red-700 border border-red-200`
    : `${base} bg-orange-100 text-orange-700 border border-orange-200`
}
</script>

<template>
  <section id="emergency" class="bg-gradient-to-r from-red-600 to-red-700 py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex flex-col lg:flex-row items-center justify-between gap-4">
        <div class="flex items-center gap-4">
          <div class="bg-white/20 rounded-full p-2.5 flex-shrink-0">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z" />
            </svg>
          </div>
          <div>
            <p class="text-white font-semibold text-lg">Emergency Blood Request</p>
            <p class="text-red-100 text-sm">Urgent need for all blood types</p>
          </div>
        </div>

        <div class="flex flex-wrap items-center gap-2">
          <span v-for="group in bloodGroups" :key="group" :class="getBadgeClass(group)">
            {{ group }}
          </span>
        </div>

        <router-link
          to="/login"
          class="inline-flex items-center gap-2 px-6 py-2.5 bg-white text-red-700 font-semibold rounded-lg hover:bg-red-50 transition-colors shadow-lg text-sm whitespace-nowrap"
        >
          View Emergency Requests
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
          </svg>
        </router-link>
      </div>
    </div>
  </section>
</template>