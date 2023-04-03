<template>
    <v-card max-width="1200" class="mx-auto">
        <v-card-title class="text-center">Pedidos</v-card-title>
        <div class="d-flex justify-end">
            <v-btn elevation="8" class="">Agregar nuevo Pedido</v-btn>
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
                    </tr>
                </tbody>
            </v-table>
        </div>
    </v-card>
</template>

<script>
    import axios from 'axios';
    export default {
        data () {
            return {
                pedidos: [],
            }
        },
        created() {
            this.getPedidos()
        },
        methods: {
            async getPedidos() {
                try {
                    let response = await axios.get('/api/pedidos')
                    console.log(response)
                    if (response.status === 200) {
                        if (response.data.status === 'ok') {
                            this.$store.commit('setPedidos', response.data.pedidos)
                            this.pedidos = response.data.pedidos
                        } else {
                            errorSweetAlert('Ocurrió un error al obtener los pedidos')
                        }
                    } else {
                        errorSweetAlert('Ocurrió un error al obtener los pedidos')
                    }
                } catch (error) {
                    errorSweetAlert('Ocurrió un error al obtener los pedidos')
                }
            }
        }
    }
</script>