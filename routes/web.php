<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DataProcessController;
use App\Http\Controllers\DataUserController;
use App\Http\Controllers\DataVisualisasiController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [DataVisualisasiController::class, 'Visualisasi_Dashboard'])->name('dashboard');

    Route::get('/kelas-perkuliahan', [DataVisualisasiController::class, 'Visualisasi_KelasPerkuliahan'])->name('kelas-perkuliahan');

    Route::get('/kelulusan-do', [DataVisualisasiController::class, 'Visualisasi_KelulusanDO'])->name('kelulusan-do');

    Route::get('/krs', [DataVisualisasiController::class, 'Visualisasi_KRS'])->name('krs');

    Route::get('/akm', [DataVisualisasiController::class, 'Visualisasi_AKM'])->name('akm');

    Route::get('/aktivitas-mahasiswa', [DataVisualisasiController::class, 'Visualisasi_AktivitasMahasiswa'])->name('aktivitas-mahasiswa');

    Route::get('/beban-dosen', [DataVisualisasiController::class, 'Visualisasi_BebanDosen'])->name('beban-dosen');

    Route::get('/data-sync', [DataVisualisasiController::class, 'Visualisasi_DataSync'])->name('data-sync');

    Route::get('/user', [DataVisualisasiController::class, 'Visualisasi_User'])->name('user');
    Route::post('/user/add-update', [DataUserController::class, 'addUpdate'])->name('user.add-update');
    Route::post('/user/delete', [DataUserController::class, 'delete'])->name('user.delete');

    Route::get('/data-krs', function () {
        $data = [
            'pages_active' => 'data-krs',
            'isActiveMenu' => true
        ];

        return view('pages/data-krs', $data);
    })->name('data-krs');

    Route::get('/generate-table', [DataProcessController::class, 'generateTable'])->name('process.table');
    Route::get('/synchronize-data', [DataProcessController::class, 'synchronizeData_test1_1'])->name('process.synchronizeTEST');
});

Route::get('/', function () {
    if (Auth::check()) {
        return redirect('/dashboard');
    } else {
        return redirect('/login');
    }
});

Route::get('/login', function () {
    if (Auth::check()) {
        return redirect('/dashboard');
    } else {
        return view('auth/login');
    }
})->name('login');

Route::post('/login', [AuthController::class, 'login'])->name('process.login');
Route::post('/logout', [AuthController::class, 'logout'])->name('process.logout');
Route::get('/logout', [AuthController::class, 'logout']);
