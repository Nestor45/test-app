<template>
    <v-card max-width="1200" class="mx-auto">
        <v-card-title class="text-center">Articulos</v-card-title>
        <div class="d-flex justify-end">
            <v-btn elevation="8" @click="agregarArticulo()">Agregar nuevo Articulo</v-btn>
        </div>
        <div class="text-center">
            <v-table>
                <thead>
                    <tr>
                        <th class="text-center">Codigo</th>
                        <th class="text-center">Nombre</th>
                        <th class="text-center">Precio</th>
                        <th class="text-center">Accions</th>
                    </tr>
                </thead>
                <tbody>
                        <tr v-for="item in articulos" :key="item.articulo_id">
                            <td>{{ item.codigo_articulo }}</td>
                            <td>{{ item.nombre }}</td>
                            <td>${{ item.precio }}</td>
                            <td>
                                <v-icon @click="editarArticulo(item)">mdi-pencil</v-icon>
                                <v-icon @click="eliminarArticulo(item)">mdi-delete</v-icon>
                            </td>
                        </tr>
                    </tbody>
            </v-table>
        </div>
    </v-card>
    <v-dialog v-model="agregarArticuloM" max-width="50rem">
        <v-card>
            <v-card-title>{{title}} Articulo</v-card-title>
            <v-form>
                <v-text-field v-model="articulo.codigo_articulo" :rules="codigoArticuloRules" label="Codigo Articulo" ></v-text-field>
                <v-text-field v-model="articulo.nombre" :rules="nombreClienteRules" label="Nombre de Articulo" ></v-text-field>
                <v-text-field prefix="$" v-model="articulo.precio" :rules="precioRules" label="Precio" ></v-text-field>
                <div v-if="title == 'Editar'">
                    <v-btn @click="guardarArticulo()" block class="mt-2" color="primary">Editar Articulo</v-btn>
                </div>
                <div v-else>
                    <v-btn @click="guardarArticulo()" block class="mt-2" color="primary">Agregar Articulo</v-btn>
                </div>
            </v-form>
        </v-card>
    </v-dialog>
</template>
<script>
import axios from 'axios';

    export default {
        data() {
            return {
                title: '',
                agregarArticuloM: false,
                articulo: {
                    codigo_articulo: '',
                    nombre: '',
                    precio: ''
                },
                codigoArticuloRules: [ value => {
                if (value?.length > 1) return true
                    return 'Necesita ingresar un codigo de articulo'
                },],
                nombreClienteRules: [ value => {
                if (value?.length > 2) return true
                    return 'Necesita ingresar un nombre para el articulo'
                },],
                precioRules: [ value => {
                if (value?.length > 2) return true
                    return 'Necesita ingresar un precio valido para el articulo'
                },],
            }
        },
        created() {
            this.getArticulos()
        },
        computed: {
            articulos() {
                return this.$store.getters.getArticulos
            }
        },
        methods: {
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
            agregarArticulo() {
                this.title = 'Agregar'
                this.agregarArticuloM = true
            },
            cerrarModal() {
                this.agregarArticuloM = false
                this.articulo.codigo_articulo = ''
                this.articulo.nombre = ''
                this.articulo.precio = ''
            },
            async guardarArticulo() {
                try {
                    if (this.articulo.codigo_articulo === '' && this.articulo.nombre === '' && this.articulo.precio === '') {
                        alert('Los datos esta vacios')
                    } else {
                        if (isNaN(this.articulo.precio)) {
                            alert('Error el ingrear el precio debe ser numerico')
                        } else {
                            if (this.title === 'Editar') {
                                let response = await axios.post('/api/editar-articulo', this.articulo)
                                if (response.status === 200) {
                                    if (response.data.status === 'ok') {
                                        this.getArticulos()
                                        this.cerrarModal();
                                    } else {
                                        alert('Ocurrió un error al editar el articulo')
                                    }
                                } else if (response.status === 201) {
                                    this.cerrarModal();
                                    alert('El articulo ya existe')
                                }
                            } else {
                                console.log(this.articulo)
                                let response = await axios.post('/api/registrar-articulo', this.articulo)
                                console.log(response)
                                if (response.status === 200) {
                                    if (response.data.status === 'ok') {
                                        this.$store.commit('setArticulos', response.data.articulo)
                                        this.getArticulos()
                                        this.cerrarModal();
                                    } else {
                                        alert('Ocurrió un error al registrar el articulo')
                                    }
                                } else if (response.status === 201) {
                                    this.cerrarModal();
                                    alert('El articulo ya existe')
                                }
                            }
                        }
                    }
                } catch (error) {
                    alert('Ocurrió un error al guarda el articulos')
                }
            },
            editarArticulo(item) {
                this.title = 'Editar'
                this.articulo.articulo_id = item.articulo_id
                this.articulo.codigo_articulo = item.codigo_articulo
                this.articulo.nombre = item.nombre
                this.articulo.precio = item.precio
                this.agregarArticuloM = true
            },
            async eliminarArticulo(item){
                this.articulo.articulo_id = item.articulo_id
                try {
                    let response = await axios.post('/api/eliminar-articulo', this.articulo)
                    if (response.status === 200) {
                        if (response.data.status === 'ok') {
                            alert('Articulo eliminado con exito')
                            this.getArticulos()
                        } else {
                            alert('Ocurrió un error al eliminar el Articulo')
                        }
                    }
                } catch (error) {
                    alert('Ocurrio un error al eliminar un Articulo')
                }
            }
        }
    }
</script>