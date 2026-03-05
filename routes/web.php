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
use App\Models\MasterKomponen;
use Illuminate\Support\Facades\Route;
use SebastianBergmann\CodeCoverage\Report\Html\Dashboard;

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
    return view('welcome');
});
Route::resource('komponen', MasterController::class)->middleware('auth');   
Route::resource('departemen', DepartemenController::class)->middleware('auth');   
Route::resource('user', UserController::class)->middleware('auth');

Route::get('/laporan/transaksi', [LaporanController::class, 'index'])->name('laporan.transaksi');

Route::prefix('mutasi')->name('mutasi.')->group(function () {
    Route::get('/',        [MutasiController::class, 'index'])  ->name('index');
    Route::get('/create',  [MutasiController::class, 'create']) ->name('create');
    Route::post('/',       [MutasiController::class, 'store'])  ->name('store');
    Route::get('/rekap',   [MutasiController::class, 'rekap'])  ->name('rekap');
    Route::get('/{id}',    [MutasiController::class, 'show'])   ->name('show');
})->middleware('auth');
Route::get('/login', [AuthController::class, 'index'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/dashboard', action: [DashboardController::class,'index'])->name('dashboard');

Route::prefix('backup')->name('backup.')->group(function () {
    Route::get('/',        [BackupController::class, 'index'])  ->name('index');
    Route::get('/download',  [BackupController::class, 'backup']) ->name('download');
    Route::post('/restore',       [BackupController::class, 'restore'])  ->name('restore');
    Route::post('/excel',   [BackupController::class, 'exportExcel'])  ->name('excel');
})->middleware('auth');

Route::get('/restore-awal', [BackupController::class, 'restoreAwalForm'])->name('restore.awal.form');
Route::post('/restore-awal', [BackupController::class, 'restoreAwal'])->name('restore.awal');
Route::get('/komponen/image/{filename}', function ($filename) {
    $path = Storage::disk('app_data_images')->path($filename);
    
    if (!file_exists($path)) abort(404);
    
    return response()->file($path);
})->name('komponen.image');
Route::get('/backup/export-images', [BackupController::class, 'exportImages'])->name('backup.export-images');
Route::post('/backup/import-images', [BackupController::class, 'importImages'])->name('backup.import-images');
Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
Route::post('/setting', [SettingController::class, 'update'])->name('setting.update');