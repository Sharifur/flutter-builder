<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\AppPageController;
use App\Http\Controllers\Api\WidgetController;
use App\Http\Controllers\Api\SchemaController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Public endpoints for demo purposes (remove auth requirement)
Route::get('projects/{project}/schema', [SchemaController::class, 'show']);
Route::get('projects/{project}/export', [SchemaController::class, 'export']);
Route::get('projects/{project}/preview', [SchemaController::class, 'preview']);

Route::middleware('auth:sanctum')->group(function () {
    // Projects API
    Route::apiResource('projects', ProjectController::class);

    // Project Pages API
    Route::get('projects/{project}/pages', [AppPageController::class, 'index']);
    Route::post('projects/{project}/pages', [AppPageController::class, 'store']);
    Route::get('pages/{page}', [AppPageController::class, 'show']);
    Route::put('pages/{page}', [AppPageController::class, 'update']);
    Route::delete('pages/{page}', [AppPageController::class, 'destroy']);

    // Widgets API
    Route::post('pages/{page}/widgets', [WidgetController::class, 'store']);
    Route::put('widgets/{widget}', [WidgetController::class, 'update']);
    Route::delete('widgets/{widget}', [WidgetController::class, 'destroy']);
    Route::post('pages/{page}/widgets/reorder', [WidgetController::class, 'reorder']);
});