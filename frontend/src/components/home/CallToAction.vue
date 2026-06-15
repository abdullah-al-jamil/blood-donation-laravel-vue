<script setup lang="ts">
</script>

<template>
  <section class="py-24 bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 relative overflow-hidden">
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_center,_rgba(220,38,38,0.15)_0%,_transparent_60%)]" />
    <div class="absolute top-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-red-500/50 to-transparent" />

    <div class="relative max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
      <div class="inline-flex items-center justify-center w-20 h-20 bg-red-600/20 rounded-full mb-8 border border-red-500/30">
        <svg class="w-10 h-10 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
        </svg>
      </div>

      <h2 class="text-3xl sm:text-4xl lg:text-5xl font-bold text-white leading-tight">
        Your One Donation Can Save
        <span class="text-red-400">Multiple Lives</span>
      </h2>

      <p class="mt-6 text-lg text-gray-300 max-w-2xl mx-auto leading-relaxed">
        Every eight minutes, someone needs a blood transfusion. Your donation is more than a medical procedure — it's a lifeline. Join our community of heroes today.
      </p>

      <div class="mt-12 flex flex-col sm:flex-row gap-4 justify-center">
        <router-link
          to="/register"
          class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-red-600 text-white font-semibold rounded-xl hover:bg-red-700 transition-all duration-200 shadow-xl hover:shadow-2xl hover:-translate-y-0.5 text-lg"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
          </svg>
          Register Now
        </router-link>
        <router-link
          to="/login"
          class="inline-flex items-center justify-center gap-2 px-8 py-4 bg-white/10 text-white font-semibold rounded-xl border-2 border-white/20 hover:bg-white/20 transition-all duration-200 hover:-translate-y-0.5 text-lg backdrop-blur-sm"
        >
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
          Find Blood
        </router-link>
      </div>
    </div>
  </section>
</template>