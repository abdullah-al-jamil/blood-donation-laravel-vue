<script setup>
import { ref, onMounted, computed } from 'vue'
import { useAuthStore, api } from '../stores/auth'

const authStore = useAuthStore()
const requests = ref([])
const loading = ref(false)
const showForm = ref(false)

const form = ref({
  blood_type: '',
  quantity_ml: 450,
  reason: '',
  hospital: '',
  required_date: ''
})

const bloodTypes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']

const isRecipient = computed(() => authStore.isRecipient)

const fetchRequests = async () => {
  loading.value = true
  try {
    const response = await api.get('/requests')
    requests.value = response.data
  } catch (error) {
    console.error('Error fetching requests:', error)
  } finally {
    loading.value = false
  }
}

const submitRequest = async () => {
  try {
    await api.post('/requests', form.value)
    showForm.value = false
    form.value = {
      blood_type: '',
      quantity_ml: 450,
      reason: '',
      hospital: '',
      required_date: ''
    }
    fetchRequests()
  } catch (error) {
    console.error('Error creating request:', error)
  }
}

const updateStatus = async (id, status) => {
  try {
    await api.patch(`/requests/${id}`, { status })
    fetchRequests()
  } catch (error) {
    console.error('Error updating request:', error)
  }
}

const deleteRequest = async (id) => {
  if (confirm('Are you sure you want to delete this request?')) {
    try {
      await api.delete(`/requests/${id}`)
      fetchRequests()
    } catch (error) {
      console.error('Error deleting request:', error)
    }
  }
}

const canManage = computed(() => {
  return authStore.isAdmin || authStore.isDonor
})

onMounted(fetchRequests)
</script>

<template>
  <div class="requests-page">
    <div class="header">
      <h1>Blood Requests</h1>
      <button v-if="isRecipient" @click="showForm = !showForm" class="btn-primary">
        {{ showForm ? 'Cancel' : 'New Request' }}
      </button>
    </div>

    <div v-if="showForm && isRecipient" class="request-form">
      <h2>Request Blood</h2>
      <form @submit.prevent="submitRequest">
        <div class="form-row">
          <div class="form-group">
            <label>Blood Type Needed</label>
            <select v-model="form.blood_type" required>
              <option value="">Select Blood Type</option>
              <option v-for="bt in bloodTypes" :key="bt" :value="bt">{{ bt }}</option>
            </select>
          </div>
          <div class="form-group">
            <label>Quantity (ml)</label>
            <input v-model.number="form.quantity_ml" type="number" min="100" max="1000" required />
          </div>
          <div class="form-group">
            <label>Required Date</label>
            <input v-model="form.required_date" type="date" />
          </div>
        </div>
        <div class="form-row">
          <div class="form-group">
            <label>Hospital</label>
            <input v-model="form.hospital" type="text" />
          </div>
          <div class="form-group">
            <label>Reason</label>
            <input v-model="form.reason" type="text" />
          </div>
        </div>
        <button type="submit" class="btn-primary">Submit Request</button>
      </form>
    </div>

    <div v-if="loading" class="loading">Loading requests...</div>
    
    <div v-else-if="requests.length === 0" class="empty">
      No blood requests found.
    </div>

    <div v-else class="requests-list">
      <div v-for="request in requests" :key="request.id" class="request-card">
        <div class="request-header">
          <span class="blood-type">{{ request.blood_type }}</span>
          <span :class="['status', request.status]">{{ request.status }}</span>
        </div>
        <div class="request-details">
          <p><strong>Quantity:</strong> {{ request.quantity_ml }} ml</p>
          <p v-if="request.hospital"><strong>Hospital:</strong> {{ request.hospital }}</p>
          <p v-if="request.reason"><strong>Reason:</strong> {{ request.reason }}</p>
          <p v-if="request.required_date"><strong>Required Date:</strong> {{ new Date(request.required_date).toLocaleDateString() }}</p>
          <p><strong>Requested By:</strong> {{ request.user?.name }}</p>
        </div>
        <div v-if="canManage && request.status === 'pending'" class="actions">
          <button @click="updateStatus(request.id, 'approved')" class="btn-approve">Approve</button>
          <button @click="updateStatus(request.id, 'rejected')" class="btn-reject">Reject</button>
        </div>
        <button v-if="request.user_id === authStore.user?.id" @click="deleteRequest(request.id)" class="btn-delete">
          Delete
        </button>
      </div>
    </div>
  </div>
</template>

<style scoped>
.header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 2rem;
}

.header h1 {
  color: #dc2626;
}

.btn-primary {
  background: #dc2626;
  color: white;
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.request-form {
  background: white;
  padding: 2rem;
  border-radius: 8px;
  margin-bottom: 2rem;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.request-form h2 {
  margin-bottom: 1rem;
  color: #333;
}

.form-row {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
}

.form-group {
  margin-bottom: 1rem;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  color: #333;
}

.form-group input,
.form-group select {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #ddd;
  border-radius: 4px;
}

.loading, .empty {
  text-align: center;
  padding: 3rem;
  color: #666;
}

.requests-list {
  display: grid;
  gap: 1rem;
}

.request-card {
  background: white;
  padding: 1.5rem;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.request-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.blood-type {
  font-size: 1.5rem;
  font-weight: bold;
  color: #dc2626;
}

.status {
  padding: 0.25rem 0.75rem;
  border-radius: 12px;
  font-size: 0.875rem;
}

.status.pending {
  background: #fef3c7;
  color: #92400e;
}

.status.approved {
  background: #dbeafe;
  color: #1e40af;
}

.status.fulfilled {
  background: #d1fae5;
  color: #065f46;
}

.status.rejected {
  background: #fee2e2;
  color: #991b1b;
}

.request-details p {
  margin: 0.25rem 0;
  color: #333;
}

.actions {
  margin-top: 1rem;
  display: flex;
  gap: 0.5rem;
}

.btn-approve {
  background: #10b981;
  color: white;
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.btn-reject {
  background: #dc2626;
  color: white;
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.btn-delete {
  margin-top: 1rem;
  background: #6b7280;
  color: white;
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}
</style>
