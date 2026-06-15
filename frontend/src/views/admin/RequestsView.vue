<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useToastStore } from '@/stores/toast'
import { AppCard, AppTable, AppBadge, AppButton, AppModal, AppFormInput, AppSelect } from '@/components/shared'
import * as adminApi from '@/api/admin'

const toast = useToastStore()
const requests = ref<any[]>([])
const loading = ref(true)
const showModal = ref(false)
const selectedRequest = ref<any>(null)

const columns = [
  { key: 'patient_name', label: 'Patient' },
  { key: 'blood_type', label: 'Blood Type' },
  { key: 'bags_needed', label: 'Bags' },
  { key: 'hospital', label: 'Hospital' },
  { key: 'urgency', label: 'Urgency' },
  { key: 'status', label: 'Status' },
  { key: 'created_at', label: 'Created' },
  { key: 'actions', label: 'Actions' },
]

async function fetchRequests() {
  loading.value = true
  try {
    const { data } = await adminApi.getBloodRequests()
    requests.value = data.data ?? data ?? []
  } catch {
    toast.add('Failed to load requests', 'error')
  } finally {
    loading.value = false
  }
}

function viewDetails(req: any) {
  selectedRequest.value = req
  showModal.value = true
}

async function handleFulfill(id: number) {
  if (!confirm('Mark this request as fulfilled?')) return
  try {
    await adminApi.fulfillBloodRequest(id)
    toast.add('Request fulfilled', 'success')
    await fetchRequests()
  } catch {
    toast.add('Failed to fulfill', 'error')
  }
}

async function handleDelete(id: number) {
  if (!confirm('Delete this request?')) return
  try {
    await adminApi.deleteBloodRequest(id)
    toast.add('Deleted', 'success')
    await fetchRequests()
  } catch {
    toast.add('Failed to delete', 'error')
  }
}

function urgencyVariant(u: string) {
  if (u === 'critical') return 'danger'
  if (u === 'urgent') return 'warning'
  return 'info'
}

function statusVariant(s: string) {
  if (s === 'fulfilled') return 'success'
  if (s === 'cancelled') return 'danger'
  return 'warning'
}

function formatRow(row: any) {
  return {
    ...row,
    created_at: row.created_at ? new Date(row.created_at).toLocaleDateString() : '-',
    actions: '',
  }
}

onMounted(fetchRequests)
</script>

<template>
  <div>
    <h1 class="text-2xl font-bold text-gray-900 mb-6">Blood Requests</h1>
    <AppCard>
      <AppTable
        :columns="columns"
        :data="requests.map(formatRow)"
        :loading="loading"
        empty-text="No requests found."
      >
        <template #cell-urgency="{ row }">
          <AppBadge :variant="urgencyVariant(row.urgency)">{{ row.urgency }}</AppBadge>
        </template>
        <template #cell-status="{ row }">
          <AppBadge :variant="statusVariant(row.status)">{{ row.status }}</AppBadge>
        </template>
        <template #cell-actions="{ row }">
          <div class="flex gap-2">
            <AppButton size="sm" variant="ghost" @click="viewDetails(row)">View</AppButton>
            <AppButton v-if="row.status === 'pending'" size="sm" variant="primary" @click="handleFulfill(row.id)">Fulfill</AppButton>
            <AppButton size="sm" variant="ghost" @click="handleDelete(row.id)">Delete</AppButton>
          </div>
        </template>
      </AppTable>
    </AppCard>

    <AppModal :visible="showModal" title="Request Details" @close="showModal = false">
      <div v-if="selectedRequest" class="space-y-3">
        <div><span class="text-sm text-gray-500">Patient:</span> <span class="text-sm font-medium">{{ selectedRequest.patient_name }}</span></div>
        <div><span class="text-sm text-gray-500">Blood Type:</span> <span class="text-sm font-medium">{{ selectedRequest.blood_type }}</span></div>
        <div><span class="text-sm text-gray-500">Bags Needed:</span> <span class="text-sm font-medium">{{ selectedRequest.bags_needed }}</span></div>
        <div><span class="text-sm text-gray-500">Hospital:</span> <span class="text-sm font-medium">{{ selectedRequest.hospital }}</span></div>
        <div><span class="text-sm text-gray-500">Urgency:</span> <AppBadge :variant="urgencyVariant(selectedRequest.urgency)">{{ selectedRequest.urgency }}</AppBadge></div>
        <div><span class="text-sm text-gray-500">Status:</span> <AppBadge :variant="statusVariant(selectedRequest.status)">{{ selectedRequest.status }}</AppBadge></div>
        <div><span class="text-sm text-gray-500">Notes:</span> <p class="text-sm text-gray-700 mt-1">{{ selectedRequest.notes || 'None' }}</p></div>
      </div>
      <template #footer>
        <AppButton variant="secondary" @click="showModal = false">Close</AppButton>
      </template>
    </AppModal>
  </div>
</template>
