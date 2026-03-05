<script setup>
import { onMounted } from 'vue'
import { useAuthStore } from './stores/auth'

const authStore = useAuthStore()

onMounted(() => {
  authStore.initAuth()
})

const logout = async () => {
  await authStore.logout()
  window.location.href = '/login'
}
</script>

<template>
  <div class="app">
    <nav class="navbar" v-if="authStore.isAuthenticated">
      <div class="nav-brand">Blood Donation</div>
      <div class="nav-links">
        <router-link to="/dashboard">Dashboard</router-link>
        <router-link to="/donations">Donations</router-link>
        <router-link to="/requests">Requests</router-link>
        <router-link to="/profile">Profile</router-link>
        <button @click="logout" class="btn-logout">Logout</button>
      </div>
    </nav>
    <main class="main-content">
      <router-view />
    </main>
  </div>
</template>

<style scoped>
.app {
  min-height: 100vh;
  background: #f5f5f5;
}

.navbar {
  background: #dc2626;
  color: white;
  padding: 1rem 2rem;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.nav-brand {
  font-size: 1.5rem;
  font-weight: bold;
}

.nav-links {
  display: flex;
  gap: 1.5rem;
  align-items: center;
}

.nav-links a {
  color: white;
  text-decoration: none;
  padding: 0.5rem 1rem;
  border-radius: 4px;
  transition: background 0.3s;
}

.nav-links a:hover,
.nav-links a.router-link-active {
  background: rgba(255, 255, 255, 0.2);
}

.btn-logout {
  background: white;
  color: #dc2626;
  border: none;
  padding: 0.5rem 1rem;
  border-radius: 4px;
  cursor: pointer;
  font-weight: 500;
}

.btn-logout:hover {
  background: #f0f0f0;
}

.main-content {
  padding: 2rem;
  max-width: 1200px;
  margin: 0 auto;
}
</style>
