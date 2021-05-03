<?php

use Illuminate\Support\Facades\Route;



use App\Http\Controllers\{AdminController, RoleController, UserController};
use Illuminate\Http\Request;


Route::middleware(['auth'])
    ->name('Frontend.')
    ->group(function () {
        Route::get('/', function (Request $request) {
            if ($request->user()->can('is_admin')) {
                return redirect()->route('Admin.Index');
            }
            return "WELCOME";
        });
    });


Route::get('/admin', [AdminController::class, 'index'])
    ->middleware(['auth', 'admin'])
    ->name('Admin.Index');

//backend routes to fetch json data from database
Route::prefix('backend')
    ->name('backend.')
    ->middleware(['auth', 'admin'])
    ->group(function () {
        UserController::routes();
        RoleController::routes();

        //BACKEND_ROUTES_ADDED_BY_GENERATOR
    });

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
