<script setup lang="ts">
import { ref, onMounted, onUnmounted } from 'vue'

const testimonials = [
  {
    name: 'Sarah Johnson',
    role: 'Blood Donor',
    quote: 'Donating blood is the simplest way I\'ve found to make a real difference. Knowing that my single donation can save up to three lives keeps me coming back every three months.',
    rating: 5,
    initials: 'SJ',
    color: 'bg-red-600',
  },
  {
    name: 'Michael Chen',
    role: 'Recipient',
    quote: 'After a serious accident, I needed 6 units of blood. Complete strangers came together to save my life. I\'m alive today because people chose to donate. Thank you will never be enough.',
    rating: 5,
    initials: 'MC',
    color: 'bg-blue-600',
  },
  {
    name: 'Dr. Emily Rodriguez',
    role: 'Medical Director',
    quote: 'As a hematologist, I see firsthand how blood donations transform patient outcomes. From emergency trauma to cancer care, donated blood is the invisible lifeline of modern medicine.',
    rating: 5,
    initials: 'ER',
    color: 'bg-green-600',
  },
  {
    name: 'James Okafor',
    role: 'Regular Donor',
    quote: 'I started donating in college and haven\'t stopped in 15 years. It\'s become part of who I am. The LifeDrop platform makes it so easy to find centers and track my impact.',
    rating: 4,
    initials: 'JO',
    color: 'bg-purple-600',
  },
]

const current = ref(0)
let interval: ReturnType<typeof setInterval> | null = null

function startAutoRotate() {
  interval = setInterval(() => {
    current.value = (current.value + 1) % testimonials.length
  }, 5000)
}

function stopAutoRotate() {
  if (interval) {
    clearInterval(interval)
    interval = null
  }
}

function goTo(index: number) {
  stopAutoRotate()
  current.value = index
  startAutoRotate()
}

function prev() {
  stopAutoRotate()
  current.value = current.value === 0 ? testimonials.length - 1 : current.value - 1
  startAutoRotate()
}

function next() {
  stopAutoRotate()
  current.value = (current.value + 1) % testimonials.length
  startAutoRotate()
}

onMounted(() => startAutoRotate())
onUnmounted(() => stopAutoRotate())
</script>

<template>
  <section class="py-20 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
      <div class="text-center mb-16">
        <span class="text-red-600 font-semibold text-sm tracking-wider uppercase">Success Stories</span>
        <h2 class="mt-3 text-3xl sm:text-4xl font-bold text-gray-900">Real Stories, Real Impact</h2>
        <p class="mt-4 text-gray-600 max-w-2xl mx-auto text-lg">
          Hear from donors and recipients whose lives have been touched by blood donation.
        </p>
      </div>

      <div class="relative max-w-3xl mx-auto" @mouseenter="stopAutoRotate" @mouseleave="startAutoRotate">
        <div class="overflow-hidden">
          <div
            class="flex transition-transform duration-500 ease-in-out"
            :style="{ transform: `translateX(-${current * 100}%)` }"
          >
            <div
              v-for="(t, i) in testimonials"
              :key="i"
              class="w-full flex-shrink-0 px-2"
            >
              <div class="bg-white rounded-2xl p-8 shadow-md border border-gray-100 text-center">
                <div :class="`w-20 h-20 mx-auto rounded-full flex items-center justify-center text-white text-2xl font-bold ${t.color} mb-4`">
                  {{ t.initials }}
                </div>
                <div class="flex justify-center gap-1 mb-4">
                  <svg v-for="s in 5" :key="s" class="w-5 h-5" :class="s <= t.rating ? 'text-yellow-400' : 'text-gray-200'" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                  </svg>
                </div>
                <blockquote class="text-gray-700 text-lg leading-relaxed italic mb-6">
                  "{{ t.quote }}"
                </blockquote>
                <div>
                  <p class="font-semibold text-gray-900">{{ t.name }}</p>
                  <p class="text-sm text-gray-500">{{ t.role }}</p>
                </div>
              </div>
            </div>
          </div>
        </div>

        <button
          @click="prev"
          class="absolute left-0 top-1/2 -translate-y-1/2 -translate-x-4 lg:-translate-x-12 w-10 h-10 bg-white rounded-full shadow-md flex items-center justify-center hover:bg-gray-50 transition-colors border border-gray-200"
        >
          <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
          </svg>
        </button>
        <button
          @click="next"
          class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-4 lg:translate-x-12 w-10 h-10 bg-white rounded-full shadow-md flex items-center justify-center hover:bg-gray-50 transition-colors border border-gray-200"
        >
          <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
          </svg>
        </button>
      </div>

      <div class="flex justify-center gap-2 mt-8">
        <button
          v-for="(_, i) in testimonials"
          :key="i"
          @click="goTo(i)"
          class="w-2.5 h-2.5 rounded-full transition-all"
          :class="i === current ? 'bg-red-600 w-8' : 'bg-gray-300 hover:bg-gray-400'"
        />
      </div>
    </div>
  </section>
</template>