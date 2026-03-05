<script setup>
import { ref, onMounted } from 'vue'
import { useAuthStore } from '../stores/auth'

const authStore = useAuthStore()
const loading = ref(false)
const message = ref('')

const form = ref({
  name: '',
  email: '',
  phone: '',
  address: '',
  date_of_birth: '',
  blood_type: ''
})

const bloodTypes = ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-']

onMounted(() => {
  if (authStore.user) {
    form.value = {
      name: authStore.user.name,
      email: authStore.user.email,
      phone: authStore.user.phone || '',
      address: authStore.user.address || '',
      date_of_birth: authStore.user.date_of_birth || '',
      blood_type: authStore.user.blood_type || ''
    }
  }
})

const updateProfile = async () => {
  loading.value = true
  message.value = ''
  try {
    await authStore.fetchUser()
    message.value = 'Profile updated successfully!'
  } catch (error) {
    message.value = 'Failed to update profile'
  } finally {
    loading.value = false
  }
}
</script>

<template>
  <div class="profile-page">
    <h1>Edit Profile</h1>
    
    <div v-if="message" :class="['message', message.includes('success') ? 'success' : 'error']">
      {{ message }}
    </div>

    <form @submit.prevent="updateProfile" class="profile-form">
      <div class="form-row">
        <div class="form-group">
          <label>Name</label>
          <input v-model="form.name" type="text" required />
        </div>
        <div class="form-group">
          <label>Email</label>
          <input v-model="form.email" type="email" disabled />
        </div>
      </div>
      <div class="form-row">
        <div class="form-group">
          <label>Phone</label>
          <input v-model="form.phone" type="tel" />
        </div>
        <div class="form-group">
          <label>Date of Birth</label>
          <input v-model="form.date_of_birth" type="date" />
        </div>
      </div>
      <div class="form-group">
        <label>Address</label>
        <input v-model="form.address" type="text" />
      </div>
      <div class="form-group" v-if="authStore.isDonor">
        <label>Blood Type</label>
        <select v-model="form.blood_type">
          <option value="">Select Blood Type</option>
          <option v-for="bt in bloodTypes" :key="bt" :value="bt">{{ bt }}</option>
        </select>
      </div>
      <button type="submit" :disabled="loading" class="btn-primary">
        {{ loading ? 'Saving...' : 'Save Changes' }}
      </button>
    </form>
  </div>
</template>

<style scoped>
.profile-page h1 {
  color: #dc2626;
  margin-bottom: 2rem;
}

.profile-form {
  background: white;
  padding: 2rem;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  max-width: 600px;
}

.form-row {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
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

.form-group input:disabled {
  background: #f3f4f6;
  color: #6b7280;
}

.btn-primary {
  background: #dc2626;
  color: white;
  padding: 0.75rem 1.5rem;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  margin-top: 1rem;
}

.btn-primary:hover:not(:disabled) {
  background: #b91c1c;
}

.btn-primary:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

.message {
  padding: 1rem;
  border-radius: 4px;
  margin-bottom: 1rem;
}

.message.success {
  background: #d1fae5;
  color: #065f46;
}

.message.error {
  background: #fee2e2;
  color: #991b1b;
}
</style>
