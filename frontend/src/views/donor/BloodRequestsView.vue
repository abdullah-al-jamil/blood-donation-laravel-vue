<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '@/stores/auth'
import { useToastStore } from '@/stores/toast'
import { AppButton, AppBadge, AppCard, AppSelect, AppFormInput, AppModal, AppPagination } from '@/components/shared'
import * as publicApi from '@/api/public'
import * as donorApi from '@/api/donor'

const router = useRouter()
const auth = useAuthStore()
const toast = useToastStore()

const requests = ref<any[]>([])
const loading = ref(true)
const currentPage = ref(1)
const lastPage = ref(1)
const total = ref(0)

const selectedBloodType = ref('')
const selectedUrgency = ref('')

const showRequestModal = ref(false)
const submitting = ref(false)
const requestForm = ref({
  patient_name: '',
  blood_type: '',
  bags_needed: 1,
  hospital: '',
  urgency: 'medium',
  notes: '',
})

const bloodTypeOptions = [
  { value: '', label: 'All Blood Types' },
  { value: 'A+', label: 'A+' },
  { value: 'A-', label: 'A-' },
  { value: 'B+', label: 'B+' },
  { value: 'B-', label: 'B-' },
  { value: 'AB+', label: 'AB+' },
  { value: 'AB-', label: 'AB-' },
  { value: 'O+', label: 'O+' },
  { value: 'O-', label: 'O-' },
]

const formBloodTypeOptions = bloodTypeOptions.slice(1)

const urgencyOptions = [
  { value: '', label: 'All Urgencies' },
  { value: 'critical', label: 'Critical' },
  { value: 'high', label: 'High' },
  { value: 'medium', label: 'Medium' },
  { value: 'low', label: 'Low' },
]

const formUrgencyOptions = [
  { value: 'low', label: 'Low' },
  { value: 'medium', label: 'Medium' },
  { value: 'high', label: 'High' },
  { value: 'critical', label: 'Critical' },
]

const columns = [
  { key: 'patient_name', label: 'Patient' },
  { key: 'blood_type', label: 'Blood Type' },
  { key: 'bags_needed', label: 'Bags' },
  { key: 'hospital', label: 'Hospital' },
  { key: 'urgency', label: 'Urgency' },
  { key: 'status', label: 'Status' },
]

async function fetchRequests(page = 1) {
  loading.value = true
  try {
    const params: any = { page }
    if (selectedBloodType.value) params.blood_type = selectedBloodType.value
    if (selectedUrgency.value) params.urgency = selectedUrgency.value

    const { data } = await publicApi.getBloodRequests({ params })
    const body = data.data ?? data
    requests.value = body.data ?? body ?? []
    currentPage.value = body.current_page ?? page
    lastPage.value = body.last_page ?? 1
    total.value = body.total ?? 0
  } catch {
    toast.add('Failed to load requests', 'error')
  } finally {
    loading.value = false
  }
}

function onPageChange(page: number) {
  fetchRequests(page)
}

function openRequestModal() {
  requestForm.value = {
    patient_name: '',
    blood_type: auth.user?.blood_type || '',
    bags_needed: 1,
    hospital: '',
    urgency: 'medium',
    notes: '',
  }
  showRequestModal.value = true
}

async function handleSubmitRequest() {
  submitting.value = true
  try {
    await donorApi.createBloodRequest(requestForm.value)
    toast.add('Blood request submitted successfully!', 'success')
    showRequestModal.value = false
    fetchRequests(1)
  } catch (e: any) {
    toast.add(e.response?.data?.message || 'Failed to submit request', 'error')
  } finally {
    submitting.value = false
  }
}

function urgencyVariant(u: string) {
  if (u === 'critical') return 'danger'
  if (u === 'high' || u === 'urgent') return 'warning'
  return 'info'
}

function statusVariant(s: string) {
  if (s === 'fulfilled') return 'success'
  if (s === 'cancelled') return 'danger'
  return 'warning'
}

watch([selectedBloodType, selectedUrgency], () => fetchRequests(1))

onMounted(() => fetchRequests(1))
</script>

