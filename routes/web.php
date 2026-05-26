<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\DownloadController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProgramController;
use App\Http\Controllers\SpmbController;
use App\Http\Controllers\StaticPageController;
use App\Http\Controllers\StoryController;
use App\Http\Controllers\TeacherController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

// Blog
Route::get('/blog', [PostController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [PostController::class, 'show'])->name('blog.show');

// Tenaga Pendidik
Route::get('/guru', [TeacherController::class, 'index'])->name('teachers.index');
Route::get('/guru/{teacher}', [TeacherController::class, 'show'])->name('teachers.show');

// Halaman Statis
Route::get('/page/{slug}', [StaticPageController::class, 'show'])->name('page.show');

// PPDB / SPMB
Route::get('/ppdb', [SpmbController::class, 'index'])->name('ppdb.index');
Route::post('/ppdb', [SpmbController::class, 'store'])->name('ppdb.store');

// Unduhan
Route::get('/unduhan', [DownloadController::class, 'index'])->name('downloads.index');
Route::get('/unduhan/{download}/download', [DownloadController::class, 'download'])->name('downloads.download');

// ── Autentikasi Pengguna Umum ────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/masuk', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/masuk', [LoginController::class, 'login']);
    Route::get('/daftar', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/daftar', [RegisterController::class, 'register']);
});
Route::post('/keluar', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

// Produk Buku
Route::get('/buku', [BookController::class, 'index'])->name('books.index');
Route::get('/buku/{book:slug}', [BookController::class, 'show'])->name('books.show');

// Keranjang Belanja
Route::get('/keranjang', [CartController::class, 'index'])->name('cart.index');
Route::post('/keranjang/{book}', [CartController::class, 'add'])->name('cart.add');
Route::patch('/keranjang/{book}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/keranjang/{book}', [CartController::class, 'remove'])->name('cart.remove');
Route::delete('/keranjang', [CartController::class, 'clear'])->name('cart.clear');

// Checkout (harus login)
Route::middleware('auth')->group(function () {
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');
});

// Kegiatan / Event
Route::get('/kegiatan', [EventController::class, 'index'])->name('events.index');
Route::get('/kegiatan/{event:slug}', [EventController::class, 'show'])->name('events.show');

// Program
Route::get('/program', [ProgramController::class, 'index'])->name('programs.index');
Route::get('/program/{program:slug}', [ProgramController::class, 'show'])->name('programs.show');

// Cerita Santri
Route::get('/cerita-santri', [StoryController::class, 'index'])->name('stories.index');
Route::get('/cerita-santri/{story:slug}', [StoryController::class, 'show'])->name('stories.show');

// Donasi
Route::get('/donasi', [DonationController::class, 'index'])->name('donasi.index');
Route::post('/donasi', [DonationController::class, 'store'])->name('donasi.store');
