<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\UserController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('file');
// });
Route::get('/', [AdminController::class, 'index']);
Route::get('/file', [AdminController::class, 'index'])->name('file');
Route::get('/assigned-products', [AdminController::class, 'assignedProducts'])->name('assigned-products');
Route::post('/upload', [ExcelController::class, 'upload'])->name('upload');
Route::get('/users', [UserController::class, 'index'])->name('users');
Route::get('/products', [AdminController::class, 'products'])->name('products');

