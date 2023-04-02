import { createWebHistory, createRouter } from "vue-router";
import store from './store'
import Usuarios from './Pages/Usuarios.vue'
const routes = [
    {
        path: '/',
        name: 'Usuarios',
        component: Usuarios,
    }
]
const router = createRouter({
    history: createWebHistory(),
    routes
})
export default router