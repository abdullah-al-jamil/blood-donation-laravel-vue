import { defineStore } from 'pinia'
import axios from 'axios'

const api = axios.create({
  baseURL: 'http://localhost:8000/api',
  withCredentials: true,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  }
})

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: localStorage.getItem('token') || null,
    loading: false,
    error: null
  }),

  getters: {
    isAuthenticated: (state) => !!state.token,
    isDonor: (state) => state.user?.role === 'donor',
    isRecipient: (state) => state.user?.role === 'recipient',
    isAdmin: (state) => state.user?.role === 'admin'
  },

  actions: {
    async login(credentials) {
      this.loading = true
      this.error = null
      try {
        const response = await api.post('/login', credentials)
        this.token = response.data.token
        this.user = response.data.user
        localStorage.setItem('token', this.token)
        api.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
        return true
      } catch (error) {
        this.error = error.response?.data?.message || 'Login failed'
        return false
      } finally {
        this.loading = false
      }
    },

    async register(userData) {
      this.loading = true
      this.error = null
      try {
        const response = await api.post('/register', userData)
        this.token = response.data.token
        this.user = response.data.user
        localStorage.setItem('token', this.token)
        api.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
        return true
      } catch (error) {
        this.error = error.response?.data?.message || 'Registration failed'
        return false
      } finally {
        this.loading = false
      }
    },

    async logout() {
      try {
        await api.post('/logout')
      } catch (error) {
        console.error('Logout error:', error)
      } finally {
        this.token = null
        this.user = null
        localStorage.removeItem('token')
        delete api.defaults.headers.common['Authorization']
      }
    },

    async fetchUser() {
      if (!this.token) return
      try {
        api.defaults.headers.common['Authorization'] = `Bearer ${this.token}`
        const response = await api.get('/user')
        this.user = response.data
      } catch (error) {
        this.logout()
      }
    },

    initAuth() {
      const token = localStorage.getItem('token')
      if (token) {
        this.token = token
        api.defaults.headers.common['Authorization'] = `Bearer ${token}`
        this.fetchUser()
      }
    }
  }
})

export { api }
