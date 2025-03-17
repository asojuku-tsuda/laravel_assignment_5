<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MigrationVerifierController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::redirect('/', '/migration-task');

// マイグレーションタスク関連のルート
Route::get('/migration-task', [MigrationVerifierController::class, 'index'])->name('migration-task.index');
Route::get('/migration-task/{table}/verify', [MigrationVerifierController::class, 'verify'])->name('migration-task.verify');
