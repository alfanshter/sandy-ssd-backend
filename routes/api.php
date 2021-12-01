<?php

use App\Http\Controllers\BarangController;
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
Route::get('/barang',[BarangController::class, 'index']);
Route::get('/barang/{id}',[BarangController::class, 'show']);
//=======ENDAPI BARANG