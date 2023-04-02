<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticuloController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\FacturaController;
use App\Http\Controllers\PedidoController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/clientes', [ClienteController::class, 'getClientes']);
Route::post('/registrar-cliente', [ClienteController::class, 'agregarCliente']);
Route::post('/editar-cliente', [ClienteController::class, 'editarCliente']);
Route::post('/eliminar-cliente', [ClienteController::class, 'eliminarCliente']);

Route::get('/articulos', [ArticuloController::class, 'getArticulos']);
Route::post('/registrar-articulo', [ArticuloController::class, 'agregarArticulo']);
Route::post('/editar-articulo', [ArticuloController::class, 'editarArticulo']);
Route::post('/eliminar-articulo', [ArticuloController::class, 'eliminarArticulo']);

Route::get('/pedidos', [PedidoController::class, 'getPedidos']);
Route::post('/agregar-pedido', [PedidoController::class, 'agregarPedido']);
Route::post('/editar-pedido', [PedidoController::class, 'editarPedido']);
Route::post('/eliminar-pedido', [PedidoController::class, 'eliminarPedido']);

Route::get('/facturas', [FacturaController::class, 'getFacturas']);
Route::post('/agregar-factura', [FacturaController::class, 'agregarFactura']);
Route::post('/subir-factura', [FacturaController::class, 'subirFactura']);
Route::post('/editar-factura', [FacturaController::class, 'editarFactura']);
Route::post('/eliminar-factura', [FacturaController::class, 'eliminarFactura']);

