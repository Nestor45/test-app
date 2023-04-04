<template>
    <v-card max-width="1200" class="mx-auto">
        <v-card-title class="text-center"> Facturas </v-card-title>
        <div class="d-flex justify-end">
            <v-btn elevation="8" @click="agregarFactura()">Agregar Factura</v-btn>
        </div>
        <div class="text-center">
            <v-table>
                <thead>
                    <tr>
                        <th> Codigo Factura </th>
                        <th> Fecha de factura </th>
                        <th> Codigo de pedido </th>
                        <th> Fecha pedido </th>
                        <th> Nombre del Articulo  </th>
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
                        <td>{{ item.articulo_nombre }}</td>
                        <td>{{ item.nombre_cliente }}</td>
                        <td>{{ item.cliente_razon_social }}</td>
                        <td><v-icon @click="verFactura(item)">mdi-file</v-icon></td>
                        <td>
                            <v-icon @click="editarFactura(item)">mdi-pencil</v-icon>
                            <v-icon @click="eliminarFactura(item)">mdi-delete</v-icon>
                        </td>
                    </tr>
                </tbody>
            </v-table>
            <v-dialog v-model="agregarFacturaM" max-width="50rem">
                <v-card>
                    <v-card-title>{{title}} Factura</v-card-title>
                    <v-form>
                        <v-text-field v-model="factura.codigo_factura" label="Codigo de Factura"></v-text-field>
                        <v-text-field v-model="factura.cantidad" label="Cantidad"></v-text-field>
                        <v-text-field v-model="factura.fecha_factura" type="date" label="Fecha de la Factura"></v-text-field>
                        
                        <select v-model="factura.pedido_id" label="Pedido"  class="form-control minimal custom-select" required>
                            <option v-for="item in pedidos" :key="item.pedido_id" :value="item.pedido_id"> {{item.codigo_pedido}}</option>
                        </select>
                        <select v-model="factura.articulo_id" label="Articulo"  class="form-control minimal custom-select" required>
                            <option v-for="item in articulos" :key="item.articulo_id" :value="item.articulo_id">{{item.nombre}}</option>
                        </select>

                        <div v-if="title == 'Editar'">
                            <v-btn @click="guardarFactura()" block class="mt-2" color="primary">Editar Pedido</v-btn>
                        </div>
                        <div v-else>
                            <v-btn @click="guardarFactura()" block class="mt-2" color="primary">Agregar Pedido</v-btn>
                        </div>
                    </v-form>
                </v-card>
            </v-dialog>

            <v-dialog v-model="verFacturaModal" max-width="50rem">
                <v-card>
                    <v-card-title>
                        Guardar factura
                    </v-card-title>
                    <v-form>
                        <v-file-input
                            v-model="archivopdf.archivo"
                            show-size
                            label="Constancia firmada"
                            accept="application/pdf"
                            variant="outlined"
                        ></v-file-input>
                        <v-btn class="" color="#c4f45d"
                            @click="guardarFacturaPDF()"
                        >
                            Guardar 
                        </v-btn>
                    </v-form>
                </v-card>
            </v-dialog>
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
                verFacturaModal: false,
                factura: {
                    codigo_factura: '',
                    fecha_factura: '',
                    pedido_id: '',
                    articulo_id: '',
                    cantidad: '',
                },
                Editfactura: {
                    codigo_factura: '',
                    fecha_factura: '',
                    pedido_id: '',
                    articulo_id: '',
                    cantidad: '',
                },
                archivopdf: {
                    factura_id: '',
                    archivo: ''
                }
            }
        },
        created() {
            this.getFacturas()
            this.getPedidos()
            this.getArticulos()
        },
        computed: {
            facturas() {
                return this.$store.getters.getFacturas
            },
            articulos() {
                return this.$store.getters.getArticulos
            },
            pedidos() {
                return this.$store.getters.getPedidos
            },
        },
        methods: {
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
            async getArticulos() {
                try {
                    let response = await axios.get('/api/articulos')
                    console.log(response)
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
            async getFacturas() {
                try {
                    let response = await axios.get('/api/facturas')
                    
                    if (response.status === 200) {
                        if (response.data.status === 'ok') {
                            this.$store.commit('setFacturas', response.data.facturas)
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
            async guardarFactura() {
                try {
                    if (this.title === 'Editar') {
                        let response = await axios.post('/api/editar-factura', this.factura)
                        if (response.status === 200) {
                            if (response.data.status === 'ok') {
                                this.$store.commit('setFacturas', response.data.factura)
                                this.getFacturas()
                                this.cerrarModal()
                            }
                        } else {
                            alert('Ocurrió un error al editar la factura')
                        }
                    } else {
                        let response = await axios.post('/api/agregar-factura', this.factura)
                        if (response.status === 200) {
                            if (response.data.status === 'ok') {
                                this.$store.commit('setFacturas', response.data.factura)
                                this.getFacturas()
                                this.cerrarModal()
                            }
                        } else {
                            alert('Ocurrió un error al registrar la factura')
                        }
                    }
                } catch (error) {
                    alert('Ocurrió un error al registrar la factura')
                }
            },
            async verFactura(item) {
                console.log(item)
                this.archivopdf.factura_id = item
                this.verFacturaModal =  true
            },
            async guardarFacturaPDF() {
                try {
                    let response = await axios.post('/api/subir-factura', this.archivopdf, {
                        headers: {
                            'Content-Type': 'multipart/form-data',
                        }
                    })
                    if (response.status === 200) {
                        if (response.data.status === 'ok') {
                            this.$store.commit('setFactura', response.data.facturas)
                            this.getFacturas()
                            this.verFactura = false
                        } else {
                            alert('Ocurrió un error al subir factura')
                        }
                    }
                } catch (error) {
                    alert('Ocurrió un error subir la factura')
                }

            },
            agregarFactura() {
                this.title = 'Agregar'
                this.agregarFacturaM = true
            },
            cerrarModal() {
                this.agregarFacturaM = false
            },
            editarFactura(item) {
                this.title = 'Editar'
                console.log(item)
                this.factura.factura_id = item.factura_id
                this.factura.articulo_id = item.articulo_id
                this.factura.cantidad = item.cantidad
                this.factura.codigo_factura = item.codigo_factura
                this.factura.fecha_factura = item.fecha_factura
                this.factura.pedido_id = item.pedido_id
                this.agregarFacturaM = true
            },
            async eliminarFactura(item){
                this.factura.factura_id = item.factura_id
                try {
                    let response = await axios.post('/api/eliminar-factura', this.factura)
                    if (response.status === 200) {
                        if (response.data.status === 'ok') {
                            this.getFacturas()
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