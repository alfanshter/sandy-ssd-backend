<?php

use App\Http\Controllers\BarangController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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
//API AUTH
Route::post('/register', [UsersController::class, 'store']);
Route::get('/read', [UsersController::class, 'index']);
Route::post('/delete', [UsersController::class, 'destroy']);
Route::get('/auth/{uid?}', [UsersController::class, 'show']);
Route::post('/updateauth', [UsersController::class, 'update']);
//=======ENDAPI AUTH

//API BARANG
Route::post('/barang',[BarangController::class, 'create']);
Route::post('/barang/{id}',[BarangController::class, 'destroy']);
Route::get('/barang',[BarangController::class, 'index']);
Route::get('/barang/{id}',[BarangController::class, 'show']);
Route::post('/editbarang',[BarangController::class, 'edit']);
//=======ENDAPI BARANG

//API TRANSAKSI
Route::get('transaksi',[TransaksiController::class,'gettransaksi']);
Route::post('transaksi',[TransaksiController::class,'insertcart']);
Route::get('sum',[TransaksiController::class,'sumcart']);
Route::post('/hapustransaksi/{id}',[TransaksiController::class,'hapustransaksi']);
Route::post('updatetransaksi',[TransaksiController::class,'updatetransaksi']);
Route::post('/hapustransaksiselesai/{uid}',[TransaksiController::class,'hapustransaksiselesai']);
Route::post('/checkout',[TransaksiController::class,'checkout']);
Route::get('/getcheckout',[TransaksiController::class,'getcheckout']);
Route::get('/sumcheckout',[TransaksiController::class,'sumcheckout']);
//======ENDAPI TRANSAKSI