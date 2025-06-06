<?php

use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Admin\Categories;
use App\Livewire\Admin\Books;
use App\Livewire\Admin\Genres;
use App\Livewire\Admin\Dashboard; // Tambahkan use statement untuk Dashboard
use App\Livewire\Admin\Loan;
use App\Livewire\Admin\Mybook;
use App\Livewire\Admin\Product;
use App\Livewire\Admin\ProductCategory;
use App\Livewire\Admin\Returns;
use App\Livewire\Customer\CartComponent;
use App\Livewire\Customer\ExploreBook;
use App\Livewire\Customer\Index;
use App\Livewire\Customer\MyBooks;
use App\Livewire\Customer\ShowProduct;
use App\Models\Cart;
use Illuminate\Support\Facades\Route;

Route::get('/',Index::class)->name('home');



Route::middleware(['auth', 'role:customer', 'verified'])->group(function () {
    Route::get('/book/{book}', ShowProduct::class)->name('book.show');
    Route::get('/my-books', MyBooks::class)->name('my-books');
    Route::get('/Explore/{categori:name}', ExploreBook::class)->name('categori.book');
    Route::get('/keranjang', CartComponent::class)->name('keranjang');
});
Route::middleware(['auth', 'role:admin', 'verified'])->group(function () {
    Route::get('dashboard', Dashboard::class)->name('dashboard');
    Route::redirect('settings', 'settings/profile');
    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('categories', Categories::class)->name('admin.categories');
    Route::get('book', Books::class)->name('admin.book');
    Route::get('genre', Genres::class)->name('admin.genre');
    Route::get('products', Product::class)->name('admin.product');
    Route::get('product-category', ProductCategory::class)->name('admin.productcategory');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
    Route::get('mybook', Mybook::class)->name('admin.mybook');
    Route::get('loan', Loan::class)->name('admin.loan');
    Route::get('return', Returns::class)->name('admin.return');
});

require __DIR__.'/auth.php';