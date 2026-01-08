<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CrudController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrderController;

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

// Public routes
Route::get('/', [CrudController::class, 'kopibigen'])->name('/');


// Authentication routes
Route::get('/login', [LoginController::class, 'index'])->name('login');
Route::post('/login', [LoginController::class, 'authenticate']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// ========== ADMIN ROUTES (OWNER ONLY) ==========
Route::middleware(['auth', 'owner'])->group(function () {
  // Admin dashboard
  Route::get('/admin', [CrudController::class, 'index'])->name('admin');

  // CRUD operations for menu
  Route::get('/tambah', [CrudController::class, 'tambah'])->name('tambah');
  Route::post('/insert', [CrudController::class, 'insert'])->name('insert');
  Route::get('/edit/id={id}', [CrudController::class, 'edit'])->name('edit');
  Route::post('/update/id={id}', [CrudController::class, 'update'])->name('update');
  Route::get('/hapus/id={id}', [CrudController::class, 'hapus'])->name('hapus');

  // CRUD operations for tables (meja)
  Route::get('/meja', [UserController::class, 'index'])->name('meja');
  Route::get('/meja/tambah', [UserController::class, 'tambah'])->name('tambah.meja');
  Route::post('/meja/insert', [UserController::class, 'insert'])->name('insert.meja');
  Route::get('/meja/edit/id={id}', [UserController::class, 'edit'])->name('edit.meja');
  Route::post('/meja/update/id={id}', [UserController::class, 'update'])->name('update.meja');
  Route::get('/meja/hapus/id={id}', [UserController::class, 'hapus'])->name('hapus.meja');

  // Order management for admin
  Route::get('/admin/orders', function () {
    $orders = \App\Models\Order::with('menu', 'user')
      ->orderBy('created_at', 'desc')
      ->get()
      ->groupBy('order_number');
    return view('admin.orders', compact('orders'));
  })->name('admin.orders');

  Route::put('/admin/orders/{orderNumber}/status', [OrderController::class, 'updateStatus'])
    ->name('admin.orders.update');
});

// ========== AUTHENTICATED USER ROUTES ==========

Route::middleware(['auth', 'kasir'])->group(function () {
  // Order history
  Route::get('/order/{orderNumber}', [OrderController::class, 'show'])->name('order.show');
  Route::get('/table-orders', [OrderController::class, 'getTableOrders']);
  Route::get('/order-history', function () {
    $userIds = \App\Models\User::where('role', 'meja')->pluck('id');
    $orders = \App\Models\Order::with('menu')
      ->whereIn('user_id', $userIds)
      ->orderBy('created_at', 'asc')
      ->get();
    return view('orders.history', compact('orders'));
  })->name('order.history');
});
Route::middleware(['auth', 'meja'])->group(function () {
  // Menu page
  Route::get('/menu', [CrudController::class, 'menu'])->name('menu');
  Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
  Route::get('/order/{orderNumber}', [OrderController::class, 'show'])->name('order.show');
  Route::get('/table-orders', [OrderController::class, 'getTableOrders']);
});
