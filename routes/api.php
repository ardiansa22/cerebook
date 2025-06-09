<?php

use Illuminate\Support\Facades\Route;

//import controller ProductController
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\MidtransController;

//products
// Route::apiResource('/book', BookController::class);

Route::apiResource('/midtrans-callback', [MidtransController::class, 'callback']);
