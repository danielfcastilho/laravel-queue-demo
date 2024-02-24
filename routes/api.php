<?php

use App\Http\Controllers\DemoTestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::post('/demo/test', [DemoTestController::class, 'process']);

Route::post('/demo/test/activate/{ref}', [DemoTestController::class, 'activate']);

Route::post('/demo/test/deactivate/{ref}', [DemoTestController::class, 'deactivate']);
