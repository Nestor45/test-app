<template>
    <v-card max-width="1200" class="mx-auto">
        <v-card-title class="text-center">Pedidos</v-card-title>
        <div class="d-flex justify-end">
            <v-btn elevation="8" @click="agregarPedido()">Agregar nuevo Pedido</v-btn>
        </div>
        <div class="text-center">
            <v-table>
                <thead>
                    <tr>
                        <th class="text-center">Cliente</th>
                        <th class="text-center">Codigo Pedido</th>
                        <th class="text-center">Fecha del Pedido</th>
                        <th class="text-center">Articulo</th>
                        <th class="text-center">Precio</th>
                        <th class="text-center">Codigo del articulo</th>
                        <th class="text-center">Accions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in pedidos" :key="item.pedido_id">
                        <td>{{ item.cliente_nombre }}</td>
                        <td>{{ item.codigo_pedido }}</td>
                        <td>{{ item.fecha_pedido }}</td>
                        <td>{{ item.articulo_nombre }}</td>
                        <td>${{ item.articulo_precio }}</td>
                        <td>{{ item.codigo_articulo }}</td>
                        <td>
                            <v-icon @click="">mdi-pencil</v-icon>
                            <v-icon @click="eliminarPedido(item)">mdi-delete</v-icon>
                        </td>
                    </tr>
                </tbody>
            </v-table>
        </div>
    </v-card>
    <v-dialog v-model="agregarPedidoM" max-width="50rem">
        <v-card>
            <v-card-title>{{title}} Pedido</v-card-title>
            <v-form>
                <v-text-field v-model="pedido.codigo_pedido" label="Codigo Pedido"></v-text-field>
                <v-text-field v-model="pedido.cantidad" label="Cantidad"></v-text-field>
                <v-text-field v-model="pedido.fecha_pedido" type="date" label="Fecha factura"></v-text-field>
                <v-select v-model="pedido.cliente_id" :items="clientesStore" label="Cliente" required>
                    
                </v-select>
                <div v-if="title == 'Editar'">
                    <v-btn @click="guardarCliente()" block class="mt-2" color="primary">Editar Pedido</v-btn>
                </div>
                <div v-else>
                    <v-btn @click="guardarCliente()" block class="mt-2" color="primary">Agregar Pedido</v-btn>
                </div>
            </v-form>
        </v-card>
    </v-dialog>
</template>

<script>
    import store from '@/store';
import axios from 'axios';
    export default {
        data () {
            return {
                title: '',
                agregarPedidoM: false,
                pedidos: [],
                articulos1: [],
                clientes: [],
                pedido: {
                    codigo_pedido: '',
                    fecha_pedido: '',
                    cliente_id: '',
                    articulo_id: '',
                    cantidad: '',
                },
                items: [
                    'Item 1',
                    'Item 2',
                    'Item 3',
                    'Item 4',
                ],
            }
        },
        created() {
            this.getPedidos()
            this.getClientes()
            this.getArticulos()
        },
        computed: {
            clientesStore() {
                return this.$store.getters.getClientesStore
            }
        },
        methods: {
            async getClientes() {
                try {
                    let response = await axios.get('/api/clientes')
                    if (response.status === 200) {
                        if (response.data.status === 'ok') {
                            this.$store.commit('setClientes', response.data.clientes)
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
            async getArticulos() {
                try {
                    let response = await axios.get('/api/articulos')
                    console.log(response)
                    if (response.status === 200) {
                        if (response.data.status === 'ok') {
                            this.$store.commit('setArticulos', response.data.articulos)
                            this.articulos1 = response.data.articulos
                        } else {
                            alert('Ocurrió un error al obtener los articulos')
                        }
                    } else {
                        alert('Ocurrió un error al obtener los articulos')
                    }
                } catch (error) {
                    alert('Ocurrió un error al obtener los articulos')
                }
            },
            async getPedidos() {
                try {
                    let response = await axios.get('/api/pedidos')
                    if (response.status === 200) {
                        if (response.data.status === 'ok') {
                            this.$store.commit('setPedidos', response.data.pedidos)
                            this.pedidos = response.data.pedidos
                        } else {
                            alert('Ocurrió un error al obtener los pedidos')
                        }
                    } else {
                        alert('Ocurrió un error al obtener los pedidos')
                    }
                } catch (error) {
                    alert('Ocurrió un error al obtener los pedidos')
                }
            },
            agregarPedido() {
                console.log(this.clientes)
                console.log(this.pedidos)
                this.title = 'Agregar'
                this.agregarPedidoM = true
            },
            cerrarModal() {
                this.agregarPedidoM = false
                this.pedido = {}
            },
            async guardarPedido(){
                try {
                    if (true){

                    } else {

                    }
                } catch (error) {
                    this.cerrarModal();
                    alert('Ocurrió un error al registrar el pedido')
                }
            },  
            async eliminarPedido(item) {
                this.pedido.pedido_id = item.pedido_id
                try {
                    let response = await axios.post('/api/eliminar-pedido', this.pedido)
                    if (response.status === 200) {
                        if (response.data.status === 'ok') {
                            this.pedidos.pop(item.pedido_id)
                            alert('Pedido eliminado con exito')
                        } else {
                            alert('Ocurrió un error al eliminar el pedido')
                        }
                    } else {
                        alert('Ocurrió un error al eliminar el pedido')
                    }
                } catch (error) {
                    alert('Ocurrio un error al eliminar un Pedido')
                }
            }

        }
    }
</script>