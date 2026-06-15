<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'

const scrolled = ref(false)
let ticking = false

function onScroll() {
  if (!ticking) {
    requestAnimationFrame(() => {
      scrolled.value = window.scrollY > 20
      ticking = false
    })
    ticking = true
  }
}

onMounted(() => window.addEventListener('scroll', onScroll, { passive: true }))
onUnmounted(() => window.removeEventListener('scroll', onScroll))
</script>

<template>
  <nav
    class="fixed top-0 left-0 right-0 z-50 transition-all duration-300"
    :class="scrolled ? 'bg-white/95 backdrop-blur-md shadow-sm' : 'bg-transparent'"
  >
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="flex items-center justify-between h-16 lg:h-20">
        <router-link to="/" class="flex items-center gap-2.5">
          <div class="w-9 h-9 bg-red-600 rounded-full flex items-center justify-center flex-shrink-0">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
            </svg>
          </div>
          <span class="text-xl font-bold" :class="scrolled ? 'text-gray-900' : 'text-white'">LifeDrop</span>
        </router-link>

        <div class="hidden md:flex items-center gap-8">
          <router-link to="/" class="text-sm font-medium transition-colors" :class="scrolled ? 'text-gray-700 hover:text-red-600' : 'text-white/90 hover:text-white'">Home</router-link>
          <a href="#about" class="text-sm font-medium transition-colors" :class="scrolled ? 'text-gray-700 hover:text-red-600' : 'text-white/90 hover:text-white'">About</a>
          <a href="#emergency" class="text-sm font-medium transition-colors" :class="scrolled ? 'text-gray-700 hover:text-red-600' : 'text-white/90 hover:text-white'">Emergency</a>
          <a href="#contact" class="text-sm font-medium transition-colors" :class="scrolled ? 'text-gray-700 hover:text-red-600' : 'text-white/90 hover:text-white'">Contact</a>
        </div>

        <div class="flex items-center gap-3">
          <router-link
            to="/login"
            class="text-sm font-medium px-4 py-2 rounded-lg transition-colors"
            :class="scrolled ? 'text-gray-700 hover:bg-gray-100' : 'text-white/90 hover:bg-white/10'"
          >
            Sign In
          </router-link>
          <router-link
            to="/register"
            class="text-sm font-medium px-4 py-2 rounded-lg bg-red-600 text-white hover:bg-red-700 transition-colors shadow-sm"
          >
            Register
          </router-link>
        </div>
      </div>
    </div>
  </nav>
</template>