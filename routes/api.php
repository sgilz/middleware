<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//user defined
use App\Http\Controllers\AuthController;
use App\Http\Controllers\QueueController;
use App\Http\Controllers\ChannelController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|

/**
 * Routes for Auth
 */
Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
/**
 * Routes for Queue management
 */
Route::post('/queue/create', [QueueController::class, 'create'])->middleware('auth:sanctum');
Route::delete('/queue/delete', [QueueController::class, 'delete'])->middleware('auth:sanctum');
Route::get('/queue/list', [QueueController::class, 'list'])->middleware('auth:sanctum');
Route::get('/queue/pull', [QueueController::class, 'pull'])->middleware('auth:sanctum');
Route::put('/queue/push', [QueueController::class, 'push'])->middleware('auth:sanctum');
/**
 * Routes for Channel management
 */
Route::post('/channel/create', [ChannelController::class, 'create'])->middleware('auth:sanctum');
Route::delete('/channel/delete', [ChannelController::class, 'delete'])->middleware('auth:sanctum');
Route::get('/channel/list', [ChannelController::class, 'list'])->middleware('auth:sanctum');

Route::get('/user-info', [AuthController::class, 'userInfo'])->middleware('auth:sanctum');

