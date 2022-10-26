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

Route::group(['prefix' => '/global/activities'], function(){
    Route::get('/all', [ActivityController::class, 'index']);

    Route::get('/{id}', [ActivityController::class, 'show']);

    Route::post('/add', [ActivityController::class, 'addGlobal']);

    Route::patch('/{id}/edit', [ActivityController::class, 'editGlobal']);

    Route::delete('/{id}/delete', [ActivityController::class, 'deleteGlobal']);
});

