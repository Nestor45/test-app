import { createWebHistory, createRouter } from "vue-router";
import store from './store'
import Home from './Layouts/Home.vue'
import Usuarios from './Pages/Usuarios.vue'
import Articulos from './Pages/Articulos.vue'
import Pedidos from './Pages/Pedidos.vue'
import Facturas from './Pages/Facturas.vue'

const routes = [
    {
        path: '/',
        name: 'Home',
        component: Home,
    },
    {
        path: '/Usuarios',
        name: 'Usuarios',
        component: Usuarios,
    },
    {
        path: '/Articulos',
        name: 'Articulos',
        component: Articulos
    },
    {
        path: '/Pedidos',
        name: 'Pedidos',
        component: Pedidos
    },
    {
        path: '/Facturas',
        name: 'Facturas',
        component: Facturas
    }
]
const router = createRouter({
    history: createWebHistory(),
    routes
})
export default router