<?php

use App\Http\Controllers\Api\LevelController;
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

Route::prefix('/levels')->controller(LevelController::class)->group(function () {
    Route::get('/', 'index');
    Route::post('/store', 'store');
    Route::post('/{id}/update', 'update');
    Route::post('/{id}/delete', 'destroy');
});
