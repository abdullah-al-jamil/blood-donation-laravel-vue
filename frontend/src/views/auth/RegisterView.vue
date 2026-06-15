<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { useToastStore } from '@/stores/toast'
import { AppButton, AppFormInput, AppSelect } from '@/components/shared'
import GuestLayout from '@/layouts/GuestLayout.vue'

const router = useRouter()
const auth = useAuthStore()
const toast = useToastStore()

const form = ref({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
  blood_type: '',
  phone: '',
  dob: '',
  address: '',
})
const loading = ref(false)
const errors = ref<Record<string, string>>({})

const bloodTypes = [
  { value: 'A+', label: 'A+' },
  { value: 'A-', label: 'A-' },
  { value: 'B+', label: 'B+' },
  { value: 'B-', label: 'B-' },
  { value: 'AB+', label: 'AB+' },
  { value: 'AB-', label: 'AB-' },
  { value: 'O+', label: 'O+' },
  { value: 'O-', label: 'O-' },
]

async function handleRegister() {
  loading.value = true
  errors.value = {}
  try {
    await auth.register(form.value)
    toast.add('Account created successfully!', 'success')
    router.push('/donor')
  } catch (e: any) {
    if (e.response?.data?.errors) {
      const errs: Record<string, string> = {}
      Object.entries(e.response.data.errors).forEach(([k, v]) => { errs[k] = (v as string[])[0] || '' })
      errors.value = errs
    } else {
      toast.add(e.response?.data?.message || 'Registration failed', 'error')
    }
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <GuestLayout>
    <h2 class="text-xl font-bold text-gray-900 mb-6">Create Account</h2>
    <form @submit.prevent="handleRegister">
      <AppFormInput v-model="form.name" label="Full Name" placeholder="John Doe" required :error="errors.name" />
      <AppFormInput v-model="form.email" label="Email" type="email" placeholder="your@email.com" required :error="errors.email" />
      <AppFormInput v-model="form.password" label="Password" type="password" placeholder="••••••••" required :error="errors.password" />
      <AppFormInput v-model="form.password_confirmation" label="Confirm Password" type="password" placeholder="••••••••" required :error="errors.password_confirmation" />
      <AppSelect v-model="form.blood_type" label="Blood Type" :options="bloodTypes" placeholder="Select blood type" required :error="errors.blood_type" />
      <AppFormInput v-model="form.phone" label="Phone" type="tel" placeholder="+1 (555) 000-0000" :error="errors.phone" />
      <AppFormInput v-model="form.dob" label="Date of Birth" type="date" :error="errors.dob" />
      <AppFormInput v-model="form.address" label="Address" placeholder="123 Main St, City" :error="errors.address" />
      <AppButton type="submit" :loading="loading" class="w-full mt-2">Register</AppButton>
    </form>
    <p class="text-center text-sm text-gray-600 mt-6">
      Already have an account?
      <router-link to="/login" class="text-red-600 hover:text-red-700 font-medium">Sign In</router-link>
    </p>
  </GuestLayout>
</template>
