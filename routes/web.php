<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Admin\Categories;
use App\Livewire\Admin\Books;
use App\Livewire\Admin\Genres;
use App\Livewire\Admin\Dashboard; // Tambahkan use statement untuk Dashboard
use App\Livewire\Admin\Mybook;
use App\Livewire\Customer\Index;
use App\Livewire\Customer\ShowProduct;
use Illuminate\Support\Facades\Route;

Route::get('/',Index::class)->name('home');
Route::get('/book/{book}', ShowProduct::class)->name('book.show');

Route::get('dashboard', Dashboard::class) // Ubah Route::view menjadi Route::get dan panggil class Dashboard
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');
    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('categories', Categories::class)->name('admin.categories');
    Route::get('book', Books::class)->name('admin.book');
    Route::get('genre', Genres::class)->name('admin.genre');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
    Route::get('mybook', Mybook::class)->name('admin.mybook');
});

require __DIR__.'/auth.php';