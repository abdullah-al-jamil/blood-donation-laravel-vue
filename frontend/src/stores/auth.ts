import { defineStore } from 'pinia'
import { ref, computed } from 'vue'
import api from '@/api/axios'

export const useAuthStore = defineStore('auth', () => {
  const user = ref<any>(JSON.parse(localStorage.getItem('user') || 'null'))
  const token = ref<string | null>(localStorage.getItem('token'))

  const isAuthenticated = computed(() => !!token.value)
  const isAdmin = computed(() => user.value?.role === 'admin')
  const isDonor = computed(() => user.value?.role === 'donor')

  async function login(email: string, password: string) {
    const { data } = await api.post('/login', { email, password })
    token.value = data.token
    user.value = data.user
    localStorage.setItem('token', data.token)
    localStorage.setItem('user', JSON.stringify(data.user))
    return data
  }

  async function register(formData: any) {
    const { data } = await api.post('/register', formData)
    token.value = data.token
    user.value = data.user
    localStorage.setItem('token', data.token)
    localStorage.setItem('user', JSON.stringify(data.user))
    return data
  }

  async function logout() {
    try { await api.post('/logout') } catch {}
    token.value = null
    user.value = null
    localStorage.removeItem('token')
    localStorage.removeItem('user')
  }

  async function fetchUser() {
    try {
      const { data } = await api.get('/me')
      user.value = data.data
      localStorage.setItem('user', JSON.stringify(data.data))
    } catch {
      await logout()
    }
  }

  async function updateProfile(formData: any) {
    const { data } = await api.put('/profile', formData)
    user.value = data.data
    localStorage.setItem('user', JSON.stringify(data.data))
    return data
  }

  return { user, token, isAuthenticated, isAdmin, isDonor, login, register, logout, fetchUser, updateProfile }
})
