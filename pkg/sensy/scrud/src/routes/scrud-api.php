<?php

use App\Http\Controllers\Api\UserApiController;
use Sensy\Scrud\app\Http\Helpers\ApiHandler;
use Sensy\Scrud\app\Http\Helpers\AuthController;
use Illuminate\Support\Facades\Route;
use Sensy\Scrud\app\Http\Helpers\AuthHandler;

//Route::get('/api/{service}', [\Sensy\Scrud\app\Http\Helpers\ApiHandler::class, 'index']);


//    ->middleware('resolve.service')
;
## DEFAULT CRUD HANDLER
Route::group(['prefix' => 'api', 'middleware' => 'resolve.service'], function () {
    Route::post('/users/login', [UserApiController::class, 'login']);
    Route::post('/users/update-pwd', [UserApiController::class, 'updatePwd'])->middleware('auth:sanctum');

    Route::get('/{service}', [ApiHandler::class, 'index'])->middleware('auth:sanctum');
    Route::get('/{service}/create', [ApiHandler::class, 'create'])->middleware('auth:sanctum');
    Route::get('/{service}/{id}', [ApiHandler::class, 'show'])->middleware('auth:sanctum');
    Route::post('/{service}', [ApiHandler::class, 'store'])->middleware('auth:sanctum');
    Route::get('/{service}/{id}/edit', [ApiHandler::class, 'edit'])->middleware('auth:sanctum');
    Route::put('/{service}/{id}', [ApiHandler::class, 'update'])->middleware('auth:sanctum');
    Route::delete('/{service}/{id}', [ApiHandler::class, 'delete'])->middleware('auth:sanctum');
    Route::delete('/{service}/{id}/force', [ApiHandler::class, 'forceDelete'])->middleware('auth:sanctum');
});
