<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { useAuthStore } from '../stores/auth'

const router = useRouter()
const authStore = useAuthStore()

const form = ref({
  email: '',
  password: ''
})

const handleLogin = async () => {
  const success = await authStore.login(form.value)
  if (success) {
    router.push('/dashboard')
  }
}
</script>

<template>
  <div class="auth-container">
    <div class="auth-card">
      <h2>Login</h2>
      <form @submit.prevent="handleLogin">
        <div class="form-group">
          <label>Email</label>
          <input v-model="form.email" type="email" required />
        </div>
        <div class="form-group">
          <label>Password</label>
          <input v-model="form.password" type="password" required />
        </div>
        <div v-if="authStore.error" class="error">{{ authStore.error }}</div>
        <button type="submit" :disabled="authStore.loading">
          {{ authStore.loading ? 'Logging in...' : 'Login' }}
        </button>
      </form>
      <p class="switch-auth">
        Don't have an account? <router-link to="/register">Register</router-link>
      </p>
    </div>
  </div>
</template>

<style scoped>
.auth-container {
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 60vh;
}

.auth-card {
  background: white;
  padding: 2rem;
  border-radius: 8px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
  width: 100%;
  max-width: 400px;
}

.auth-card h2 {
  text-align: center;
  color: #dc2626;
  margin-bottom: 1.5rem;
}

.form-group {
  margin-bottom: 1rem;
}

.form-group label {
  display: block;
  margin-bottom: 0.5rem;
  color: #333;
}

.form-group input {
  width: 100%;
  padding: 0.75rem;
  border: 1px solid #ddd;
  border-radius: 4px;
}

button[type="submit"] {
  width: 100%;
  padding: 0.75rem;
  background: #dc2626;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
  font-size: 1rem;
}

button[type="submit"]:hover:not(:disabled) {
  background: #b91c1c;
}

button:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}

.error {
  color: #dc2626;
  margin-bottom: 1rem;
  text-align: center;
}

.switch-auth {
  text-align: center;
  margin-top: 1rem;
}

.switch-auth a {
  color: #dc2626;
}
</style>
