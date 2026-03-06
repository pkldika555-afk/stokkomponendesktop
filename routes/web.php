<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepartemenController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\MasterController;
use App\Http\Controllers\MutasiController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', [WelcomeController::class, 'index']);

Route::resource('komponen', MasterController::class)->middleware('auth');
Route::resource('departemen', DepartemenController::class)->middleware('auth');
Route::resource('user', UserController::class)->middleware('auth');

Route::get('/laporan/transaksi', [LaporanController::class, 'index'])->name('laporan.transaksi');

Route::prefix('mutasi')->name('mutasi.')->middleware('auth')->group(function () {
    Route::get('/',        [MutasiController::class, 'index'])  ->name('index');
    Route::get('/create',  [MutasiController::class, 'create']) ->name('create');
    Route::post('/',       [MutasiController::class, 'store'])  ->name('store');
    Route::get('/rekap',   [MutasiController::class, 'rekap'])  ->name('rekap');
    Route::get('/{id}',    [MutasiController::class, 'show'])   ->name('show');
});

Route::get('/login',  [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout',[AuthController::class, 'logout'])->name('logout');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

Route::prefix('backup')->name('backup.')->middleware('auth')->group(function () {
    Route::get('/',         [BackupController::class, 'index'])       ->name('index');
    Route::get('/download', [BackupController::class, 'backup'])      ->name('download');
    Route::post('/restore', [BackupController::class, 'restore'])     ->name('restore');
    Route::post('/excel',   [BackupController::class, 'exportExcel']) ->name('excel');
});

Route::get('/restore-awal',  [BackupController::class, 'restoreAwalForm'])->name('restore.awal.form');
Route::post('/restore-awal', [BackupController::class, 'restoreAwal'])    ->name('restore.awal');

Route::get('/backup/export-images', [BackupController::class, 'exportImages']) ->name('backup.export-images');
Route::post('/backup/import-images',[BackupController::class, 'importImages']) ->name('backup.import-images');

Route::get('/images/komponen/{filename}', [MasterController::class, 'showImage'])
    ->name('komponen.image');

Route::get('/setting',  [SettingController::class, 'index']) ->name('setting.index');
Route::post('/setting', [SettingController::class, 'update'])->name('setting.update');