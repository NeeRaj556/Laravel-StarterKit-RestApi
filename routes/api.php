<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\SpecitilityController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;


// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::get('/up', function () {
    return response()->json(['message' => 'Server is up and running!'], 200);
});


Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);

Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::apiResource('/speciality', SpecitilityController::class);
    Route::apiResource('/doctor', DoctorController::class);
});
Route::middleware('role:doctor')->prefix('doctor')->name('doctor.')->group(function () {

    Route::get('doctor', function () {
        return response()->json(['message' => 'This is an example of a protected route for the doctor role'], 200);
    });
});
Route::middleware('role')->group(function () {
    Route::get('user', [AuthController::class, 'getUser']);
    Route::post('logout', [AuthController::class, 'logout']);
});
// Route::apiResource('products', ProductController::class);
Route::apiResource('/products', ProductController::class);
