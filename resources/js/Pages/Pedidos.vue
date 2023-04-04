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
                            <v-icon @click="editarPedido(item)">mdi-pencil</v-icon>
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
                <v-text-field v-model="pedido.codigo_pedido" label="Codigo de Pedido"></v-text-field>
                <v-text-field v-model="pedido.cantidad" label="Cantidad"></v-text-field>
                <v-text-field v-model="pedido.fecha_pedido" type="date" label="Fecha del Pedido"></v-text-field>
                <select v-model="pedido.cliente_id" label="Cliente" class="form-control minimal custom-select" required>
                    <option v-for="item in clientes" :key="item.cliente_id" :value="item.cliente_id">{{item.nombre}}</option>
                </select>
                <select v-model="pedido.articulo_id" label="Articulo" class="form-control minimal custom-select" required>
                    <option v-for="item in articulos" :key="item.articulo_id" :value="item.articulo_id">{{item.nombre}}</option>
                </select>
                <div v-if="title == 'Editar'">
                    <v-btn @click="guardarPedido()" block class="mt-2" color="primary">Editar Pedido</v-btn>
                </div>
                <div v-else>
                    <v-btn @click="guardarPedido()" block class="mt-2" color="primary">Agregar Pedido</v-btn>
                </div>
            </v-form>
        </v-card>
    </v-dialog>
</template>

<script>
import axios from 'axios';
    export default {
        data () {
            return {
                title: '',
                agregarPedidoM: false,
                pedido: {
                    codigo_pedido: '',
                    fecha_pedido: '',
                    cliente_id: '',
                    articulo_id: '',
                    cantidad: '',

                },
            }
        },
        created() {
            this.getPedidos()
            this.getClientes()
            this.getArticulos()
        },
        computed: {
            pedidos() {
                return this.$store.getters.getPedidos
            },
            clientes() {
                return this.$store.getters.getClientes
            },
            articulos() {
                return this.$store.getters.getArticulos
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
                    if (response.status === 200) {
                        if (response.data.status === 'ok') {
                            this.$store.commit('setArticulos', response.data.articulos)
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
                this.title = 'Agregar'
                this.agregarPedidoM = true
            },
            cerrarModal() {
                this.agregarPedidoM = false
                this.pedido = {}
            },
            editarPedido(item) {
                this.title = 'Editar'
                this.pedido.articulo_id = item.articulo_id
                this.pedido.cliente_id = item.cliente_id
                this.pedido.cantidad = item.cantidad
                this.pedido.pedido_id = item.pedido_id
                this.pedido.codigo_pedido = item.codigo_pedido
                this.pedido.codigo_articulo = item.codigo_articulo
                this.pedido.fecha_pedido = item.fecha_pedido
                this.pedido.cliente_nombre = item.cliente_nombre
                this.agregarPedidoM = true
            },
            async guardarPedido() {
                console.log(this.pedido)
                try {
                    if (this.title === 'Editar'){
                        console.log(this.pedido)
                        let response = await axios.post('/api/editar-pedido', this.pedido)
                        if (response.status === 200) {
                            if (response.data.status === 'ok') {
                                this.$store.commit('setPedidos', response.data.pedido)
                                this.getPedidos()
                                this.cerrarModal();
                            } else {
                                alert('Ocurrió un error al registrar el pedido')
                            }
                        } else if (response.status === 201) {
                            this.cerrarModal();
                            alert('El pedido ya existe')
                        }
                    } else {
                        console.log(this.pedido)
                        let response = await axios.post('/api/agregar-pedido', this.pedido)
                        if (response.status === 200) {
                            if (response.data.status === 'ok') {
                                this.$store.commit('setPedidos', response.data.pedido)
                                this.getPedidos()
                                this.cerrarModal();
                            } else {
                                alert('Ocurrió un error al registrar el pedido')
                            }
                        } else if (response.status === 201) {
                            this.cerrarModal();
                            alert('El pedido ya existe')
                        }
                    }
                } catch (error) {
                    this.cerrarModal();
                    alert('Ocurrió un error al registrar el pedido')
                }
            },  
            async eliminarPedido(item) {
                console.log(item)
                this.pedido.pedido_id = item.pedido_id
                try {
                    let response = await axios.post('/api/eliminar-pedido', this.pedido)
                    if (response.status === 200) {
                        if (response.data.status === 'ok') {
                            this.getPedidos()
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