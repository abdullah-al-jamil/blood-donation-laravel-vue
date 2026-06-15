<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useToastStore } from '@/stores/toast'
import { AppButton, AppFormInput, AppSelect, AppCard, AppBadge } from '@/components/shared'

const auth = useAuthStore()
const toast = useToastStore()

const form = ref({
  name: '',
  email: '',
  phone: '',
  address: '',
  dob: '',
  blood_type: '',
})
const loading = ref(false)
const errors = ref<Record<string, string>>({})

const bloodTypes = [
  { value: 'A+', label: 'A+' }, { value: 'A-', label: 'A-' },
  { value: 'B+', label: 'B+' }, { value: 'B-', label: 'B-' },
  { value: 'AB+', label: 'AB+' }, { value: 'AB-', label: 'AB-' },
  { value: 'O+', label: 'O+' }, { value: 'O-', label: 'O-' },
]

onMounted(() => {
  const u = auth.user
  if (u) {
    form.value.name = u.name || ''
    form.value.email = u.email || ''
    form.value.phone = u.phone || ''
    form.value.address = u.address || ''
    form.value.dob = u.dob || ''
    form.value.blood_type = u.blood_type || ''
  }
})

async function handleUpdate() {
  loading.value = true
  errors.value = {}
  try {
    await auth.updateProfile(form.value)
    toast.add('Profile updated successfully!', 'success')
  } catch (e: any) {
    if (e.response?.data?.errors) {
      const errs: Record<string, string> = {}
      Object.entries(e.response.data.errors).forEach(([k, v]) => { errs[k] = (v as string[])[0] || '' })
      errors.value = errs
    } else {
      toast.add(e.response?.data?.message || 'Update failed', 'error')
    }
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div>
    <h1 class="text-2xl font-bold text-gray-900 mb-6">My Profile</h1>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
      <div class="lg:col-span-2">
        <AppCard title="Edit Profile">
          <form @submit.prevent="handleUpdate">
            <AppFormInput v-model="form.name" label="Full Name" required :error="errors.name" />
            <AppFormInput v-model="form.email" label="Email" type="email" disabled :error="errors.email" />
            <AppFormInput v-model="form.phone" label="Phone" type="tel" :error="errors.phone" />
            <AppFormInput v-model="form.dob" label="Date of Birth" type="date" :error="errors.dob" />
            <AppFormInput v-model="form.address" label="Address" :error="errors.address" />
            <AppSelect v-model="form.blood_type" label="Blood Type" :options="bloodTypes" :error="errors.blood_type" />
            <AppButton type="submit" :loading="loading" class="mt-2">Update Profile</AppButton>
          </form>
        </AppCard>
      </div>
      <div>
        <AppCard title="Status">
          <div class="space-y-4">
            <div>
              <p class="text-sm text-gray-500">Eligibility</p>
              <AppBadge :variant="auth.user?.is_eligible ? 'success' : 'danger'" class="mt-1">
                {{ auth.user?.is_eligible ? 'Eligible to donate' : 'Not eligible' }}
              </AppBadge>
            </div>
            <div>
              <p class="text-sm text-gray-500">Blood Type</p>
              <p class="text-lg font-bold text-gray-900">{{ auth.user?.blood_type || 'Not set' }}</p>
            </div>
            <div>
              <p class="text-sm text-gray-500">Member Since</p>
              <p class="text-sm text-gray-900">{{ auth.user?.created_at ? new Date(auth.user.created_at).toLocaleDateString() : '-' }}</p>
            </div>
          </div>
        </AppCard>
      </div>
    </div>
  </div>
</template>
