<?php

namespace App\Http\Controllers;

use App\Models\Articulo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class ArticuloController extends Controller
{
    public function getArticulos() {
        try {
            $articulos = Articulo::where('status',1)->get();
            $array = array();
            foreach ($articulos as $articulo) {
                $objectArticulo = new \stdClass();
                $objectArticulo->codigo_articulo = $articulo->codigo_articulo;
                $objectArticulo->nombre = $articulo->nombre;
                $objectArticulo->precio = $articulo->precio;
                array_push($array, $objectArticulo);
            }
            return response()->json([
                "status" => "ok",
                "message" => "Articulos obtenidos con exito",
                "articulos" => $array,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "ok",
                "message" => "Ocurrio un error al consultar los articulos",
                "error" => $th->getMessage(),
                "line" => $th->getLine(),
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     * Funcion que recibe como parametros el codigo_articulo, nombre y precio
     * para guardar un articulo.
     * @param  \Illuminate\Http\Request $request - Información desde el frontend con datos del articulo.
     * @return \Illuminate\Http\Response
     */
    public function agregarArticulo(Request $request) {
        try {
            $existe_articulo = Articulo::where('codigo_articulo', $request->codigo_articulo)->exists();
            if ($existe_articulo) {
                return response()->json([
                    "status" => "exists",
                    "message" => "Este articulo ya existe"
                ], 200);
            }
            $articulo = new Articulo;
            $articulo->codigo_articulo = $request->codigo_articulo;
            $articulo->nombre = $request->nombre;
            $articulo->precio = $request->precio;
            $articulo->save();
            DB::commit();
            return response()->json([
                "status" => "ok",
                "message" => "articulo creado con exito",
                "articulo" => $articulo,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "ok",
                "message" => "Ocurrio un error al agregar el articulo",
                "error" => $th->getMessage(),
                "line" => $th->getLine(),
            ], 500);
        }
    }

    /**
     * Editar un articulo
     * Función para editar un articulo la función recibe como variables:
     * articulo_id, nombre, precio
     */
    public function editarArticulo(Request $request) {
        try {
            $articulo = Articulo::find($request->articulo_id);

            $articulo->codigo_articulo = $request->codigo_articulo;
            $articulo->nombre = $request->nombre;
            $articulo->precio = $request->precio;
            $articulo->save();
            DB::commit();
            return response()->json([
                "status" => "ok",
                "message" => "Articulo creado con exito",
                "articulo" => $articulo,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "ok",
                "message" => "Ocurrio un error al editar el articulo",
                "error" => $th->getMessage(),
                "line" => $th->getLine(),
            ], 500);
        }
    }

    /**
     * Eliminar un articulo
     * Se cambia el status del articulo
     * para que no se muestre más.
     */
    public function eliminarArticulo(Request $request) {
        try {
            $articulo = Articulo::find($request->articulo_id);

            $articulo->status = 0;
            
            $articulo->save();
            DB::commit();
            return response()->json([
                "status" => "ok",
                "message" => "Articulo eliminado con exito",
                "articulo" => $articulo,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "ok",
                "message" => "Ocurrio un error al editar el articulo",
                "error" => $th->getMessage(),
                "line" => $th->getLine(),
            ], 500);
        }
    }
}
