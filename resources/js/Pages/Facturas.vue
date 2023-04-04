<template>
    <v-card max-width="1200" class="mx-auto">
        <v-card-title class="text-center"> Facturas </v-card-title>
        <div class="d-flex justify-end">
            <v-btn elevation="8" class="">Agregar Factura</v-btn>
        </div>
        <div class="text-center">
            <v-table>
                <thead>
                    <tr>
                        <th> Codigo Factura </th>
                        <th> Fecha de factura </th>
                        <th> Codigo de pedido </th>
                        <th> Fecha pedido </th>
                        <th> Nombre cliente </th>
                        <th> Razon social </th>
                        <th> Factura archivo</th>
                        <th> Actions </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in facturas" :key="item.factura_id">
                        <td>{{ item.codigo_factura }}</td>
                        <td>{{ item.fecha_factura }}</td>
                        <td>{{ item.codigo_pedido }}</td>
                        <td>{{ item.fecha_pedido }}</td>
                        <td>{{ item.nombre_cliente }}</td>
                        <td>{{ item.cliente_razon_social }}</td>
                        <td><v-icon @click="verFactura(item.factura_id)">mdi-file</v-icon></td>
                        <td>
                            <v-icon @click="">mdi-pencil</v-icon>
                            <v-icon @click="eliminarFactura(item)">mdi-delete</v-icon>
                        </td>
                    </tr>
                </tbody>
            </v-table>
        </div>
    </v-card>
</template>
<script>
    import axios from 'axios';
    export default {
        data() {
            return {
                title: '',
                agregarFacturaM: false,
                facturas:[],
                verFacturaModal: false,
                factura: {
                    codigo_factura: '',
                    fecha_factura: '',
                    pedido_id: '',
                    articulo_id: '',
                    cantidad: ''
                }
            }
        },
        created() {
            this.getFacturas()
        },
        methods: {
            async getFacturas() {
                try {
                    let response = await axios.get('/api/facturas')
                    console.log(response)
                    if (response.status === 200) {
                        if (response.data.status === 'ok') {
                            this.$store.commit('setFacturas', response.data.facturas)
                            this.facturas = response.data.facturas
                        } else {
                            errorSweetAlert('Ocurrió un error al obtener las facturas')
                        }
                    } else {
                        errorSweetAlert('Ocurrió un error al obtener las facturas')
                    }
                } catch (error) {
                    errorSweetAlert('Ocurrió un error al obtener las facturas')
                }
            },
            async verFactura(item) {
                console.log(item)
            },
            agregarFactura() {
                this.title = 'Agregar'
                this.agregarFacturaM = true
            },
            cerrarModal() {
                this.agregarFacturaM = false
            },
            async eliminarFactura(item){
                this.factura.factura_id = item.factura_id
                try {
                    let response = await axios.post('/api/eliminar-factura', this.factura)
                    if (response.status === 200) {
                        if (response.data.status === 'ok') {
                            this.facturas.pop(item.factura_id)
                            alert('factura eliminado con exito')
                        } else {
                            alert('Ocurrió un error al eliminar el factura')
                        }
                    }
                } catch (error) {
                    alert('Ocurrio un error al eliminar un factura')
                }
            }
        }
    }
</script>