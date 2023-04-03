<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function getClientes() {
        try {
            $clientes = Cliente::where('status',1)->get();
            $array = array();
            foreach ($clientes as $cliente) {
                $objectCliente = new \stdClass();
                $objectCliente->cliente_id = $cliente->id;
                $objectCliente->nombre = $cliente->nombre;
                $objectCliente->razon_social = $cliente->razon_social;
                array_push($array, $objectCliente);
            }
            return response()->json([
                "status" => "ok",
                "message" => "Clientes obtenidos con exito",
                "clientes" => $array,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "ok",
                "message" => "Ocurrio un error al consultar los clientes",
                "error" => $th->getMessage(),
                "line" => $th->getLine(),
            ], 500);
        }
        
    }

    /**
     * Show the form for creating a new resource.
     * Funcion que recibe como parametros el nombre y razon_social
     * para guardar un cliente.
     * @param  \Illuminate\Http\Request $request - Información desde el frontend con datos del cliente.
     * @return \Illuminate\Http\Response
     */
    public function agregarCliente(Request $request) {
        DB::beginTransaction();
        try {
            
            $existe_cliente = Cliente::where('nombre', $request->nombre)->exists();
            if ($existe_cliente) {
                return response()->json([
                    "status" => "exists",
                    "message" => "Este cliente ya existe"
                ], 201);
            }
            
            $cliente = new Cliente;
            $cliente->nombre = $request->nombre;
            $cliente->razon_social = $request->razon_social;
            $cliente->save();
            DB::commit();
            return response()->json([
                "status" => "ok",
                "message" => "Cliente creado con exito",
                "cliente" => $cliente,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "ok",
                "message" => "Ocurrio un error al agregar al cliente",
                "error" => $th->getMessage(),
                "line" => $th->getLine(),
            ], 500);
        }
    }

    /**
     * Editar Cliente
     * Funcion que debe recibir el id del cliente (cliente_id)
     * nombre y razon_social para poder editar 
     */
    public function editarCliente(Request $request) {
        DB::beginTransaction();
        try {
            $cliente = Cliente::find($request->cliente_id);

            $cliente->nombre = $request->nombre;
            $cliente->razon_social = $request->razon_social;
            
            $cliente->save();
            DB::commit();

            return response()->json([
                "status" => "ok",
                "message" => "Datos del cliente actulizados con exito",
                "cliente" => $cliente,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "ok",
                "message" => "Ocurrio un error al editar al cliente",
                "error" => $th->getMessage(),
                "line" => $th->getLine(),
            ], 500);
        }
    }

    /**
     * Eliminar Cliente
     * Funcionn que debe recibir el id del cliente (cliente_id) desde el frontend
     * El registro no se elimina solo se modifica el status
     * del cliente para que no se muestre. 
     */
    public function eliminarCliente(Request $request) {
        DB::beginTransaction();
        try {
            $cliente = Cliente::find($request->cliente_id);

            $cliente->status = 0;
            
            $cliente->save();
            DB::commit();

            return response()->json([
                "status" => "ok",
                "message" => "Cliene eliminado con exito",
                "cliente" => $cliente,
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                "status" => "ok",
                "message" => "Ocurrio un error al eliminar al cliente",
                "error" => $th->getMessage(),
                "line" => $th->getLine(),
            ], 500);
        }
    }
}
