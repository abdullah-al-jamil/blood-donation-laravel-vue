<script setup>
import { computed } from 'vue'
import { useAuthStore } from '../stores/auth'

const authStore = useAuthStore()

const user = computed(() => authStore.user)
const roleLabel = computed(() => {
  if (user.value?.role === 'donor') return 'Blood Donor'
  if (user.value?.role === 'recipient') return 'Blood Recipient'
  return 'Administrator'
})
</script>

<template>
  <div class="dashboard">
    <h1>Welcome, {{ user?.name }}!</h1>
    <div class="user-info">
      <div class="info-card">
        <h3>Role</h3>
        <p>{{ roleLabel }}</p>
      </div>
      <div class="info-card">
        <h3>Email</h3>
        <p>{{ user?.email }}</p>
      </div>
      <div class="info-card" v-if="user?.phone">
        <h3>Phone</h3>
        <p>{{ user.phone }}</p>
      </div>
      <div class="info-card" v-if="user?.blood_type">
        <h3>Blood Type</h3>
        <p>{{ user.blood_type }}</p>
      </div>
    </div>
    <div class="quick-actions">
      <h2>Quick Actions</h2>
      <div class="actions">
        <router-link to="/donations" class="action-card">
          <h3>View Donations</h3>
          <p>See blood donation history</p>
        </router-link>
        <router-link to="/requests" class="action-card">
          <h3>Blood Requests</h3>
          <p>Manage blood requests</p>
        </router-link>
        <router-link to="/profile" class="action-card">
          <h3>Edit Profile</h3>
          <p>Update your information</p>
        </router-link>
      </div>
    </div>
  </div>
</template>

<style scoped>
.dashboard h1 {
  color: #dc2626;
  margin-bottom: 2rem;
}

.user-info {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
  gap: 1rem;
  margin-bottom: 2rem;
}

.info-card {
  background: white;
  padding: 1.5rem;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.info-card h3 {
  color: #666;
  font-size: 0.875rem;
  margin-bottom: 0.5rem;
}

.info-card p {
  font-size: 1.25rem;
  font-weight: 500;
  color: #333;
}

.quick-actions h2 {
  margin-bottom: 1rem;
}

.actions {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1rem;
}

.action-card {
  background: white;
  padding: 1.5rem;
  border-radius: 8px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
  text-decoration: none;
  color: inherit;
  transition: transform 0.2s, box-shadow 0.2s;
}

.action-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15);
}

.action-card h3 {
  color: #dc2626;
  margin-bottom: 0.5rem;
}

.action-card p {
  color: #666;
}
</style>
