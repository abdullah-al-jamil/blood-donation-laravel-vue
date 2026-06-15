import api from './axios'

export function login(email: string, password: string) {
  return api.post('/login', { email, password })
}

export function register(data: any) {
  return api.post('/register', data)
}

export function logout() {
  return api.post('/logout')
}

export function me() {
  return api.get('/me')
}

export function updateProfile(data: any) {
  return api.put('/profile', data)
}
