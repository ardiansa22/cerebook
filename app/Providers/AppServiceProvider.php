<?php

namespace App\Providers;

use App\Livewire\Admin\Loan;
use App\Models\Book;
use App\Models\Rental;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('components.layouts.app.sidebar', function ($view) {
        $totalBooks = Book::count();
        $totalLoans = Rental::whereIn('status', ['rented', 'late'])
            ->whereHas('payment', function ($q) {
                $q->where('status', 'paid');
            })
            ->count();

        $view->with([
            'totalBooks' => $totalBooks,
            'totalLoans' => $totalLoans,
        ]);
    });
    }
}