<template>
  <div>
    <div class="flex items-center justify-between mb-6">
      <div>
        <h1 class="text-2xl font-bold text-gray-900">Blood Requests</h1>
        <p v-if="auth.user?.blood_type" class="text-sm text-gray-500 mt-1">
          Showing requests for <span class="font-medium text-red-600">{{ auth.user.blood_type }}</span> and other blood types
        </p>
      </div>
      <div class="flex items-center gap-3">
        <AppButton variant="primary" @click="openRequestModal">Request Blood</AppButton>
        <AppButton variant="ghost" @click="router.push('/donor')">← Back</AppButton>
      </div>
    </div>

    <div class="flex flex-col sm:flex-row gap-4 mb-6">
      <div class="w-full sm:w-48">
        <AppSelect
          v-model="selectedBloodType"
          label=""
          :options="bloodTypeOptions"
          placeholder="All Blood Types"
        />
      </div>
      <div class="w-full sm:w-48">
        <AppSelect
          v-model="selectedUrgency"
          label=""
          :options="urgencyOptions"
          placeholder="All Urgencies"
        />
      </div>
    </div>

    <AppCard>
      <table class="w-full text-sm">
        <thead>
          <tr class="border-b border-gray-200">
            <th v-for="col in columns" :key="col.key" class="text-left py-3 px-3 font-medium text-gray-500 first:pl-0 last:pr-0">
              {{ col.label }}
            </th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="loading">
            <td :colspan="columns.length" class="text-center py-8 text-gray-500">Loading...</td>
          </tr>
          <tr v-else-if="requests.length === 0">
            <td :colspan="columns.length" class="text-center py-8 text-gray-500">No blood requests found.</td>
          </tr>
          <tr v-else v-for="row in requests" :key="row.id" class="border-b border-gray-100 last:border-0 hover:bg-gray-50">
            <td class="py-3 px-3 first:pl-0">
              <span class="font-medium text-gray-900">{{ row.patient_name || 'Patient' }}</span>
            </td>
            <td class="py-3 px-3">
              <span class="font-semibold text-gray-900">{{ row.blood_type }}</span>
            </td>
            <td class="py-3 px-3 text-gray-700">{{ row.bags_needed }}</td>
            <td class="py-3 px-3 text-gray-700">{{ row.hospital }}</td>
            <td class="py-3 px-3">
              <AppBadge :variant="urgencyVariant(row.urgency)">{{ row.urgency }}</AppBadge>
            </td>
            <td class="py-3 px-3 last:pr-0">
              <AppBadge :variant="statusVariant(row.status)">{{ row.status }}</AppBadge>
            </td>
          </tr>
        </tbody>
      </table>

      <AppPagination
        :current-page="currentPage"
        :last-page="lastPage"
        :total="total"
        @page="onPageChange"
      />
    </AppCard>

    <AppModal :visible="showRequestModal" title="Request Blood" @close="showRequestModal = false">
      <form @submit.prevent="handleSubmitRequest" id="request-form">
        <AppFormInput v-model="requestForm.patient_name" label="Patient Name" placeholder="Full name of the patient" required />
        <AppSelect v-model="requestForm.blood_type" label="Blood Type" :options="formBloodTypeOptions" placeholder="Select blood type" required />
        <AppFormInput v-model="requestForm.bags_needed" label="Bags Needed" type="text" placeholder="e.g. 2" required />
        <AppFormInput v-model="requestForm.hospital" label="Hospital" placeholder="Hospital name" required />
        <AppSelect v-model="requestForm.urgency" label="Urgency" :options="formUrgencyOptions" />
        <div class="mb-4">
          <label class="block text-sm font-medium text-gray-700 mb-1">Notes</label>
          <textarea
            :value="requestForm.notes"
            @input="requestForm.notes = ($event.target as HTMLTextAreaElement).value"
            placeholder="Additional information (optional)"
            rows="3"
            class="block w-full rounded-lg border border-gray-300 px-3 py-2 text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
          />
        </div>
      </form>
      <template #footer>
        <AppButton variant="secondary" @click="showRequestModal = false">Cancel</AppButton>
        <AppButton type="submit" form="request-form" :loading="submitting">Submit Request</AppButton>
      </template>
    </AppModal>
  </div>
</template>