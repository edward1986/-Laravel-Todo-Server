<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\TodoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;
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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::middleware('auth:api')->get('/logout', function (Request $request) {
    Log($request);
    $token = $request->user()->token();
    $token->revoke();
    $response = ['message' => 'You have been successfully logged out!'];
    return response($response, 200);
});
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('todos/all/delete', [TodoController::class, 'allDelete']);
Route::post('todos/all/update', [TodoController::class, 'allUpdate']);
Route::post('todos/all/store', [TodoController::class, 'allStore']);
//Route::middleware(['auth:api'])->group(function () {
Route::apiResources([
        '/todos' => TodoController::class
    ]
);
