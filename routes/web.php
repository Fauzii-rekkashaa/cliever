<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Pengajar\PengajarController;
use App\Http\Controllers\Pelajar\PelajarController;

// ── Landing ────────────────────────────────────────────────────
Route::get('/', [LandingController::class, 'index'])->name('home');

// ── Auth (Guest only) ──────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.store');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.store');
});

// ── Logout ─────────────────────────────────────────────────────
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

// ── Admin ──────────────────────────────────────────────────────
Route::prefix('admin')->name('admin.')->middleware(['auth', 'nocache'])->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

    Route::get('/pelajar',             [AdminController::class, 'pelajar'])->name('pelajar');
    Route::get('/pelajar/create',      [AdminController::class, 'createPelajar'])->name('pelajar.create');
    Route::post('/pelajar',            [AdminController::class, 'storePelajar'])->name('pelajar.store');
    Route::get('/pelajar/{user}/edit', [AdminController::class, 'editPelajar'])->name('pelajar.edit');
    Route::put('/pelajar/{user}',      [AdminController::class, 'updatePelajar'])->name('pelajar.update');
    Route::delete('/pelajar/{user}',   [AdminController::class, 'destroyPelajar'])->name('pelajar.destroy');

    Route::get('/pengajar',                     [AdminController::class, 'pengajar'])->name('pengajar');
    Route::get('/pengajar/create',              [AdminController::class, 'createPengajar'])->name('pengajar.create');
    Route::post('/pengajar',                    [AdminController::class, 'storePengajar'])->name('pengajar.store');
    Route::get('/pengajar/{user}/edit',         [AdminController::class, 'editPengajar'])->name('pengajar.edit');
    Route::put('/pengajar/{user}',              [AdminController::class, 'updatePengajar'])->name('pengajar.update');
    Route::delete('/pengajar/{user}',           [AdminController::class, 'destroyPengajar'])->name('pengajar.destroy');
    Route::patch('/pengajar/{user}/konfirmasi', [AdminController::class, 'konfirmasiPengajar'])->name('pengajar.konfirmasi');
    Route::patch('/pengajar/{user}/tolak',      [AdminController::class, 'tolakPengajar'])->name('pengajar.tolak');

    Route::get('/konfirmasi',                    [AdminController::class, 'konfirmasi'])->name('konfirmasi');
    Route::get('/konfirmasi/{course}',           [AdminController::class, 'showKonfirmasi'])->name('konfirmasi.show');
    Route::patch('/konfirmasi/{course}/setujui', [AdminController::class, 'setujuiCourse'])->name('konfirmasi.setujui');
    Route::patch('/konfirmasi/{course}/tolak',   [AdminController::class, 'tolakCourse'])->name('konfirmasi.tolak');
    Route::delete('/konfirmasi/materi/{materi}', [AdminController::class, 'destroyMateriAdmin'])->name('konfirmasi.materi.destroy');
});

// ── Pengajar ───────────────────────────────────────────────────
Route::prefix('pengajar')->name('pengajar.')->middleware(['auth', 'nocache'])->group(function () {
    Route::get('/dashboard',              [PengajarController::class, 'dashboard'])->name('dashboard');
    Route::get('/course/create',          [PengajarController::class, 'createCourse'])->name('course.create');
    Route::post('/course',                [PengajarController::class, 'storeCourse'])->name('course.store');
    Route::get('/course/{course}/edit',   [PengajarController::class, 'editCourse'])->name('course.edit');
    Route::put('/course/{course}',        [PengajarController::class, 'updateCourse'])->name('course.update');
    Route::delete('/course/{course}',     [PengajarController::class, 'destroyCourse'])->name('course.destroy');
    Route::get('/course/{course}/materi', [PengajarController::class, 'materi'])->name('materi');
    Route::post('/course/{course}/materi',[PengajarController::class, 'storeMateri'])->name('materi.store');
    Route::delete('/materi/{materi}',     [PengajarController::class, 'destroyMateri'])->name('materi.destroy');
});

// ── Pelajar ────────────────────────────────────────────────────
Route::prefix('pelajar')->name('pelajar.')->middleware(['auth', 'nocache'])->group(function () {
    Route::get('/dashboard',         [PelajarController::class, 'dashboard'])->name('dashboard');
    Route::get('/my-course',         [PelajarController::class, 'myCourse'])->name('mycourse');
    Route::get('/course/preview/{course}', [PelajarController::class, 'coursePreview'])->name('course.preview');
    Route::get('/course/{course}',   [PelajarController::class, 'courseDetail'])->name('course.detail');
    Route::get('/materi/{materi}',   [PelajarController::class, 'materiShow'])->name('materi.show');
    Route::post('/materi/{materi}/selesai', [PelajarController::class, 'selesaikanMateri'])->name('materi.selesai');
    Route::get('/browse',            [PelajarController::class, 'browse'])->name('browse');
    Route::post('/enroll/{course}',  [PelajarController::class, 'enroll'])->name('enroll');
    Route::get('/certificates',      [PelajarController::class, 'sertifikat'])->name('sertifikat');
    Route::get('/certificates/{enrollment}/download', [PelajarController::class, 'downloadSertifikat'])->name('sertifikat.download');
});
