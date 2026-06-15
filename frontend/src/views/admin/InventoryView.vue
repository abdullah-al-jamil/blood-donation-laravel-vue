<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useToastStore } from '@/stores/toast'
import { AppCard, AppTable, AppBadge, AppButton, AppModal, AppFormInput, AppSelect, AppPagination } from '@/components/shared'
import * as adminApi from '@/api/admin'

const toast = useToastStore()
const items = ref<any[]>([])
const loading = ref(true)
const showModal = ref(false)
const editing = ref<any>(null)
const summary = ref<any[]>([])

const form = ref({ blood_type: '', bags: 1, expiry_date: '', status: 'available' })
const submitting = ref(false)

const bloodTypes = [
  { value: 'A+', label: 'A+' }, { value: 'A-', label: 'A-' },
  { value: 'B+', label: 'B+' }, { value: 'B-', label: 'B-' },
  { value: 'AB+', label: 'AB+' }, { value: 'AB-', label: 'AB-' },
  { value: 'O+', label: 'O+' }, { value: 'O-', label: 'O-' },
]

const statusOptions = [
  { value: 'available', label: 'Available' },
  { value: 'expired', label: 'Expired' },
  { value: 'reserved', label: 'Reserved' },
]

const columns = [
  { key: 'blood_type', label: 'Blood Type' },
  { key: 'bags', label: 'Bags' },
  { key: 'expiry_date', label: 'Expiry Date' },
  { key: 'status', label: 'Status' },
  { key: 'actions', label: 'Actions' },
]

async function fetchData() {
  loading.value = true
  try {
    const [itemsRes, summaryRes] = await Promise.all([
      adminApi.getInventory(),
      adminApi.getInventorySummary().catch(() => ({ data: { data: [] } })),
    ])
    items.value = itemsRes.data.data ?? itemsRes.data ?? []
    summary.value = summaryRes.data.data ?? summaryRes.data ?? []
  } catch {
    toast.add('Failed to load inventory', 'error')
  } finally {
    loading.value = false
  }
}

function openCreate() {
  editing.value = null
  form.value = { blood_type: '', bags: 1, expiry_date: '', status: 'available' }
  showModal.value = true
}

function openEdit(item: any) {
  editing.value = item
  form.value = { blood_type: item.blood_type, bags: item.bags, expiry_date: item.expiry_date || '', status: item.status }
  showModal.value = true
}

async function handleSubmit() {
  submitting.value = true
  try {
    if (editing.value) {
      await adminApi.updateInventory(editing.value.id, form.value)
      toast.add('Inventory updated', 'success')
    } else {
      await adminApi.createInventory(form.value)
      toast.add('Inventory created', 'success')
    }
    showModal.value = false
    await fetchData()
  } catch (e: any) {
    toast.add(e.response?.data?.message || 'Failed', 'error')
  } finally {
    submitting.value = false
  }
}

async function handleDelete(id: number) {
  if (!confirm('Delete this inventory item?')) return
  try {
    await adminApi.deleteInventory(id)
    toast.add('Deleted', 'success')
    await fetchData()
  } catch {
    toast.add('Failed to delete', 'error')
  }
}

function statusVariant(s: string) {
  if (s === 'available') return 'success'
  if (s === 'expired') return 'danger'
  return 'warning'
}

onMounted(fetchData)
</script>

<template>
  <div>
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold text-gray-900">Blood Inventory</h1>
      <AppButton @click="openCreate">Add Inventory</AppButton>
    </div>

    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
      <AppCard v-for="s in summary" :key="s.blood_type">
        <p class="text-sm text-gray-500">{{ s.blood_type }}</p>
        <p class="text-xl font-bold mt-1" :class="s.bags < 5 ? 'text-red-600' : 'text-gray-900'">{{ s.bags }} bags</p>
      </AppCard>
    </div>

    <AppCard>
      <AppTable :columns="columns" :data="items.map((r: any) => ({ ...r, status: r.status || 'available' }))" :loading="loading" empty-text="No inventory found." />
    </AppCard>

    <AppModal :visible="showModal" :title="editing ? 'Edit Inventory' : 'Add Inventory'" @close="showModal = false">
      <form @submit.prevent="handleSubmit" id="modal-form">
        <AppSelect v-model="form.blood_type" label="Blood Type" :options="bloodTypes" required />
        <AppFormInput v-model="form.bags" label="Bags" type="text" required />
        <AppFormInput v-model="form.expiry_date" label="Expiry Date" type="date" />
        <AppSelect v-model="form.status" label="Status" :options="statusOptions" />
      </form>
      <template #footer>
        <AppButton variant="secondary" @click="showModal = false">Cancel</AppButton>
        <AppButton type="submit" form="modal-form" :loading="submitting">{{ editing ? 'Update' : 'Create' }}</AppButton>
      </template>
    </AppModal>
  </div>
</template>
