<?php

use Illuminate\Support\Facades\Route;

//import controller ProductController
use App\Http\Controllers\Api\BookController;

//products
Route::apiResource('/book', BookController::class);