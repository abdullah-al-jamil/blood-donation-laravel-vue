<script setup lang="ts">
</script>

<template>
  <section class="relative min-h-screen flex items-center overflow-hidden bg-gradient-to-br from-red-700 via-red-600 to-red-800">
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top_right,_rgba(255,255,255,0.15)_0%,_transparent_60%)]" />
    <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_bottom_left,_rgba(0,0,0,0.2)_0%,_transparent_50%)]" />
    <div class="absolute top-20 left-10 w-72 h-72 bg-white/5 rounded-full blur-3xl" />
    <div class="absolute bottom-20 right-10 w-96 h-96 bg-white/5 rounded-full blur-3xl" />

    <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-24 pb-16 lg:pt-32 lg:pb-24">
      <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
        <div class="text-center lg:text-left">
          <div class="inline-flex items-center gap-2 bg-white/15 backdrop-blur-sm text-white text-sm font-medium px-4 py-1.5 rounded-full mb-6">
            <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse" />
            Saving lives every day
          </div>

          <h1 class="text-4xl sm:text-5xl lg:text-6xl xl:text-7xl font-extrabold text-white leading-tight">
            Donate Blood,
            <span class="text-red-200">Save Lives</span>
          </h1>

          <p class="mt-6 text-lg sm:text-xl text-red-100 max-w-xl mx-auto lg:mx-0 leading-relaxed">
            Every drop counts. Join thousands of heroes who save lives through blood donation.
            Your single donation can save up to three lives.
          </p>

          <div class="mt-10 flex flex-col sm:flex-row gap-4 justify-center lg:justify-start">
            <router-link
              to="/register"
              class="inline-flex items-center justify-center px-8 py-4 bg-white text-red-700 font-semibold rounded-xl shadow-xl hover:bg-red-50 transition-all duration-200 hover:shadow-2xl hover:-translate-y-0.5 text-lg"
            >
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z" />
              </svg>
              Become a Donor
            </router-link>
            <router-link
              to="/login"
              class="inline-flex items-center justify-center px-8 py-4 bg-white/10 text-white font-semibold rounded-xl border-2 border-white/30 hover:bg-white/20 transition-all duration-200 hover:-translate-y-0.5 text-lg backdrop-blur-sm"
            >
              <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
              </svg>
              Request Blood
            </router-link>
          </div>

          <div class="mt-14 grid grid-cols-1 sm:grid-cols-3 gap-4 max-w-2xl mx-auto lg:mx-0">
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/10">
              <p class="text-3xl font-bold text-white">10,000+</p>
              <p class="text-sm text-red-200 mt-1">Registered Donors</p>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/10">
              <p class="text-3xl font-bold text-white">5,000+</p>
              <p class="text-sm text-red-200 mt-1">Lives Saved</p>
            </div>
            <div class="bg-white/10 backdrop-blur-sm rounded-xl p-4 border border-white/10">
              <p class="text-3xl font-bold text-white">24/7</p>
              <p class="text-sm text-red-200 mt-1">Available</p>
            </div>
          </div>
        </div>

        <div class="hidden lg:flex items-center justify-center">
          <div class="relative w-full max-w-lg">
            <div class="absolute inset-0 bg-gradient-to-br from-red-400/20 to-transparent rounded-3xl blur-2xl" />
            <div class="relative bg-white/5 backdrop-blur-sm rounded-3xl p-8 border border-white/10">
              <svg class="w-full h-auto text-red-200" viewBox="0 0 400 350" fill="none" xmlns="http://www.w3.org/2000/svg">
                <circle cx="200" cy="160" r="120" fill="currentColor" fill-opacity="0.15" />
                <path d="M200 50C200 50 240 90 240 130C240 152.09 222.09 170 200 170C177.91 170 160 152.09 160 130C160 90 200 50 200 50Z" fill="currentColor" fill-opacity="0.4" />
                <rect x="150" y="180" width="100" height="8" rx="4" fill="currentColor" fill-opacity="0.3" />
                <rect x="170" y="195" width="60" height="6" rx="3" fill="currentColor" fill-opacity="0.2" />
                <circle cx="100" cy="250" r="15" fill="currentColor" fill-opacity="0.2" />
                <circle cx="300" cy="220" r="12" fill="currentColor" fill-opacity="0.2" />
                <circle cx="140" cy="290" r="10" fill="currentColor" fill-opacity="0.15" />
                <circle cx="260" cy="280" r="18" fill="currentColor" fill-opacity="0.15" />
                <line x1="160" y1="110" x2="240" y2="110" stroke="currentColor" stroke-opacity="0.5" stroke-width="2" stroke-linecap="round" />
                <line x1="180" y1="90" x2="220" y2="90" stroke="currentColor" stroke-opacity="0.5" stroke-width="2" stroke-linecap="round" />
                <line x1="190" y1="130" x2="210" y2="130" stroke="currentColor" stroke-opacity="0.5" stroke-width="2" stroke-linecap="round" />
              </svg>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="absolute bottom-0 left-0 right-0 h-16 bg-gradient-to-t from-gray-50 to-transparent" />
  </section>
</template>