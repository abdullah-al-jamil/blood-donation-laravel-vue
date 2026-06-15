<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useToastStore } from '@/stores/toast'
import { AppCard, AppBadge } from '@/components/shared'
import * as adminApi from '@/api/admin'

const toast = useToastStore()
const loading = ref(true)
const stats = ref({ total_donors: 0, total_donations: 0, total_bags: 0, pending_requests: 0 })
const inventorySummary = ref<any[]>([])
const recentDonations = ref<any[]>([])
const pendingRequests = ref<any[]>([])

onMounted(async () => {
  try {
    const { data } = await adminApi.getDashboard()
    const d = data.data ?? data
    stats.value = {
      total_donors: d.total_donors ?? 0,
      total_donations: d.total_donations ?? 0,
      total_bags: d.total_bags ?? 0,
      pending_requests: d.pending_requests ?? 0,
    }
    inventorySummary.value = d.inventory_summary ?? []
    recentDonations.value = (d.recent_donations ?? []).slice(0, 5)
    pendingRequests.value = (d.pending_requests_list ?? []).slice(0, 5)
  } catch {
    toast.add('Failed to load dashboard', 'error')
  } finally {
    loading.value = false
  }
})

function urgencyVariant(u: string) {
  if (u === 'critical') return 'danger'
  if (u === 'urgent') return 'warning'
  return 'info'
}
</script>

<template>
  <div>
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Admin Dashboard</h1>

    <div v-if="loading" class="text-center py-8 text-gray-500">Loading...</div>

    <template v-else>
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <AppCard>
          <p class="text-sm text-gray-500">Total Donors</p>
          <p class="text-3xl font-bold text-gray-900 mt-1">{{ stats.total_donors }}</p>
        </AppCard>
        <AppCard>
          <p class="text-sm text-gray-500">Total Donations</p>
          <p class="text-3xl font-bold text-gray-900 mt-1">{{ stats.total_donations }}</p>
        </AppCard>
        <AppCard>
          <p class="text-sm text-gray-500">Total Bags</p>
          <p class="text-3xl font-bold text-gray-900 mt-1">{{ stats.total_bags }}</p>
        </AppCard>
        <AppCard>
          <p class="text-sm text-gray-500">Pending Requests</p>
          <p class="text-3xl font-bold text-gray-900 mt-1">{{ stats.pending_requests }}</p>
        </AppCard>
      </div>

      <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <AppCard title="Inventory by Blood Type">
          <div v-if="inventorySummary.length === 0" class="text-gray-500 text-sm py-4">No inventory data.</div>
          <div v-for="item in inventorySummary" :key="item.blood_type" class="mb-3">
            <div class="flex items-center justify-between text-sm mb-1">
              <span class="font-medium text-gray-700">{{ item.blood_type }}</span>
              <span class="text-gray-600">{{ item.bags }} bags</span>
            </div>
            <div class="w-full bg-gray-200 rounded-full h-2.5">
              <div
                class="h-2.5 rounded-full transition-all"
                :class="item.bags < 5 ? 'bg-red-500' : 'bg-green-500'"
                :style="{ width: Math.min(100, (item.bags / 50) * 100) + '%' }"
              />
            </div>
          </div>
        </AppCard>

        <AppCard title="Pending Blood Requests">
          <div v-if="pendingRequests.length === 0" class="text-gray-500 text-sm py-4">No pending requests.</div>
          <div
            v-for="req in pendingRequests"
            :key="req.id"
            class="flex items-center justify-between py-2 border-b last:border-0"
          >
            <div>
              <p class="text-sm font-medium text-gray-900">{{ req.patient_name || 'Patient' }}</p>
              <p class="text-xs text-gray-500">{{ req.blood_type }} · {{ req.bags_needed }} bags</p>
            </div>
            <AppBadge :variant="urgencyVariant(req.urgency)">{{ req.urgency }}</AppBadge>
          </div>
        </AppCard>
      </div>

      <AppCard title="Recent Donations">
        <div v-if="recentDonations.length === 0" class="text-gray-500 text-sm py-4">No recent donations.</div>
        <div
          v-for="don in recentDonations"
          :key="don.id"
          class="flex items-center justify-between py-2 border-b last:border-0"
        >
          <div>
            <p class="text-sm font-medium text-gray-900">{{ don.donor?.name || 'Donor' }}</p>
            <p class="text-xs text-gray-500">{{ don.donation_center?.name }} · {{ don.donation_date }}</p>
          </div>
          <span class="text-sm font-medium text-gray-700">{{ don.bags }} bags</span>
        </div>
      </AppCard>
    </template>
  </div>
</template>
