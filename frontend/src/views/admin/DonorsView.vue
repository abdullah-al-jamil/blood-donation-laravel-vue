<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useToastStore } from '@/stores/toast'
import { AppCard, AppTable, AppBadge, AppFormInput, AppButton, AppPagination } from '@/components/shared'
import * as adminApi from '@/api/admin'

const toast = useToastStore()
const donors = ref<any[]>([])
const loading = ref(true)
const search = ref('')
const currentPage = ref(1)
const lastPage = ref(1)
const total = ref(0)

const columns = [
  { key: 'name', label: 'Name', sortable: true },
  { key: 'email', label: 'Email' },
  { key: 'blood_type', label: 'Blood Type' },
  { key: 'phone', label: 'Phone' },
  { key: 'is_eligible', label: 'Eligibility' },
  { key: 'last_donation_at', label: 'Last Donation' },
  { key: 'actions', label: 'Actions' },
]

async function fetchDonors() {
  loading.value = true
  try {
    const { data } = await adminApi.getDonors()
    const d = data.data ?? data ?? []
    donors.value = Array.isArray(d) ? d : []
  } catch {
    toast.add('Failed to load donors', 'error')
  } finally {
    loading.value = false
  }
}

async function toggleEligibility(id: number) {
  try {
    await adminApi.toggleDonorEligibility(id)
    toast.add('Eligibility toggled', 'success')
    await fetchDonors()
  } catch {
    toast.add('Failed to toggle eligibility', 'error')
  }
}

function formatRow(row: any) {
  return {
    ...row,
    blood_type: row.blood_type || '-',
    phone: row.phone || '-',
    is_eligible: row.is_eligible ? 'Eligible' : 'Not Eligible',
    last_donation_at: row.last_donation_at || 'Never',
    actions: '',
  }
}

onMounted(fetchDonors)
</script>

<template>
  <div>
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Manage Donors</h1>
    <AppCard>
      <div class="mb-4">
        <AppFormInput v-model="search" placeholder="Search donors..." />
      </div>
      <AppTable
        :columns="columns"
        :data="donors.map(formatRow)"
        :loading="loading"
        empty-text="No donors found."
      >
        <template #cell-actions="{ row }">
          <AppButton size="sm" :variant="row.is_eligible === 'Eligible' ? 'danger' : 'secondary'" @click="toggleEligibility(row.id)">
            {{ row.is_eligible === 'Eligible' ? 'Mark Ineligible' : 'Mark Eligible' }}
          </AppButton>
        </template>
      </AppTable>
    </AppCard>
  </div>
</template>
