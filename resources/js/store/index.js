import { createStore } from 'vuex'

const store = createStore({
    state: {
        clientes:[],
        articulos:[],
        pedidos:[],
        facturas: [],
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
    getters: {
        getClientes(state) {
            return state.clientes
        },
        getArticulos(state) {
            return state.articulos
        },
        getPedidos(state) {
            return state.pedidos
        },
        getFacturas(state) {
            return state.facturas
        }
    }
})
export default store;