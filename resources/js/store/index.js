import { createStore } from 'vuex'
import router from '../router'
import { errorSweetAlert } from './../helpers/sweetAlertGlobals'

const store = createStore({
    state: {
        clientes:[],
        articulos:[],
        pedidos:[],
        facturas: [],
    },
    getters: {
        getClientesStore() {
            console.log("algo"+state.clientes)
            return state.clientes
        },
        getArticulos() {
            return state.articulos
        },
        getPedidos() {
            return state.pedidos
        },
        getFacturas() {
            return state.facturas
        }
    },
    mutations: {
        setClientes(state, payload) {
            state.clientes = payload
        },
        setArticulos(state, payload) {
            state.articulos = payload
        },
        setPedidos(state, payload) {
            state.pedidos = payload
        },
        setFacturas(state, payload) {
            state.facturas = payload
        }
    },
    actions: {
        
    },
    modules: {

    }
})
export default store