<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use App\Models\FacturaItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\URL;
use Carbon\Carbon;

class FacturaController extends Controller
{
    /***
     * Funcion que nos devuelve todas las facturas
     */
    public function getFacturas() {
        try {
            $facturas = Factura::where('status',1)->get();
            $array = array();
            foreach ($facturas as $factura) {
                $objectFactura = new \stdClass();
                $objectFactura->factura_id = $factura->id;
                $objectFactura->codigo_factura = $factura->codigo_factura;
                $objectFactura->fecha_factura = $factura->fecha_factura;
                $objectFactura->archivo = Storage::url($factura->archivo);
                $objectFactura->codigo_pedido = $factura->pedido->codigo_pedido;
                $objectFactura->fecha_pedido = $factura->pedido->fecha_pedido;
                $objectFactura->nombre_cliente = $factura->pedido->cliente->nombre;
                $objectFactura->cliente_razon_social = $factura->pedido->cliente->razon_social;
                array_push($array, $objectFactura);
            }
            return response()->json([
                "status" => "ok",
                "message" => "Facturas obtenidos con exito",
                "facturas" => $array,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "ok",
                "message" => "Ocurrio un error al consultar las facturas",
                "error" => $th->getMessage(),
                "line" => $th->getLine(),
            ], 500);
        }
    }

    /**
     * Funcion para subir la factura
     * se espera recibir el factura_id de la factura y 
     * el archivo.
     */
    public function subirFactura(Request $request) {
        try {
            DB::beginTransaction();
            if ($request->file('archivo')) {
                $factura = Factura::find($request->factura_id);
                $f_hora_entrega = Carbon::now();
                $current_day = Carbon::now();
                $fecha_f = $f_hora_entrega->year . '-' . $f_hora_entrega->month . '-' . $f_hora_entrega->day;
                $hora_f = $f_hora_entrega->hour . ':' . $f_hora_entrega->minute . ':' . $f_hora_entrega->second;
                $fecha_hora_concatenar_archivo = $current_day->day . '_' . $current_day->month . '_' . $current_day->year . '_' . $current_day->hour . '_' . $current_day->minute . '_' . $current_day->second . '_' . $current_day->micro;
                
                $file = $request->file('archivo');
               /*  $extension = $file[0]->getClientOriginalExtension(); */
                $extension = $file->getClientOriginalExtension();
                if ($extension == 'xml' || $extension == 'pdf') {
                    $fileNameToStore = 'archivo_' . $fecha_hora_concatenar_archivo . '.' . $extension;
                } else {
                    return response()->json([
                        "status" => "ok",
                        "message" => "El archivo no es permitido"
                    ], 500);
                }
                /* $path = $request->file('archivo')[0]->storeAs('public/facturas', $fileNameToStore); */
                $path = $request->file('archivo')->storeAs('public/facturas', $fileNameToStore);
                /* return response()->json([
                    "status" => "ok",
                    "message" => $path
                ], 500); */
                $factura->archivo = $path;
                $factura->save();
                DB::commit();
                return response()->json([
                    "status" => "ok",
                    "message" => "Factura guardada con exito",
                    "solicitudes" => $factura
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "ok",
                "message" => "Ocurrio un error al subir la factura",
                "error" => $th->getMessage(),
                "line" => $th->getLine(),
            ], 500);
        }
    }

    /**
     * Funcion para agregar una factura
     */
    public function agregarFactura(Request $request) {
        DB::beginTransaction();
        try {
            $existe_factura = Factura::where('codigo_factura',$request->codigo_factura)->exists();
            
            if ($existe_factura) {
                return response()->json([
                    "status" => "exists",
                    "message" => "Esta factura ya existe"
                ], 200);
            }
            $factura = new Factura;
            $factura->codigo_factura = $request->codigo_factura;
            $factura->fecha_factura = $request->fecha_factura;
            $factura->pedido_id = $request->pedido_id;
            $factura->save();
        
            $factura_item = new FacturaItem;
            $factura_item->factura_id = $factura->id;
            $factura_item->articulo_id = $request->articulo_id;
            $factura_item->cantidad = $request->cantidad;
            $factura_item->save();

            DB::commit();
            return response()->json([
                "status" => "ok",
                "message" => "Factura creado con exito",
                "factura" => $factura,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "ok",
                "message" => "Ocurrio un error al agregar la factura",
                "error" => $th->getMessage(),
                "line" => $th->getLine(),
            ], 500);
        }
    }

    /**
     * Funcion para editar la factura
     * La funciones tiene que recibir como parametros:
     * factura_id, codigo_facura, fecha_factura, pedido_id, articulo_id, cantidad
     */
    public function editarFactura(Request $request) {
        DB::beginTransaction();
        try {
            $factura = Factura::find($request->factura_id);
            $factura->codigo_factura = $request->codigo_factura;
            $factura->fecha_factura = $request->fecha_factura;
            $factura->pedido_id = $request->pedido_id;

            $factura->save();

            $factura_item = new FacturaItem;
            $factura_item->factura_id = $factura->id;
            $factura_item->articulo_id = $request->articulo_id;
            $factura_item->cantidad = $request->cantidad;
            
            $factura_item->save();

            DB::commit();

            return response()->json([
                "status" => "ok",
                "message" => "Datos del factura actulizados con exito",
                "factura" => $factura,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "ok",
                "message" => "Ocurrio un error al editar la factura",
                "error" => $th->getMessage(),
                "line" => $th->getLine(),
            ], 500);
        }
    }

    /**
     * Eliminar Factura 
     * Funcion que recibe como parametros:
     * factura_id desde el frontend
     * La cual cambie el estado de la facura para que no se pueda mostrar 
     * en la vista del front
     */
    public function eliminarFactura(Request $request) {
        DB::beginTransaction();
        try {
            $factura = Factura::find($request->factura_id);
            $factura->status = 0;
            $factura->save();

            DB::commit();

            return response()->json([
                "status" => "ok",
                "message" => "Factura eliminado con exito",
                "factura" => $factura->codigo_factura,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "ok",
                "message" => "Ocurrio un error al eliminar la factura",
                "error" => $th->getMessage(),
                "line" => $th->getLine(),
            ], 500);
        }
    }
}
