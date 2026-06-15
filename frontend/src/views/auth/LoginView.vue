<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { useToastStore } from '@/stores/toast'
import { AppButton, AppFormInput } from '@/components/shared'
import GuestLayout from '@/layouts/GuestLayout.vue'

const router = useRouter()
const auth = useAuthStore()
const toast = useToastStore()

const email = ref('')
const password = ref('')
const loading = ref(false)
const errors = ref<Record<string, string>>({})

async function handleLogin() {
  loading.value = true
  errors.value = {}
  try {
    await auth.login(email.value, password.value)
    toast.add('Logged in successfully!', 'success')
    if (auth.isAdmin) router.push('/admin')
    else router.push('/donor')
  } catch (e: any) {
    if (e.response?.data?.errors) {
      const errs: Record<string, string> = {}
      Object.entries(e.response.data.errors).forEach(([k, v]) => { errs[k] = (v as string[])[0] || '' })
      errors.value = errs
    } else {
      toast.add(e.response?.data?.message || 'Login failed', 'error')
    }
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <GuestLayout>
    <h2 class="text-xl font-bold text-gray-900 mb-6">Sign In</h2>
    <form @submit.prevent="handleLogin">
      <AppFormInput v-model="email" label="Email" type="email" placeholder="your@email.com" required :error="errors.email" />
      <AppFormInput v-model="password" label="Password" type="password" placeholder="••••••••" required :error="errors.password" />
      <AppButton type="submit" :loading="loading" class="w-full mt-2">Sign In</AppButton>
    </form>
    <p class="text-center text-sm text-gray-600 mt-6">
      Don't have an account?
      <router-link to="/register" class="text-red-600 hover:text-red-700 font-medium">Register</router-link>
    </p>
  </GuestLayout>
</template>
