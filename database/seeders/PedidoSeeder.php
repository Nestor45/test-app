<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PedidoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $current_date = Carbon::now();

        DB::table('pedidos')->insert([
            'codigo_pedido' => '153',
            'fecha_pedido' => $current_date->toDateString(),
            'cliente_id' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
        DB::table('pedidos')->insert([
            'codigo_pedido' => '358',
            'fecha_pedido' => $current_date->toDateString(),
            'cliente_id' => 2,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);
    }
}
