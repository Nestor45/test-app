import { createStore } from 'vuex'
import router from '../router'
import { errorSweetAlert } from './../helpers/sweetAlertGlobals'

const store = createStore({
    state: {
        clientes:[]
    },
    getters: {
        getClientes() {
            return state.clientes
        }
    },
    mutations: {
        setClientes(state, payload) {
            state.clientes = payload
        }
    },
    actions: {

    },
    modules: {

    }
})
export default store