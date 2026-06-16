import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    {
      path: '/',
      name: 'home',
      component: () => import('@/views/HomeView.vue'),
    },
    {
      path: '/login',
      name: 'login',
      component: () => import('@/views/auth/LoginView.vue'),
      meta: { guest: true },
    },
    {
      path: '/register',
      name: 'register',
      component: () => import('@/views/auth/RegisterView.vue'),
      meta: { guest: true },
    },
    {
      path: '/donor',
      component: () => import('@/layouts/DonorLayout.vue'),
      meta: { auth: true, role: 'donor' },
      children: [
        { path: '', name: 'donor.dashboard', component: () => import('@/views/donor/DashboardView.vue') },
        { path: 'profile', name: 'donor.profile', component: () => import('@/views/donor/ProfileView.vue') },
        { path: 'appointments', name: 'donor.appointments', component: () => import('@/views/donor/AppointmentsView.vue') },
        { path: 'donation-history', name: 'donor.history', component: () => import('@/views/donor/HistoryView.vue') },
        { path: 'blood-requests', name: 'donor.blood-requests', component: () => import('@/views/donor/BloodRequestsView.vue') },
      ],
    },
    {
      path: '/admin',
      component: () => import('@/layouts/AdminLayout.vue'),
      meta: { auth: true, role: 'admin' },
      children: [
        { path: '', name: 'admin.dashboard', component: () => import('@/views/admin/DashboardView.vue') },
        { path: 'donors', name: 'admin.donors', component: () => import('@/views/admin/DonorsView.vue') },
        { path: 'inventory', name: 'admin.inventory', component: () => import('@/views/admin/InventoryView.vue') },
        { path: 'requests', name: 'admin.requests', component: () => import('@/views/admin/RequestsView.vue') },
        { path: 'centers', name: 'admin.centers', component: () => import('@/views/admin/CentersView.vue') },
        { path: 'appointments', name: 'admin.appointments', component: () => import('@/views/admin/AppointmentsView.vue') },
        { path: 'donations', name: 'admin.donations', component: () => import('@/views/admin/DonationsView.vue') },
      ],
    },
    {
      path: '/:pathMatch(.*)*',
      redirect: '/',
    },
  ],
})

router.beforeEach((to, _from, next) => {
  const token = localStorage.getItem('token')
  const userStr = localStorage.getItem('user')
  let user: any = null
  try { user = userStr ? JSON.parse(userStr) : null } catch {}

  if (to.meta.auth && !token) {
    return next('/login')
  }

  if (to.meta.guest && token) {
    const guestRedirect = user?.role === 'admin' ? '/admin' : '/donor'
    if (to.path === guestRedirect) return next()
    return next(guestRedirect)
  }

  if (to.meta.role && user?.role !== to.meta.role) {
    const roleRedirect = user?.role === 'admin' ? '/admin' : '/donor'
    if (to.path === roleRedirect) return next()
    return next(roleRedirect)
  }

  next()
})

export default router
