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
            <v-window-item v-for="item in items.concat(more)"  :key="item" :value="'tab-' + item" >
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
        ],
        more: [
            'News', 'Maps', 'Books', 'Flights', 'Apps',
        ],
        text: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.',
        }),
        methods: {
            imprimir (item) {
                this.$router.push('/'+item)
                this.$nextTick(() => { this.vista = item })
            },
            addItem (item) {
                console.log("hola")
                const removed = this.items.splice(0, 1)
                console.log(removed)
                this.items.push(
                    
                ...this.more.splice(this.more.indexOf(item), 1),
                )
                this.more.push(...removed)
                this.$nextTick(() => { this.currentItem = 'tab-' + item })
                
            },
        },
    }
</script>