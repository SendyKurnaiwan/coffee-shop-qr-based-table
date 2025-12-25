<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CrudController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RegisterController;

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

Route::get('/welcome', function () {
  return view('welcome');
});  

Route::get('/a', function () {
  return view('mainmenu');
});

Route::get('/', [CrudController::class, 'kopibigen'])->name('/');
Route::get('/menu', [CrudController::class, 'menu'])->name('/menu');
Route::get('/admin', [CrudController::class, 'index'])->name('/admin');
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate']);

Route::get('/register', [RegisterController::class, 'index']);
Route::post('/register', [RegisterController::class, 'store']);


Route::get('/tambah', [CrudController::class, 'tambah'])->name('tambah');
Route::post('/insert', [CrudController::class, 'insert'])->name('insert');

Route::get('/edit/id={id}', [CrudController::class, 'edit'])->name('edit');
Route::post('/update/id={id}', [CrudController::class, 'update'])->name('update');

Route::get('/hapus/id={id}', [CrudController::class, 'hapus'])->name('hapus');