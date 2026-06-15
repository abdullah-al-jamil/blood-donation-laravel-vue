import api from './axios'

export function getAppointments() {
  return api.get('/donor/appointments')
}

export function getAppointment(id: number) {
  return api.get(`/donor/appointments/${id}`)
}

export function createAppointment(data: any) {
  return api.post('/donor/appointments', data)
}

export function updateAppointment(id: number, data: any) {
  return api.put(`/donor/appointments/${id}`, data)
}

export function cancelAppointment(id: number) {
  return api.delete(`/donor/appointments/${id}`)
}

export function getDonations() {
  return api.get('/donor/donations')
}

export function getDonation(id: number) {
  return api.get(`/donor/donations/${id}`)
}

export function createBloodRequest(data: any) {
  return api.post('/blood-requests', data)
}
