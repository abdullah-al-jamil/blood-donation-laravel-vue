<script setup>
import { ref, onMounted, computed } from 'vue'
import { useAuthStore, api } from '../stores/auth'

const authStore = useAuthStore()
const donations = ref([])
const loading = ref(false)
const showForm = ref(false)

const form = ref({
  blood_type: '',
  quantity_ml: 450,
  donation_date: new Date().toISOString().split('T')[0],
  notes: ''
})

const bloodTypes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']

const isDonor = computed(() => authStore.isDonor)
const isAdmin = computed(() => authStore.isAdmin)
const canManage = computed(() => authStore.isAdmin)

const fetchDonations = async () => {
  loading.value = true
  try {
    const response = await api.get('/donations')
    donations.value = response.data
  } catch (error) {
    console.error('Error fetching donations:', error)
  } finally {
    loading.value = false
  }
}

const submitDonation = async () => {
  try {
    await api.post('/donations', form.value)
    showForm.value = false
    form.value = {
      blood_type: '',
      quantity_ml: 450,
      donation_date: new Date().toISOString().split('T')[0],
      notes: ''
    }
    fetchDonations()
  } catch (error) {
    console.error('Error creating donation:', error)
  }
}

const deleteDonation = async (id) => {
  if (confirm('Are you sure you want to delete this donation?')) {
    try {
      await api.delete(`/donations/${id}`)
      fetchDonations()
    } catch (error) {
      console.error('Error deleting donation:', error)
    }
  }
}

const updateStatus = async (id, status) => {
  try {
    await api.patch(`/donations/${id}`, { status })
    fetchDonations()
  } catch (error) {
    console.error('Error updating status:', error)
    alert('Failed to update status: ' + (error.response?.data?.message || error.message))
  }
}

onMounted(fetchDonations)
</script>

<template>
  <div class="donations-page">
    <div class="header">
      <h1>Blood Donations</h1>
      <button v-if="isDonor" @click="showForm = !showForm" class="btn-primary">
        {{ showForm ? 'Cancel' : 'New Donation' }}
      </button>
      <span v-if="isAdmin" class="admin-notice">Admin: You can approve/reject donations</span>
      <span v-else class="admin-notice">User Role: {{ authStore.user?.role }}</span>
    </div>

    <div v-if="showForm && isDonor" class="donation-form">
      <h2>Record New Donation</h2>
      <form @submit.prevent="submitDonation">
        <div class="form-row">
          <div class="form-group">
            <label>Blood Type</label>
            <select v-model="form.blood_type" required>
              <option value="">Select Blood Type</option>
              <option v-for="bt in bloodTypes" :key="bt" :value="bt">{{ bt }}</option>
            </select>
          </div>
          <div class="form-group">
            <label>Quantity (ml)</label>
            <input v-model.number="form.quantity_ml" type="number" min="100" max="500" required />
          </div>
          <div class="form-group">
            <label>Donation Date</label>
            <input v-model="form.donation_date" type="date" required />
          </div>
        </div>
        <div class="form-group">
          <label>Notes</label>
          <textarea v-model="form.notes" rows="3"></textarea>
        </div>
        <button type="submit" class="btn-primary">Submit Donation</button>
      </form>
    </div>

    <div v-if="loading" class="loading">Loading donations...</div>
    
    <div v-else-if="donations.length === 0" class="empty">
      No donations found.
    </div>

    <div v-else class="donations-list">
      <div v-for="donation in donations" :key="donation.id" class="donation-card">
        <div class="donation-header">
          <span class="blood-type">{{ donation.blood_type }}</span>
          <span :class="['status', donation.status]">{{ donation.status }}</span>
        </div>
        <div class="donation-details">
          <p><strong>Quantity:</strong> {{ donation.quantity_ml }} ml</p>
          <p><strong>Date:</strong> {{ new Date(donation.donation_date).toLocaleDateString() }}</p>
          <p v-if="donation.donor"><strong>Donor:</strong> {{ donation.donor.name }}</p>
        </div>
        <div v-if="donation.notes" class="notes">
          <strong>Notes:</strong> {{ donation.notes }}
        </div>
        <div v-if="canManage && donation.status === 'pending'" class="actions">
          <button @click="updateStatus(donation.id, 'completed')" class="btn-approve">Approve</button>
          <button @click="updateStatus(donation.id, 'rejected')" class="btn-reject">Reject</button>
        </div>
        <button v-if="isDonor && donation.donor_id === authStore.user?.id" @click="deleteDonation(donation.id)" class="btn-delete">
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
  gap: 1rem;
  flex-wrap: wrap;
}

.header h1 {
  color: #dc2626;
}

.admin-notice {
  color: #666;
  font-size: 0.875rem;
}

.btn-primary {
  background: #dc2626;
  color: white;
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.donation-form {
  background: white;
  padding: 2rem;
  border-radius: 8px;
  margin-bottom: 2rem;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.donation-form h2 {
  margin-bottom: 1rem;
  color: #333;
}

.form-row {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
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
.form-group select,
.form-group textarea {
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

.donations-list {
  display: grid;
  gap: 1rem;
}

.donation-card {
  background: white;
  padding: 1.5rem;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.donation-header {
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

.status.completed {
  background: #d1fae5;
  color: #065f46;
}

.status.rejected {
  background: #fee2e2;
  color: #991b1b;
}

.donation-details p {
  margin: 0.25rem 0;
  color: #333;
}

.notes {
  margin-top: 1rem;
  padding-top: 1rem;
  border-top: 1px solid #eee;
  color: #666;
}

.btn-delete {
  margin-top: 1rem;
  background: #dc2626;
  color: white;
  padding: 0.5rem 1rem;
  border: none;
  border-radius: 4px;
  cursor: pointer;
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
</style>
