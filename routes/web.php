<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\ProductController;
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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});
//GROUPING DENGAN MENGGUNAKAN MIDDLEWARE
Route::group(['middleware' => ['auth:sanctum', 'verified']], function() {
    //ROUTING UNTUK HALAMAN DASHBOARD
    Route::get('/dashboard', function() {
        return Inertia::render('Dashboard');
    })->name('dashboard');
  
//RESTFUL ROUTING UNTUK HALAMAN PRODUCT
Route::resource('product', ProductController::class);

});