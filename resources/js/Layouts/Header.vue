<template>
    <v-card>
        <v-toolbar color="deep-purple-accent-4">
            <v-app-bar-nav-icon></v-app-bar-nav-icon>
            <v-toolbar-title>
                <v-btn @click="this.$router.push('/')">Interview</v-btn>
                
            </v-toolbar-title>
            <v-spacer></v-spacer>
            <v-btn icon> <v-icon>mdi-magnify</v-icon> </v-btn>
            <v-btn icon> <v-icon>mdi-dots-vertical</v-icon> </v-btn>
            <template v-slot:extension>
                <v-tabs v-model="currentItem" fixed-tabs>
                    <v-tab v-for="item in items" :key="item" :value="'tab-' + item" @click="imprimir(item)">
                        {{ item }}
                    </v-tab>
                </v-tabs>
            </template>
        </v-toolbar>
        <v-window v-model="currentItem">
            <v-window-item v-for="item in items"  :key="item" :value="'tab-' + item" >
                <v-card flat>
                    <v-card-text>
                        <router-view></router-view>
                    </v-card-text>
                </v-card>
            </v-window-item>
        </v-window>
    </v-card>
</template>

<script>
    export default {
        data: () => ({
            currentItem: 'tab-Web',
            vista: '',
            items: [
                'Usuarios', 'Articulos', 'Pedidos', 'Facturas',
            ]
        }),
        methods: {
            imprimir (item) {
                this.$router.push('/'+item)
                this.$nextTick(() => { this.vista = item })
            }
        },
    }
</script>