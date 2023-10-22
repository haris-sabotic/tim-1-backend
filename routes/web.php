<?php

use Illuminate\Support\Facades\Route;

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

Auth::routes();

Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('/admin/menu', [App\Http\Controllers\AdminMenuController::class, 'index'])->name('menu');
Route::post('/admin/menu/create', [App\Http\Controllers\AdminMenuController::class, 'create'])->name('menu.create');
Route::post('/admin/menu/edit', [App\Http\Controllers\AdminMenuController::class, 'edit'])->name('menu.edit');
Route::post('/admin/menu/delete', [App\Http\Controllers\AdminMenuController::class, 'delete'])->name('menu.delete');

Route::get('/admin/users', [App\Http\Controllers\AdminUserController::class, 'index'])->name('users');
Route::post('/admin/users/create', [App\Http\Controllers\AdminUserController::class, 'create'])->name('user.create');
Route::post('/admin/users/edit', [App\Http\Controllers\AdminUserController::class, 'edit'])->name('user.edit');
Route::post('/admin/users/delete', [App\Http\Controllers\AdminUserController::class, 'delete'])->name('user.delete');
