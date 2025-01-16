<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SupplychainController;

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

Route::get('/', function () {
    return redirect()->to('/admin');
});

Route::get("order-invoice/{id}",[SupplychainController::class,"order"])->name('order-invoice');

Route::get("packing-slip/{id}",[SupplychainController::class,"packing"])->name('packing-slip');

Route::get("report-generate/{fromdate}/{todate}",[SupplychainController::class,"report"])->name('report-generate');
