<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useToastStore } from '@/stores/toast'
import { AppCard, AppTable, AppBadge, AppButton, AppModal, AppFormInput, AppSelect } from '@/components/shared'
import * as adminApi from '@/api/admin'

const toast = useToastStore()
const donations = ref<any[]>([])
const donors = ref<any[]>([])
const centers = ref<any[]>([])
const loading = ref(true)
const showModal = ref(false)

const form = ref({ donor_id: '', donation_center_id: '', donation_date: '', bags: 1, blood_type: '' })
const submitting = ref(false)

const bloodTypes = [
  { value: 'A+', label: 'A+' }, { value: 'A-', label: 'A-' },
  { value: 'B+', label: 'B+' }, { value: 'B-', label: 'B-' },
  { value: 'AB+', label: 'AB+' }, { value: 'AB-', label: 'AB-' },
  { value: 'O+', label: 'O+' }, { value: 'O-', label: 'O-' },
]

const columns = [
  { key: 'donor', label: 'Donor' },
  { key: 'center', label: 'Center' },
  { key: 'donation_date', label: 'Date' },
  { key: 'bags', label: 'Bags' },
  { key: 'blood_type', label: 'Blood Type' },
]

async function fetchData() {
  loading.value = true
  try {
    const [donationsRes, donorsRes, centersRes] = await Promise.all([
      adminApi.getDonations(),
      adminApi.getDonors().catch(() => ({ data: { data: [] } })),
      adminApi.getCenters().catch(() => ({ data: { data: [] } })),
    ])
    donations.value = donationsRes.data.data ?? donationsRes.data ?? []
    donors.value = donorsRes.data.data ?? donorsRes.data ?? []
    centers.value = centersRes.data.data ?? centersRes.data ?? []
  } catch {
    toast.add('Failed to load data', 'error')
  } finally {
    loading.value = false
  }
}

async function handleCreate() {
  submitting.value = true
  try {
    await adminApi.createDonation(form.value)
    toast.add('Donation recorded', 'success')
    showModal.value = false
    form.value = { donor_id: '', donation_center_id: '', donation_date: '', bags: 1, blood_type: '' }
    await fetchData()
  } catch (e: any) {
    toast.add(e.response?.data?.message || 'Failed', 'error')
  } finally {
    submitting.value = false
  }
}

function formatRow(row: any) {
  return {
    ...row,
    donor: row.user?.name || row.donor?.name || '-',
    center: row.donation_center?.name || '-',
    blood_type: row.blood_type || row.donor?.blood_type || '-',
  }
}

onMounted(fetchData)
</script>

<template>
  <div>
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold text-gray-900">Donations</h1>
      <AppButton @click="showModal = true">Record Donation</AppButton>
    </div>

    <AppCard>
      <AppTable
        :columns="columns"
        :data="donations.map(formatRow)"
        :loading="loading"
        empty-text="No donations recorded."
      />
    </AppCard>

    <AppModal :visible="showModal" title="Record Donation" @close="showModal = false">
      <form @submit.prevent="handleCreate" id="modal-form">
        <AppSelect
          v-model="form.donor_id"
          label="Donor"
          :options="donors.map((d: any) => ({ value: d.id, label: d.name }))"
          placeholder="Select donor"
          required
        />
        <AppSelect
          v-model="form.donation_center_id"
          label="Center"
          :options="centers.map((c: any) => ({ value: c.id, label: c.name }))"
          placeholder="Select center"
          required
        />
        <AppFormInput v-model="form.donation_date" label="Date" type="date" required />
        <AppFormInput v-model="form.bags" label="Bags" type="text" required />
        <AppSelect v-model="form.blood_type" label="Blood Type" :options="bloodTypes" required />
      </form>
      <template #footer>
        <AppButton variant="secondary" @click="showModal = false">Cancel</AppButton>
        <AppButton type="submit" form="modal-form" :loading="submitting">Record</AppButton>
      </template>
    </AppModal>
  </div>
</template>
