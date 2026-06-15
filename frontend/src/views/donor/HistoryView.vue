<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useToastStore } from '@/stores/toast'
import { AppCard, AppBadge, AppTable } from '@/components/shared'
import * as donorApi from '@/api/donor'

const toast = useToastStore()
const donations = ref<any[]>([])
const loading = ref(true)

const columns = [
  { key: 'donation_date', label: 'Date' },
  { key: 'donation_center', label: 'Center' },
  { key: 'bags', label: 'Bags' },
  { key: 'blood_type', label: 'Blood Type' },
  { key: 'status', label: 'Status' },
]

onMounted(async () => {
  try {
    const { data } = await donorApi.getDonations()
    donations.value = data.data ?? data ?? []
  } catch {
    toast.add('Failed to load donation history', 'error')
  } finally {
    loading.value = false
  }
})

function statusVariant(status: string) {
  if (status === 'completed') return 'success'
  if (status === 'cancelled') return 'danger'
  return 'info'
}

function formatRow(row: any) {
  return {
    ...row,
    donation_center: row.donation_center?.name || '-',
    blood_type: row.blood_type || (row as any).donor?.blood_type || '-',
    status: row.status || 'completed',
  }
}
</script>

<template>
  <div>
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Donation History</h1>
    <AppCard>
      <AppTable
        :columns="columns"
        :data="donations.map(formatRow)"
        :loading="loading"
        empty-text="No donation history yet."
      />
    </AppCard>
  </div>
</template>
