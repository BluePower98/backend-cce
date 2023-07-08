<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MonedaController;
use App\Http\Controllers\TipoDocumentosController;
use App\Http\Controllers\ProveedorController;
use App\Http\Controllers\ClienteController;
USE App\Http\Controllers\LoginController;
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

Route::get('products', [ProductsController::class, 'index']);
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::get('monedas', [MonedaController::class, 'index']);
Route::get('tipodocumentos', [TipoDocumentosController::class, 'index']);
Route::get('listaProveedores', [ProveedorController::class, 'getProveedores']);
Route::get('listaClientes/{idempresa}/{fechainicial}/{fechafinal}/{tipoComprobante}/{Serie}/{numero}/{razonsocial}/{estado}', [ClienteController::class, 'getClientes']);
Route::get('usuarios', [ClienteController::class, 'index']);
Route::get('listaClienteIndiv/{idempresa}/{total}/{numero}/{Serie}/{Tipo}/{FecEmiCom}/{rucdni}', [ClienteController::class, 'getClienteIndividual']);
Route::get('login/{ruc}/{rucdni}/{ruc_clave}', [LoginController::class, 'login']);
Route::get('downloadXML', [ClienteController::class, 'downloadXML']);