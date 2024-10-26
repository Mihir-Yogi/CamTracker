<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Middleware\AuthAdmin;


Route::get('/', function () {
    if (Auth::check()) {
        // User is authenticated, redirect to the appropriate dashboard
        $user = Auth::user();
        return redirect($user->utype === 'ADM' ? '/admin' : '/user');
    }

    // User is not authenticated, redirect to the login page
    return redirect()->route('login');
});
// Existing Auth routes and other route definitions
Auth::routes();


Route::middleware(['auth', AuthAdmin::class])->group(function(){
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/admin-settings', [AdminController::class, 'settings'])->name('admin.settings');
    Route::post('/admin/update', [AdminController::class, 'updateSetting'])->name('admin.update');

    Route::get('/admin/users', [AdminController::class, 'users_details'])->name(name: 'admin.users');
    Route::get('/admin/users/{id}/edit', [AdminController::class, 'edit'])->name('admin.user.edit'); // Edit user form
    Route::put('/admin/users/{id}', [AdminController::class, 'update'])->name('admin.user.update');
    Route::delete('/admin/users/{id}/delete', [AdminController::class, 'users_delete'])->name('admin.user.delete');
});


Route::middleware(['auth'])->group(function(){
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::get('/user-settings', [UserController::class, 'settings'])->name('user.settings');
    Route::post('/user/update', [UserController::class, 'updateSetting'])->name('user.update');
});    
