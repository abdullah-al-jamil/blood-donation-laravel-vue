<script setup lang="ts">
const partners = [
  { name: 'City General Hospital', initials: 'CGH' },
  { name: 'Saint Mary\'s Medical', initials: 'SMM' },
  { name: 'National Blood Center', initials: 'NBC' },
  { name: 'University Health Network', initials: 'UHN' },
  { name: 'Red Cross Alliance', initials: 'RCA' },
  { name: 'Children\'s Hospital', initials: 'CH' },
]
</script>

<template>
  <section class="py-16 bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <p class="text-center text-sm font-semibold text-gray-500 tracking-wider uppercase mb-10">
        Partnered with Leading Healthcare Organizations
      </p>

      <div class="grid grid-cols-3 md:grid-cols-6 gap-8 items-center">
        <div
          v-for="partner in partners"
          :key="partner.name"
          class="flex items-center justify-center"
        >
          <div class="w-24 h-24 bg-gray-50 rounded-2xl border border-gray-100 flex items-center justify-center hover:border-red-200 hover:shadow-sm transition-all group">
            <span class="text-lg font-bold text-gray-400 group-hover:text-red-600 transition-colors">{{ partner.initials }}</span>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>