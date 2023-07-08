<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MonedaController;
use App\Http\Controllers\TipoDocumentosController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ComprobanteController;
use App\Http\Controllers\pruebaController;
use Illuminate\Support\Facades\DB;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::get('listaClientes/{idempresa}/{fechainicial}/{fechafinal}/{tipoComprobante}/{Serie}/{numero}/{razonsocial}/{estado}', [ClienteController::class, 'getClientes']);
Route::get('listaClienteIndiv/{idempresa}/{total}/{numero}/{Serie}/{Tipo}/{FecEmiCom}/{rucdni}', [ClienteController::class, 'getClienteIndividual']);
Route::get('login/{ruc}/{rucdni}/{ruc_clave}', [LoginController::class, 'login']);
Route::get('downloadXML', [ClienteController::class, 'downloadXML']);
Route::get('getComprobante/{idempresa}/{idtipodocumento}/{serie}/{numero}/{idsucursal}', [ComprobanteController::class, 'getComprobante']);
Route::get('GetVentasDetalleId_Comprobante/{idempresa}/{idtipodocumento}/{serie}/{numero}', [ComprobanteController::class, 'GetVentasDetalleId_Comprobante']);
Route::get('getVentaPagos/{idempresa}/{idtipodocumento}/{serie}/{numero}', [ComprobanteController::class, 'getVentaPagos']);
Route::post('getPDFs', [ComprobanteController::class, 'getPDFs']);