<script setup lang="ts">
</script>

<template>
  <div class="min-h-screen bg-gradient-to-br from-red-50 to-red-100 flex items-center justify-center px-4 py-12">
    <div class="w-full max-w-md">
      <div class="text-center mb-8">
        <router-link to="/" class="inline-flex items-center justify-center w-16 h-16 bg-red-600 rounded-full mb-4 hover:bg-red-700 transition-colors">
          <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
          </svg>
        </router-link>
        <router-link to="/" class="hover:opacity-80">
          <h1 class="text-2xl font-bold text-gray-900">LifeDrop</h1>
        </router-link>
        <p class="text-sm text-gray-600 mt-1">Save lives, donate blood</p>
      </div>
      <div class="bg-white rounded-xl shadow-lg p-8">
        <slot />
      </div>
    </div>
  </div>
</template>
