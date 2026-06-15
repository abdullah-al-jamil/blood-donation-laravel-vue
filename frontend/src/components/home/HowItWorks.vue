<script setup lang="ts">
const steps = [
  {
    number: '01',
    title: 'Register as a Donor',
    description: 'Sign up in minutes and complete your health profile. Join our community of life-savers ready to make a difference.',
    icon: 'register',
  },
  {
    number: '02',
    title: 'Find or Receive Requests',
    description: 'Get notified about blood requests matching your type. Hospitals and patients in need are connected directly to you.',
    icon: 'find',
  },
  {
    number: '03',
    title: 'Donate and Save Lives',
    description: 'Visit a donation center, give blood, and become a hero. Each donation can save up to three lives.',
    icon: 'donate',
  },
]
</script>

<template>
  <section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-16">
        <span class="text-red-600 font-semibold text-sm tracking-wider uppercase">How It Works</span>
        <h2 class="mt-3 text-3xl sm:text-4xl font-bold text-gray-900">Three Simple Steps</h2>
        <p class="mt-4 text-gray-600 max-w-2xl mx-auto text-lg">
          Becoming a blood donor is easier than you think. Here's how you can make a difference.
        </p>
      </div>

      <div class="grid md:grid-cols-3 gap-8 lg:gap-12">
        <div v-for="(step, index) in steps" :key="step.number" class="relative group">
          <div v-if="index < steps.length - 1" class="hidden md:block absolute top-16 left-[60%] w-[80%] h-0.5 border-t-2 border-dashed border-gray-200" />

          <div class="flex flex-col items-center text-center">
            <div class="relative mb-6">
              <div class="w-16 h-16 bg-red-100 rounded-2xl flex items-center justify-center group-hover:bg-red-600 transition-colors duration-300">
                <svg v-if="step.icon === 'register'" class="w-8 h-8 text-red-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <svg v-else-if="step.icon === 'find'" class="w-8 h-8 text-red-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
                <svg v-else class="w-8 h-8 text-red-600 group-hover:text-white transition-colors duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                </svg>
              </div>
              <span class="absolute -top-2 -right-2 w-8 h-8 bg-red-600 text-white rounded-full flex items-center justify-center text-sm font-bold shadow-lg">
                {{ step.number }}
              </span>
            </div>

            <h3 class="text-xl font-semibold text-gray-900 mb-3">{{ step.title }}</h3>
            <p class="text-gray-600 leading-relaxed">{{ step.description }}</p>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>