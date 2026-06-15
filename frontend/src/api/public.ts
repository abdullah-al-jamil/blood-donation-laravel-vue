import api from './axios'

export function getCenters() {
  return api.get('/centers')
}

export function getCenter(id: number) {
  return api.get(`/centers/${id}`)
}

export function getBloodRequests(config?: any) {
  return api.get('/blood-requests', config)
}

export function getBloodRequest(id: number) {
  return api.get(`/blood-requests/${id}`)
}

export function getInventory() {
  return api.get('/inventory')
}

export function getInventorySummary() {
  return api.get('/inventory/summary')
}
