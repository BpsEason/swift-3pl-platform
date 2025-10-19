import { createRouter, createWebHistory } from 'vue-router'
import OrderImport from './views/OrderImport.vue'
import Inventory from './views/Inventory.vue'
import Report from './views/Report.vue'

const routes = [
  { path: '/', component: OrderImport, name: 'OrderImport', meta: { title: '訂單匯入' } },
  { path: '/inventory', component: Inventory, name: 'Inventory', meta: { title: '庫存查詢' } },
  { path: '/report', component: Report, name: 'Report', meta: { title: '效率報表' } },
]

export const router = createRouter({
  history: createWebHistory(),
  routes,
})
