<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BlogCategoryController;

Route::get('/', function () {
    return Inertia::render('Admin/Dashboard');
})->name('admin.dashboard');

Route::resource('users', UserController::class);

Route::resource('blog-categories', BlogCategoryController::class);
