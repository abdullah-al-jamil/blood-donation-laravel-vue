<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { useToastStore } from '@/stores/toast'
import { AppButton, AppBadge, AppCard } from '@/components/shared'
import * as donorApi from '@/api/donor'
import * as publicApi from '@/api/public'

const router = useRouter()
const auth = useAuthStore()
const toast = useToastStore()

const stats = ref({ totalDonations: 0, lastDonation: null, isEligible: true })
const upcomingAppointments = ref<any[]>([])
const recentRequests = ref<any[]>([])
const loading = ref(true)

const formatDate = (value: string | null) => {
  if (!value) return ''
  const d = new Date(value)
  if (isNaN(d.getTime())) return String(value)
  return d.toLocaleString(undefined, { year: 'numeric', month: 'short', day: 'numeric', hour: 'numeric', minute: '2-digit' })
}

onMounted(async () => {
  try {
    const [donationsRes, aptsRes, requestsRes] = await Promise.all([
      donorApi.getDonations().catch(() => ({ data: { data: [] } })),
      donorApi.getAppointments().catch(() => ({ data: { data: [] } })),
      publicApi.getBloodRequests().catch(() => ({ data: { data: [] } })),
    ])
    const donations = donationsRes.data.data ?? donationsRes.data ?? []
    const appointments = aptsRes.data.data ?? aptsRes.data ?? []
    const bloodRequests = requestsRes.data.data ?? requestsRes.data ?? []
    console.log('Donations:', donations)
    const completed = donations.filter((d: any) => d.appointment?.status === 'completed')
    stats.value = {
      totalDonations: completed.length,
      lastDonation: completed.length > 0 ? completed[completed.length - 1].donation_date : null,
      isEligible: auth.user?.is_eligible ?? true,
    }

    upcomingAppointments.value = appointments
      .filter((a: any) => a.status === 'scheduled')
      .slice(0, 3)

    recentRequests.value = bloodRequests
      .filter((r: any) => r.blood_type === auth.user?.blood_type)
      .slice(0, 3)
  } catch {
    toast.add('Failed to load dashboard data', 'error')
  } finally {
    loading.value = false
  }
})
</script>

<template>
  <div>
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Dashboard</h1>

    <AppCard class="mb-6">
      <div class="flex items-center gap-4">
        <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center">
          <span class="text-2xl">👤</span>
        </div>
        <div>
          <h2 class="text-xl font-semibold text-gray-900">Welcome, {{ auth.user?.name }}</h2>
          <p class="text-sm text-gray-500">{{ auth.user?.email }} · {{ auth.user?.blood_type || 'Blood type not set' }}</p>
        </div>
      </div>
    </AppCard>

    <div v-if="loading" class="text-center py-8 text-gray-500">Loading...</div>

    <template v-else>
      <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <AppCard>
          <p class="text-sm text-gray-500">Total Donations</p>
          <p class="text-3xl font-bold text-gray-900 mt-1">{{ stats.totalDonations }}</p>
        </AppCard>
        <AppCard>
          <p class="text-sm text-gray-500">Last Donation</p>
          <p class="text-lg font-semibold text-gray-900 mt-1">{{ stats.lastDonation || 'Never' }}</p>
        </AppCard>
        <AppCard>
          <p class="text-sm text-gray-500">Eligibility</p>
          <div class="mt-2">
            <AppBadge :variant="stats.isEligible ? 'success' : 'danger'">
              {{ stats.isEligible ? 'Eligible' : 'Not Eligible' }}
            </AppBadge>
          </div>
        </AppCard>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <AppCard title="Upcoming Appointments">
          <div v-if="upcomingAppointments.length === 0" class="text-gray-500 text-sm py-4">No upcoming appointments.</div>
          <div v-for="apt in upcomingAppointments" :key="apt.id" class="flex items-center justify-between py-2 border-b last:border-0">
            <div>
              <p class="text-sm font-medium text-gray-900">{{ apt.donation_center?.name || 'Center' }}</p>
              <p class="text-xs text-gray-500">{{ formatDate(apt.appointment_date) }}<span v-if="apt.appointment_time"> at {{ apt.appointment_time }}</span></p>
            </div>
            <AppBadge variant="info">Scheduled</AppBadge>
          </div>
          <AppButton size="sm" class="mt-3" @click="router.push('/donor/appointments')">Book Appointment</AppButton>
        </AppCard>

        <AppCard :title="`Recent Blood Requests (${auth.user?.blood_type || 'N/A'})`">
          <div v-if="recentRequests.length === 0" class="text-gray-500 text-sm py-4">No matching requests.</div>
          <div v-for="req in recentRequests" :key="req.id" class="flex items-center justify-between py-2 border-b last:border-0">
            <div>
              <p class="text-sm font-medium text-gray-900">{{ req.patient_name || 'Patient' }}</p>
              <p class="text-xs text-gray-500">{{ req.hospital }} · {{ req.bags_needed }} bags</p>
            </div>
            <AppBadge :variant="req.urgency === 'critical' ? 'danger' : req.urgency === 'urgent' ? 'warning' : 'info'">
              {{ req.urgency }}
            </AppBadge>
          </div>
          <div class="flex gap-2 mt-3">
            <AppButton size="sm" variant="primary" @click="router.push('/donor/blood-requests')">Request Blood</AppButton>
            <AppButton size="sm" variant="ghost" @click="router.push('/donor/blood-requests')">See All Requests</AppButton>
          </div>
        </AppCard>
      </div>
    </template>
  </div>
</template>
