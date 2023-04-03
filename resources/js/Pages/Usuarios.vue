<template>
    <v-card max-width="1200" class="mx-auto">
        <v-card-title class="text-center">Usuarios</v-card-title>
        <div class="d-flex justify-end">
            <v-btn elevation="8" @click="agregarCliente()">Agregar nuevo Usuario</v-btn>
        </div>
        <div class="text-center">
            <v-table>
                <thead>
                    <tr>
                        <th class="text-center"> Nombre </th>
                        <th class="text-center"> Razon Social </th>
                        <th class="text-center"> Accions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in clientes" :key="item.cliente_id">
                        <td>{{ item.nombre }}</td>
                        <td>{{ item.razon_social }}</td>
                        <td>
                            <th><v-icon @click="editarCliente(item)">mdi-pencil</v-icon></th>
                            <th><v-icon @click="eliminarCliente(item)">mdi-delete</v-icon></th>
                        </td>
                    </tr>
                </tbody>
            </v-table>
        </div>
    </v-card>
    <v-dialog v-model="agregarClienteM" max-width="50rem">
        <v-card>
            <v-card-title>{{title}} Cliente</v-card-title>
            <v-form>
                <v-text-field v-model="cliente.nombre" :rules="nombreClienteRules" label="Nombre Cliente" ></v-text-field>
                <v-text-field v-model="cliente.razon_social" :rules="razonSocialRules" label="Razón Social" ></v-text-field>
                <div v-if="title == 'Editar'">
                    <v-btn @click="guardarCliente()" block class="mt-2" color="primary">Editar Cliente</v-btn>
                </div>
                <div v-else>
                    <v-btn @click="guardarCliente()" block class="mt-2" color="primary">Agregar Cliente</v-btn>
                </div>
            </v-form>
        </v-card>
    </v-dialog>
</template>

<script>
import axios from 'axios';
import { errorSweetAlert } from "../helpers/sweetAlertGlobals"

  export default {
    data () {
      return {
        title: '',
        clientes: [],
        agregarClienteM: false,
        cliente: {
            nombre: '',
            razon_social: ''
        },
        EditCliente: {
            cliente_id:'',
            nombre: '',
            razon_social: ''
        },
        nombreClienteRules: [ value => {
          if (value?.length > 2) return true
          return 'Necesita ingresar un nombre'
        }, ],
        razonSocialRules: [ value => {
          if (value?.length > 2) return true
          return 'Necesita ingresar un nombre'
        },],
      }
    },
    computed: {
        clientesStore() {
            return this.$store.getters.getClientes
        }
    }, 
    created() {
        this.getClientes()
    },
    methods: {
        async getClientes() {
            try {
                let response = await axios.get('/api/clientes')
                if (response.status === 200) {
                    if (response.data.status === 'ok') {
                        this.$store.commit('setClientes', response.data.clientes)
                        this.clientes = response.data.clientes
                    } else {
                        alert('Ocurrió un error al obtener los clientes')
                    }
                } else {
                    alert('Ocurrió un error al obtener los clientes')
                }
            } catch (error) {
                alert('Ocurrió un error al obtener los clientes')
            }
        },
        agregarCliente() {
            this.title = 'Agregar'
            this.agregarClienteM = true
        },
        cerrarModalCliente() {
            this.agregarClienteM = false
            this.cliente.nombre = ''
            this.cliente.razon_social = ''
        },
        async guardarCliente() {
            try {
                if ( this.cliente.nombre === '' && this.cliente.razon_social === ''){
                    alert('Los datos esta vacios')
                } else {
                    if(this.title === 'Editar') {
                        console.log(this.cliente.cliente_id)
                        let response = await axios.post('/api/editar-cliente', this.cliente)
                        console.log(response)
                        if (response.status === 200) {
                            if (response.data.status === 'ok') {
                                this.$store.commit('setClientes', response.data.cliente)
                                this.cerrarModalCliente();
                            } else {
                                alert('Ocurrió un error al editar el cliente')
                            }
                        } else if (response.status === 201) {
                            this.cerrarModalCliente();
                            alert('El cliente ya existe')
                        }
                    } else {
                        let response = await axios.post('/api/registrar-cliente', this.cliente)
                        if (response.status === 200) {
                            if (response.data.status === 'ok') {
                                this.clientes.push(response.data.cliente)
                                this.$store.commit('setClientes', response.data.cliente)
                                this.cerrarModalCliente();
                            } else {
                                alert('Ocurrió un error al registrar el cliente')
                            }
                        } else if (response.status === 201) {
                            this.cerrarModalCliente();
                            alert('El cliente ya existe')
                        }
                    }
                }
            } catch (error) {
                this.cerrarModalCliente();
                alert('Ocurrió un error al registrar el cliente')
            }
        },
        async editarCliente(clientesStore) {
            this.title = 'Editar'
            this.cliente.cliente_id = clientesStore.cliente_id
            this.cliente.nombre = clientesStore.nombre
            this.cliente.razon_social = clientesStore.razon_social
            this.agregarClienteM = true
        },
        async eliminarCliente(clientesStore){
            this.cliente.cliente_id = clientesStore.cliente_id
            try {
                let response = await axios.post('/api/eliminar-cliente', this.cliente)
                if (response.status === 200) {
                    if (response.data.status === 'ok') {
                        alert('Cliente eliminado con exito')
                    } else {
                        alert('Ocurrió un error al eliminar el cliente')
                    }
                } else if (response.status === 201) {
                    this.cerrarModalCliente();
                    alert('El cliente ya existe')
                }
            } catch (error) {
                alert('Ocurrio un error al eliminar un cliente')
            }
        }
    }
  }
</script>