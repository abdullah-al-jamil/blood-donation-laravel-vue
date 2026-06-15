<script setup lang="ts">
const benefits = [
  {
    title: 'Save Lives',
    description: 'One blood donation can save up to three lives. Your blood is essential for surgeries, trauma care, cancer treatments, and chronic illnesses.',
    color: 'red',
    icon: 'heart',
  },
  {
    title: 'Free Health Checkups',
    description: 'Every donation includes a mini health screening — blood pressure, hemoglobin, and pulse check. Stay informed about your health.',
    color: 'blue',
    icon: 'health',
  },
  {
    title: 'Community Impact',
    description: 'Be part of a life-saving community. Your donation inspires others and strengthens the healthcare system for everyone.',
    color: 'green',
    icon: 'community',
  },
  {
    title: 'Emergency Support',
    description: 'Blood is irreplaceable in emergencies. Natural disasters, accidents, and critical surgeries depend entirely on donated blood.',
    color: 'purple',
    icon: 'emergency',
  },
]

function getIconClasses(color: string) {
  switch (color) {
    case 'red': return 'bg-red-100 text-red-600'
    case 'blue': return 'bg-blue-100 text-blue-600'
    case 'green': return 'bg-green-100 text-green-600'
    case 'purple': return 'bg-purple-100 text-purple-600'
    default: return 'bg-gray-100 text-gray-600'
  }
}
</script>

<template>
  <section class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-16">
        <span class="text-red-600 font-semibold text-sm tracking-wider uppercase">Why Donate</span>
        <h2 class="mt-3 text-3xl sm:text-4xl font-bold text-gray-900">Reasons to Donate Blood</h2>
        <p class="mt-4 text-gray-600 max-w-2xl mx-auto text-lg">
          Your donation goes beyond saving lives. Discover the many benefits of becoming a regular blood donor.
        </p>
      </div>

      <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
        <div
          v-for="benefit in benefits"
          :key="benefit.title"
          class="group bg-gray-50 rounded-2xl p-6 border border-gray-100 hover:border-gray-200 hover:shadow-lg transition-all duration-300 hover:-translate-y-1"
        >
          <div :class="`w-14 h-14 rounded-xl flex items-center justify-center mb-5 ${getIconClasses(benefit.color)}`">
            <svg v-if="benefit.icon === 'heart'" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
            </svg>
            <svg v-else-if="benefit.icon === 'health'" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
            </svg>
            <svg v-else-if="benefit.icon === 'community'" class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
            </svg>
            <svg v-else class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z" />
            </svg>
          </div>

          <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ benefit.title }}</h3>
          <p class="text-gray-600 text-sm leading-relaxed">{{ benefit.description }}</p>
        </div>
      </div>
    </div>
  </section>
</template>