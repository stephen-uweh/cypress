<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\AuthenticationController;
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

Route::post('/register', [AuthenticationController::class, 'register']);

Route::post('/login', [AuthenticationController::class, 'login']);


Route::group(['prefix' => '/global/activities'], function () {
    Route::get('/all', [ActivityController::class, 'index']);

    Route::get('/{id}', [ActivityController::class, 'show']);

    Route::post('/add', [ActivityController::class, 'addGlobal'])->middleware('can:admin');

    Route::patch('/{id}/edit', [ActivityController::class, 'editGlobal'])->middleware('can:admin');

    Route::delete('/{id}/delete', [ActivityController::class, 'deleteGlobal'])->middleware('can:admin');
});


Route::group(['prefix' => '/users/{user}'], function () {
    
    Route::get('/', [ActivityController::class, 'allUserActivities'])->middleware('can:admin');

    Route::get('/show/{id}', [ActivityController::class, 'showUserActivity'])->middleware('can:admin');

    Route::post('/add', [ActivityController::class, 'addActivityForUser'])->middleware('can:admin');

    Route::patch('/edit/{id}', [ActivityController::class, 'editActivityForUser'])->middleware('can:admin');

    Route::delete('/delete/{id}', [ActivityController::class, 'deleteActivityForUser'])->middleware('can:admin');
});


Route::get('/my/activities', [ActivityController::class, 'userIndex'])->middleware('auth');

Route::get('/my/activities/{id}', [ActivityController::class, 'singleActivity'])->middleware('auth');

Route::get('/activities/range', [ActivityController::class, 'getUserActivitiesByRange'])->middleware('auth');


