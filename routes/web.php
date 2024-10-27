<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Middleware\AuthAdmin;
use App\Http\Controllers\DepotController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\NvrController;
use App\Http\Controllers\ComboController;

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


    
      // Depot routes under admin
    Route::get('/admin/depots', [DepotController::class, 'index'])->name('admin.depots.index');
    Route::get('/admin/depots/create', [DepotController::class, 'create'])->name('admin.depots.create');
    Route::post('/admin/depots/store', [DepotController::class, 'store'])->name('admin.depots.store');
    Route::get('/admin/depots/{depot}/edit', [DepotController::class, 'edit'])->name('admin.depots.edit');
    Route::put('/admin/depots/{depot}', [DepotController::class, 'update'])->name('admin.depots.update');
    Route::delete('/admin/depots/{depot}', [DepotController::class, 'destroy'])->name('admin.depots.destroy');

    // Location routes under admin
    Route::get('/admin/locations', [LocationController::class, 'index'])->name('admin.locations.index');
    Route::get('/admin/locations/create', [LocationController::class, 'create'])->name('admin.locations.create');
    Route::post('/admin/locations/store', [LocationController::class, 'store'])->name('admin.locations.store');
    Route::get('/admin/locations/{location}/edit', [LocationController::class, 'edit'])->name('admin.locations.edit');
    Route::put('/admin/locations/{location}', [LocationController::class, 'update'])->name('admin.locations.update');
    Route::delete('/admin/locations/{location}', [LocationController::class, 'destroy'])->name('admin.locations.destroy');

    
    // NVR routes under admin
    Route::get('/admin/nvrs', [NvrController::class, 'index'])->name('admin.nvrs.index');
    Route::get('/admin/nvrs/create', [NvrController::class, 'create'])->name('admin.nvrs.create');
    Route::post('/admin/nvrs/store', [NvrController::class, 'store'])->name('admin.nvrs.store');
    Route::get('/admin/nvrs/{nvr}/edit', [NvrController::class, 'edit'])->name('admin.nvrs.edit');
    Route::put('/admin/nvrs/{nvr}', [NvrController::class, 'update'])->name('admin.nvrs.update');
    Route::post('/admin/nvrs/{nvr}/replace', [NvrController::class, 'replace'])->name('admin.nvrs.replace');
    Route::delete('/admin/nvrs/{nvr}', [NvrController::class, 'destroy'])->name('admin.nvrs.destroy');
    Route::get('/admin/locations-by-depot/{depotId}', [NvrController::class, 'getLocationsByDepot']);

    // Combo routes under admin
    Route::get('/admin/combos', [ComboController::class, 'index'])->name('admin.combos.index');
    Route::get('/admin/combos/create', [ComboController::class, 'create'])->name('admin.combos.create');
    Route::post('/admin/combos', [ComboController::class, 'store'])->name('admin.combos.store');
});


Route::middleware(['auth'])->group(function(){
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::get('/user-settings', [UserController::class, 'settings'])->name('user.settings');
    Route::post('/user/update', [UserController::class, 'updateSetting'])->name('user.update');
});    
