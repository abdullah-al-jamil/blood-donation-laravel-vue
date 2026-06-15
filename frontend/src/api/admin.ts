import api from './axios'

export function getDashboard() {
  return api.get('/admin/dashboard')
}

export function getCenters() {
  return api.get('/admin/centers')
}

export function getCenter(id: number) {
  return api.get(`/admin/centers/${id}`)
}

export function createCenter(data: any) {
  return api.post('/admin/centers', data)
}

export function updateCenter(id: number, data: any) {
  return api.put(`/admin/centers/${id}`, data)
}

export function deleteCenter(id: number) {
  return api.delete(`/admin/centers/${id}`)
}

export function getAllAppointments() {
  return api.get('/admin/appointments')
}

export function getAppointment(id: number) {
  return api.get(`/admin/appointments/${id}`)
}

export function updateAppointment(id: number, data: any) {
  return api.put(`/admin/appointments/${id}`, data)
}

export function deleteAppointment(id: number) {
  return api.delete(`/admin/appointments/${id}`)
}

export function getDonations() {
  return api.get('/admin/donations')
}

export function getDonation(id: number) {
  return api.get(`/admin/donations/${id}`)
}

export function createDonation(data: any) {
  return api.post('/admin/donations', data)
}

export function updateDonation(id: number, data: any) {
  return api.put(`/admin/donations/${id}`, data)
}

export function deleteDonation(id: number) {
  return api.delete(`/admin/donations/${id}`)
}

export function getBloodRequests() {
  return api.get('/admin/blood-requests')
}

export function deleteBloodRequest(id: number) {
  return api.delete(`/admin/blood-requests/${id}`)
}

export function getBloodRequest(id: number) {
  return api.get(`/admin/blood-requests/${id}`)
}

export function updateBloodRequest(id: number, data: any) {
  return api.put(`/admin/blood-requests/${id}`, data)
}

export function fulfillBloodRequest(id: number) {
  return api.put(`/admin/blood-requests/${id}/fulfill`)
}

export function getInventory() {
  return api.get('/admin/inventory')
}

export function getInventorySummary() {
  return api.get('/admin/inventory/summary')
}

export function createInventory(data: any) {
  return api.post('/admin/inventory', data)
}

export function updateInventory(id: number, data: any) {
  return api.put(`/admin/inventory/${id}`, data)
}

export function deleteInventory(id: number) {
  return api.delete(`/admin/inventory/${id}`)
}

export function getDonors() {
  return api.get('/admin/donors')
}

export function getDonor(id: number) {
  return api.get(`/admin/donors/${id}`)
}

export function toggleDonorEligibility(id: number) {
  return api.post(`/admin/donors/${id}/toggle-eligibility`)
}
