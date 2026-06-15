<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useToastStore } from '@/stores/toast'
import { AppCard, AppTable, AppBadge, AppButton, AppModal, AppFormInput } from '@/components/shared'
import * as adminApi from '@/api/admin'

const toast = useToastStore()
const centers = ref<any[]>([])
const loading = ref(true)
const showModal = ref(false)
const editing = ref<any>(null)

const form = ref({ name: '', address: '', phone: '', email: '' })
const submitting = ref(false)

const columns = [
  { key: 'name', label: 'Name' },
  { key: 'address', label: 'Address' },
  { key: 'phone', label: 'Phone' },
  { key: 'email', label: 'Email' },
  { key: 'actions', label: 'Actions' },
]

async function fetchCenters() {
  loading.value = true
  try {
    const { data } = await adminApi.getCenters()
    centers.value = data.data ?? data ?? []
  } catch {
    toast.add('Failed to load centers', 'error')
  } finally {
    loading.value = false
  }
}

function openCreate() {
  editing.value = null
  form.value = { name: '', address: '', phone: '', email: '' }
  showModal.value = true
}

function openEdit(center: any) {
  editing.value = center
  form.value = { name: center.name, address: center.address || '', phone: center.phone || '', email: center.email || '' }
  showModal.value = true
}

async function handleSubmit() {
  submitting.value = true
  try {
    if (editing.value) {
      await adminApi.updateCenter(editing.value.id, form.value)
      toast.add('Center updated', 'success')
    } else {
      await adminApi.createCenter(form.value)
      toast.add('Center created', 'success')
    }
    showModal.value = false
    await fetchCenters()
  } catch (e: any) {
    toast.add(e.response?.data?.message || 'Failed', 'error')
  } finally {
    submitting.value = false
  }
}

async function handleDelete(id: number) {
  if (!confirm('Delete this center?')) return
  try {
    await adminApi.deleteCenter(id)
    toast.add('Deleted', 'success')
    await fetchCenters()
  } catch {
    toast.add('Failed to delete', 'error')
  }
}

function formatRow(row: any) {
  return {
    ...row,
    address: row.address || '-',
    phone: row.phone || '-',
    email: row.email || '-',
    actions: '',
  }
}

onMounted(fetchCenters)
</script>

<template>
  <div>
    <div class="flex items-center justify-between mb-6">
      <h1 class="text-2xl font-bold text-gray-900">Donation Centers</h1>
      <AppButton @click="openCreate">Add Center</AppButton>
    </div>

    <AppCard>
      <AppTable
        :columns="columns"
        :data="centers.map(formatRow)"
        :loading="loading"
        empty-text="No centers found."
      >
        <template #cell-actions="{ row }">
          <div class="flex gap-2">
            <AppButton size="sm" variant="ghost" @click="openEdit(row)">Edit</AppButton>
            <AppButton size="sm" variant="ghost" @click="handleDelete(row.id)">Delete</AppButton>
          </div>
        </template>
      </AppTable>
    </AppCard>

    <AppModal
      :visible="showModal"
      :title="editing ? 'Edit Center' : 'Add Center'"
      @close="showModal = false"
    >
      <form @submit.prevent="handleSubmit" id="modal-form">
        <AppFormInput v-model="form.name" label="Name" required />
        <AppFormInput v-model="form.address" label="Address" />
        <AppFormInput v-model="form.phone" label="Phone" type="tel" />
        <AppFormInput v-model="form.email" label="Email" type="email" />
      </form>
      <template #footer>
        <AppButton variant="secondary" @click="showModal = false">Cancel</AppButton>
        <AppButton type="submit" form="modal-form" :loading="submitting">{{ editing ? 'Update' : 'Create' }}</AppButton>
      </template>
    </AppModal>
  </div>
</template>
