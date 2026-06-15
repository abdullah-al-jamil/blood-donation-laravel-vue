<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useToastStore } from '@/stores/toast'
import { AppCard, AppTable, AppBadge, AppButton, AppSelect } from '@/components/shared'
import * as adminApi from '@/api/admin'

const toast = useToastStore()
const appointments = ref<any[]>([])
const loading = ref(true)

const columns = [
  { key: 'donor', label: 'Donor' },
  { key: 'center', label: 'Center' },
  { key: 'appointment_date', label: 'Date' },
  { key: 'appointment_time', label: 'Time' },
  { key: 'status', label: 'Status' },
  { key: 'actions', label: 'Actions' },
]

async function fetchAppointments() {
  loading.value = true
  try {
    const { data } = await adminApi.getAllAppointments()
    appointments.value = data.data ?? data ?? []
  } catch {
    toast.add('Failed to load appointments', 'error')
  } finally {
    loading.value = false
  }
}

async function updateStatus(id: number, status: string) {
  try {
    await adminApi.updateAppointment(id, { status })
    toast.add(`Appointment ${status}`, 'success')
    await fetchAppointments()
  } catch {
    toast.add('Failed to update', 'error')
  }
}

function statusVariant(s: string) {
  if (s === 'completed') return 'success'
  if (s === 'cancelled') return 'danger'
  return 'info'
}

function formatRow(row: any) {
  return {
    ...row,
    donor: row.user?.name || row.donor?.name || '-',
    center: row.donation_center?.name || '-',
    actions: '',
  }
}

onMounted(fetchAppointments)
</script>

<template>
  <div>
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Appointments</h1>
    <AppCard>
      <AppTable
        :columns="columns"
        :data="appointments.map(formatRow)"
        :loading="loading"
        empty-text="No appointments found."
      >
        <template #cell-status="{ row }">
          <AppBadge :variant="statusVariant(row.status)">{{ row.status }}</AppBadge>
        </template>
        <template #cell-actions="{ row }">
          <div class="flex gap-2">
            <AppButton
              v-if="row.status === 'scheduled'"
              size="sm"
              variant="primary"
              @click="updateStatus(row.id, 'completed')"
            >
              Complete
            </AppButton>
            <AppButton
              v-if="row.status === 'scheduled'"
              size="sm"
              variant="danger"
              @click="updateStatus(row.id, 'cancelled')"
            >
              Cancel
            </AppButton>
          </div>
        </template>
      </AppTable>
    </AppCard>
  </div>
</template>
