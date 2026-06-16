<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useAppointmentsStore } from '@/stores/appointments'
import { useCentersStore } from '@/stores/centers'
import { useToastStore } from '@/stores/toast'
import { AppButton, AppBadge, AppCard, AppModal, AppSelect, AppFormInput, AppTable } from '@/components/shared'

const appointmentsStore = useAppointmentsStore()
const centersStore = useCentersStore()
const toast = useToastStore()

const showModal = ref(false)
const form = ref({ donation_center_id: '', appointment_date: '', appointment_time: '' })
const submitting = ref(false)

const columns = [
  { key: 'donation_center', label: 'Center' },
  { key: 'appointment_date', label: 'Date' },
  { key: 'appointment_time', label: 'Time' },
  { key: 'status', label: 'Status' },
  { key: 'actions', label: 'Actions' },
]

onMounted(() => {
  appointmentsStore.fetchAppointments()
  centersStore.fetchCenters()
})

async function handleCreate() {
  submitting.value = true
  try {
    const payload = {
      center_id: form.value.donation_center_id,
      appointment_date: `${form.value.appointment_date} ${form.value.appointment_time}`,
    }
    await appointmentsStore.createAppointment(payload)
    toast.add('Appointment booked!', 'success')
    showModal.value = false
    form.value = { donation_center_id: '', appointment_date: '', appointment_time: '' }
  } catch (e: any) {
    toast.add(e.response?.data?.message || 'Failed to book appointment', 'error')
  } finally {
    submitting.value = false
  }
}

async function handleCancel(id: number) {
  if (!confirm('Cancel this appointment?')) return
  try {
    await appointmentsStore.cancelAppointment(id)
    toast.add('Appointment cancelled', 'info')
  } catch {
    toast.add('Failed to cancel', 'error')
  }
}

function statusVariant(status: string) {
  if (status === 'completed') return 'success'
  if (status === 'cancelled') return 'danger'
  return 'info'
}

function formatRow(row: any) {
  return {
    ...row,
    donation_center: row.donation_center?.name || '-',
    appointment_date: row.appointment_date?.split('T')[0] || '-',
    appointment_time: row.appointment_date?.split('T')[1]?.slice(0, 5) || '-',
    status: row.status || 'scheduled',
  }
}
</script>

<template>
  <div>
    <div class="flex items-center justify-between gap-2 mb-6 flex-wrap">
      <h1 class="text-2xl font-bold text-gray-900">My Appointments</h1>
      <AppButton @click="showModal = true">Book Appointment</AppButton>
    </div>

    <AppCard>
      <AppTable
        :columns="columns"
        :data="appointmentsStore.list.map(formatRow)"
        :loading="appointmentsStore.loading"
        empty-text="No appointments found."
      >
        <template #cell-status="{ row }">
          <AppBadge :variant="statusVariant(row.status)">{{ row.status }}</AppBadge>
        </template>
        <template #cell-actions="{ row }">
          <AppButton
            v-if="row.status === 'scheduled'"
            size="sm"
            variant="danger"
            @click="handleCancel(row.id)"
          >
            Cancel
          </AppButton>
          <span v-else>-</span>
        </template>
      </AppTable>
    </AppCard>

    <AppModal :visible="showModal" title="Book Appointment" @close="showModal = false">
      <form @submit.prevent="handleCreate" id="modal-form">
        <AppSelect
          v-model="form.donation_center_id"
          label="Donation Center"
          :options="centersStore.list.map((c: any) => ({ value: c.id, label: c.name }))"
          placeholder="Select center"
          required
        />
        <AppFormInput v-model="form.appointment_date" label="Date" type="date" required />
        <AppFormInput v-model="form.appointment_time" label="Time" type="time" required />
      </form>
      <template #footer>
        <AppButton variant="secondary" @click="showModal = false">Cancel</AppButton>
        <AppButton type="submit" form="modal-form" :loading="submitting">Book</AppButton>
      </template>
    </AppModal>
  </div>
</template>
