<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivityController;
use App\Models\Activity;

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


Route::get('/all', [ActivityController::class, 'index']);

Route::get('/activities/{id}', [ActivityController::class, 'show']);

Route::post('/activities/add', [ActivityController::class, 'add']);

Route::patch('activities/{id}/edit', [ActivityController::class, 'edit']);

Route::get('/test/{date}', [ActivityController::class, 'test']);