<script setup lang="ts">
import { ref, computed } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { useAuthStore } from '@/stores/auth'

const router = useRouter()
const route = useRoute()
const auth = useAuthStore()
const sidebarOpen = ref(false)

const navItems = [
  { name: 'Dashboard', path: '/donor', icon: '🏠' },
  { name: 'My Appointments', path: '/donor/appointments', icon: '📅' },
  { name: 'Donation History', path: '/donor/donation-history', icon: '🕐' },
  { name: 'My Profile', path: '/donor/profile', icon: '👤' },
]

async function handleLogout() {
  await auth.logout()
  router.push('/login')
}
</script>

<template>
  <div class="min-h-screen bg-gray-50 flex">
    <aside
      class="fixed inset-y-0 left-0 z-40 w-64 bg-white border-r border-gray-200 transform transition-transform duration-200 lg:translate-x-0 lg:static lg:inset-auto"
      :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    >
      <div class="flex items-center gap-3 px-6 py-5 border-b border-gray-200">
        <div class="w-10 h-10 bg-red-600 rounded-full flex items-center justify-center">
          <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
          </svg>
        </div>
        <div>
          <p class="font-semibold text-gray-900">Blood Donation</p>
          <p class="text-xs text-gray-500">Donor Portal</p>
        </div>
      </div>
      <nav class="px-4 py-4 space-y-1">
        <router-link
          v-for="item in navItems"
          :key="item.path"
          :to="item.path"
          @click="sidebarOpen = false"
          class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors"
          :class="route.path === item.path ? 'bg-red-50 text-red-700' : 'text-gray-600 hover:bg-gray-100'"
        >
          <span>{{ item.icon }}</span>
          {{ item.name }}
        </router-link>
      </nav>
    </aside>

    <div v-if="sidebarOpen" class="fixed inset-0 z-30 bg-black/50 lg:hidden" @click="sidebarOpen = false" />

    <div class="flex-1 flex flex-col min-h-screen">
      <header class="bg-white border-b border-gray-200 px-6 py-3 flex items-center justify-between">
        <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-lg hover:bg-gray-100">
          <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>
        <div class="flex-1" />
        <div class="flex items-center gap-4">
          <span class="text-sm text-gray-700">{{ auth.user?.name }}</span>
          <button @click="handleLogout" class="text-sm text-red-600 hover:text-red-700 font-medium">Logout</button>
        </div>
      </header>
      <main class="flex-1 p-6">
        <router-view />
      </main>
    </div>
  </div>
</template>
