<template>
    <v-card max-width="1200" class="mx-auto">
        <v-card-title class="text-center">Articulos</v-card-title>
        <div class="d-flex justify-end">
            <v-btn elevation="8">Agregar nuevo Usuario</v-btn>
        </div>
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
                        <td>{{ item.precio }}</td>
                    </tr>
                </tbody>
        </v-table>
    </v-card>
</template>
<script>
import axios from 'axios';

    export default {
        data() {
            return {
                articulos: [],
            }
        },
        created() {
            this.getArticulos()
        },
        methods: {
            async getArticulos() {
                try {
                    let response = await axios.get('/api/articulos')
                    console.log(response)
                    if (response.status === 200) {
                        if (response.data.status === 'ok') {
                            this.$store.commit('setArticulos', response.data.articulos)
                            this.articulos = response.data.articulos
                        } else {
                            errorSweetAlert('Ocurrió un error al obtener los articulos')
                        }
                    } else {
                        errorSweetAlert('Ocurrió un error al obtener los articulos')
                    }
                } catch (error) {
                    errorSweetAlert('Ocurrió un error al obtener los articulos')
                }
            }
        }
    }
</script>