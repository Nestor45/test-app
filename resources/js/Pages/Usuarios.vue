<template>
    <v-card max-width="1200" class="mx-auto">
        <v-card-title class="text-center">Usuarios</v-card-title>
        <div class="d-flex justify-end">
            <v-btn elevation="8" class="">Agregar nuevo Usuario</v-btn>
        </div>
        <div class="text-center">
            <v-table>
                <thead>
                    <tr>
                        <th class="text-center">
                            Nombre
                        </th>
                        <th class="text-center">
                            Razon Social
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="item in clientes" :key="item.cliente_id">
                        <td>{{ item.nombre }}</td>
                        <td>{{ item.razon_social }}</td>
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
        clientes: [],
        desserts: [
          {
            name: 'Frozen Yogurt',
            calories: 159,
          },
          {
            name: 'Ice cream sandwich',
            calories: 237,
          },
          {
            name: 'Eclair',
            calories: 262,
          },
          {
            name: 'Cupcake',
            calories: 305,
          },
          {
            name: 'Gingerbread',
            calories: 356,
          },
          {
            name: 'Jelly bean',
            calories: 375,
          },
          {
            name: 'Lollipop',
            calories: 392,
          },
          {
            name: 'Honeycomb',
            calories: 408,
          },
          {
            name: 'Donut',
            calories: 452,
          },
          {
            name: 'KitKat',
            calories: 518,
          },
        ],
      }
    },
    created() {
        this.getClientes()
    },
    methods: {
        async getClientes() {
            try {
                let response = await axios.get('/api/clientes')
                console.log(response)
                if (response.status === 200) {
                    if (response.data.status === 'ok') {
                        this.$store.commit('setClientes', response.data.clientes)
                        this.clientes = response.data.clientes
                    }
                } else {
                    errorSweetAlert('Ocurrió un error al obtener los clientes')
                }
            } catch (error) {
                errorSweetAlert('Ocurrió un error al obtener los clientes')
            }
        }
    }
  }
</script>