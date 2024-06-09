<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DataProcessController;
use App\Http\Controllers\DataVisualisasiController;
use Illuminate\Support\Facades\Auth;
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

// Route::get('/', function () {
//     return view('Login');
// });

Route::middleware(['auth'])->group(function () {

    Route::get('/user', function () {
        return view('user');
    });

    // Route::get('/dashboard', function () {
    //     $data = [
    //         'pages_active' => 'dashboard',
    //         'isActiveMenu' => false
    //     ];

    //     return view('dashboard', $data);
    // })->name('dashboard');

    Route::get('/dashboard', [DataVisualisasiController::class, 'DashboardVisualisasi'])->name('dashboard');

    Route::get('/data-krs', function () {
        $data = [
            'pages_active' => 'data-krs',
            'isActiveMenu' => true
        ];

        return view('pages/data-krs', $data);
    })->name('data-krs');

    Route::get('/data-sync', function () {
        $data = [
            'pages_active' => 'data-sync',
            'isActiveMenu' => false
        ];

        return view('pages/data-sync', $data);
    })->name('data-sync');

    // Test Generate auto table link
    Route::get('/generate-table', [DataProcessController::class, 'generateTable'])->name('process.table');
    Route::get('/synchronize-data', [DataProcessController::class, 'synchronizeData_test1_1'])->name('process.synchronizeTEST');
});

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware('auth');

// Route::get('/user', function () {
//     return view('user');
// })->middleware('auth');

Route::get('/', function () {
    // Jika pengguna sudah login, arahkan ke dashboard
    if (Auth::check()) {
        return redirect('/dashboard');
    } else {
        return redirect('/login');
    }
});

Route::get('/login', function () {
    // Jika pengguna sudah login, arahkan ke dashboard
    if (Auth::check()) {
        return redirect('/dashboard');
    } else {
        return view('auth/login');
    }
})->name('login');


// AUTH ROUTE
Route::post('/login', [AuthController::class, 'login'])->name('process.login');
Route::post('/logout', [AuthController::class, 'logout'])->name('process.logout');
Route::get('/logout', [AuthController::class, 'logout']);
