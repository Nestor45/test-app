<?php

namespace App\Http\Controllers;

use App\Models\Pedido;
use App\Models\PedidoItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    public function getPedidos() {
        try {
            $pedidos = Pedido::where('status',1)->get();
            
            $array = array();
            foreach ($pedidos as $pedido) {
                $objectPedido = new \stdClass();
                $objectPedido->pedido_id = $pedido->id;
                $objectPedido->codigo_pedido = $pedido->codigo_pedido;
                $objectPedido->fecha_pedido = $pedido->fecha_pedido;
                $objectPedido->cliente_id = $pedido->cliente->id;
                $objectPedido->cliente_nombre = $pedido->cliente->nombre;
                $objectPedido->cliente_razon_social = $pedido->cliente->razon_social;

                $articulos = $pedido->articulos->all();
                
                foreach ($articulos as $articulo) {
                    $objectPedido->articulo_id = $articulo->id;
                    $objectPedido->codigo_articulo = $articulo->codigo_articulo;
                    $objectPedido->articulo_nombre = $articulo->nombre;
                    $objectPedido->articulo_precio = $articulo->precio;
                    $objectPedido->cantidad = $articulo->pivot->cantidad;
                  
                }
                array_push($array, $objectPedido);
            }
            return response()->json([
                "status" => "ok",
                "message" => "Pedidos obtenidos con exito",
                "pedidos" => $array,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "ok",
                "message" => "Ocurrio un error al consultar los pedidos",
                "error" => $th->getMessage(),
                "line" => $th->getLine(),
            ], 500);
        }
    }

    public function agregarPedido(Request $request) {
        DB::beginTransaction();
        try {
            $existe_pedido = Pedido::where('codigo_pedido', $request->codigo_pedido)->exists();
            if ($existe_pedido) {
                return response()->json([
                    "status" => "exists",
                    "message" => "Este perdido ya existe"
                ], 200);
            }
            $pedido = new Pedido;
            $pedido->codigo_pedido = $request->codigo_pedido;
            $pedido->fecha_pedido = $request->fecha_pedido;
            $pedido->cliente_id = $request->cliente_id;
            $pedido->save();

            $pedido_item = new PedidoItem;
            $pedido_item->pedido_id = $pedido->id;
            $pedido_item->articulo_id = $request->articulo_id;
            $pedido_item->cantidad = $request->cantidad;
            $pedido_item->save();

            DB::commit();
            return response()->json([
                "status" => "ok",
                "message" => "Pedido creado con exito",
                "factura" => $pedido,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "ok",
                "message" => "Ocurrio un error al agregar los pedidos",
                "error" => $th->getMessage(),
                "line" => $th->getLine(),
            ], 500);
        }
    }

    /**
     * Editar Pedido
     * Funcion que recibe como parametros
     * pedido_id, codigo_pedido, fecha_pedido, cliente_id, articulo_id y cantidad
     */
    public function editarPedido(Request $request) {
        DB::beginTransaction();
        try {
            $pedido = Pedido::find($request->pedido_id);

            $pedido->codigo_pedido = $request->codigo_pedido;
            $pedido->fecha_pedido = $request->fecha_pedido;
            $pedido->cliente_id = $request->cliente_id;

            $pedido->save();
            
            $pedido_item = new PedidoItem;
            $pedido_item->pedido_id = $pedido->id;
            $pedido_item->articulo_id = $request->articulo_id;
            $pedido_item->cantidad = $request->cantidad;
            
            $pedido_item->save();

            DB::commit();

            return response()->json([
                "status" => "ok",
                "message" => "Datos del pedido actulizados con exito",
                "pedido" => $pedido,
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                "status" => "ok",
                "message" => "Ocurrio un error al editar al pedido",
                "error" => $th->getMessage(),
                "line" => $th->getLine(),
            ], 500);
        }
    }

    /**
     * Eliminar Pedidos
     * Función que recibe como parametros: pedido_id
     */
    public function eliminarPedido(Request $request){
        DB::beginTransaction();
        try {
            $pedido = Pedido::find($request->pedido_id);
            $pedido->status = 0;
            $pedido->save();

            DB::commit();

            return response()->json([
                "status" => "ok",
                "message" => "Pedido eliminado con exito",
                "pedido" => $pedido,
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                "status" => "ok",
                "message" => "Ocurrio un error al eliminar al pedido",
                "error" => $th->getMessage(),
                "line" => $th->getLine(),
            ], 500);
        }
    }
}
