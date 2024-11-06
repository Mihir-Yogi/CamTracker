<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\FailureController;
use App\Http\Middleware\AuthAdmin;
use App\Http\Controllers\DepotController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\ComboController;
use App\Http\Controllers\NvrController;
use App\Http\Controllers\DvrController;
use App\Http\Controllers\HddController;
use App\Http\Controllers\CCTVController;
use App\Http\Controllers\StatusReportController;


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

    Route::get('/admin/users', [AdminController::class, 'users_details'])->name('admin.users');
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

    Route::post('/admin/locations/sublocations', [LocationController::class, 'sub_store'])->name('admin.locations.sub_store');

    // Combo routes under admin
    Route::get('/admin/combos', [ComboController::class, 'index'])->name('admin.combos.index');
    Route::get('/admin/combos/create', [ComboController::class, 'create'])->name('admin.combos.create');
    Route::post('/admin/combos', [ComboController::class, 'store'])->name('admin.combos.store');
    Route::get('admin/combos/{id}', [ComboController::class, 'show'])->name('admin.combos.show');
    Route::get('/combos/{id}/edit', [ComboController::class, 'edit'])->name('admin.combos.edit'); // Show edit form (GET method)
    Route::put('/combos/{id}', [ComboController::class, 'update'])->name('admin.combos.update'); // Update combo (PUT method)
    Route::delete('/combos/{id}', [ComboController::class, 'destroy'])->name('admin.combos.destroy'); // Delete combo
    
    // NVR routes under admin
    Route::get('/admin/nvrs', [NvrController::class, 'index'])->name('admin.nvrs.index');
    Route::post('/admin/nvrs/store', [NvrController::class, 'store'])->name('admin.nvrs.store');
    Route::get('admin/nvrs/{nvr}', [NvrController::class, 'show'])->name('admin.nvrs.show');
    Route::get('/admin/nvrs/{nvr}/edit', [NvrController::class, 'edit'])->name('admin.nvrs.edit');
    Route::put('/admin/nvrs/{nvr}', [NvrController::class, 'update'])->name('admin.nvrs.update');
    Route::get('/admin/nvrs/{nvr}/replace', [NvrController::class, 'showReplaceForm'])->name('admin.nvrs.replaceForm');
    Route::post('/admin/nvrs/{nvr}/replace', [NvrController::class, 'replace'])->name('admin.nvrs.replace');
    Route::delete('/admin/nvrs/{nvr}', [NvrController::class, 'destroy'])->name('admin.nvrs.destroy');
    Route::get('/admin/locations-by-depot/{depotId}', [NvrController::class, 'getLocationsByDepot']);

    // DVR routes under admin
    Route::get('/admin/dvrs', [DvrController::class, 'index'])->name('admin.dvrs.index');
    Route::post('/admin/dvrs/store', [DvrController::class, 'store'])->name('admin.dvrs.store');
    Route::get('admin/dvrs/{dvr}', [DvrController::class, 'show'])->name('admin.dvrs.show');
    Route::get('/admin/dvrs/{dvr}/edit', [DvrController::class, 'edit'])->name('admin.dvrs.edit');
    Route::put('/admin/dvrs/{dvr}', [DvrController::class, 'update'])->name('admin.dvrs.update');
    Route::get('/admin/dvrs/{dvr}/replace', [DvrController::class, 'showReplaceForm'])->name('admin.dvrs.replaceForm');
    Route::post('/admin/dvrs/{dvr}/replace', [DvrController::class, 'replace'])->name('admin.dvrs.replace');
    Route::delete('/admin/dvrs/{dvr}', [DvrController::class, 'destroy'])->name('admin.dvrs.destroy');
    Route::get('/admin/locations-by-depot/{depotId}', [DvrController::class, 'getLocationsByDepot']);

    // HDD routes under admin
    Route::get('/admin/hdds', [ HddController::class, 'index'])->name('admin.hdds.index');
    Route::post('/admin/hdds/store', [HddController::class, 'store'])->name('admin.hdds.store');
    Route::get('/admin/hdds/{hdd}', [HddController::class, 'show'])->name('admin.hdds.show');
    Route::get('/admin/hdds/{hdd}/edit', [HddController::class, 'edit'])->name('admin.hdds.edit');
    Route::put('/admin/hdds/{hdd}', [HddController::class, 'update'])->name('admin.hdds.update');
    Route::get('/admin/hdds/{hdd}/replace', [HddController::class, 'showReplaceForm'])->name('admin.hdds.replaceForm');
    Route::post('/admin/hdds/{hdd}/replace', [HddController::class, 'replace'])->name('admin.hdds.replace');
    Route::delete('/admin/hdds/{hdd}', [HddController::class, 'destroy'])->name('admin.hdds.destroy');
    Route::get('/admin/locations-by-depot/{depotId}', [HddController::class, 'getLocationsByDepot']);
    
    // CCTV Camera Routes under admin
    Route::get('/admin/cctvs', [CCTVController::class, 'index'])->name('admin.cctvs.index');                  
    Route::get('/admin/cctvs/create', [CCTVController::class, 'create'])->name('admin.cctvs.create');    
    Route::post('/admin/cctvs/store', [CCTVController::class, 'store'])->name('admin.cctvs.store');           
    Route::get('/admin/cctvs/{cctv}', [CCTVController::class, 'show'])->name('admin.cctvs.show');           
    Route::get('/admin/cctvs/{cctv}/edit', [CCTVController::class, 'edit'])->name('admin.cctvs.edit');       
    Route::put('/admin/cctvs/{cctv}', [CCTVController::class, 'update'])->name('admin.cctvs.update');         
    Route::get('/admin/cctvs/{cctv}/replace', [CCTVController::class, 'showReplaceForm'])->name('admin.cctvs.replaceForm'); 
    Route::post('/admin/cctvs/{cctv}/replace', [CCTVController::class, 'replace'])->name('admin.cctvs.replace');          
    Route::delete('/admin/cctvs/{cctv}', [CCTVController::class, 'destroy'])->name('admin.cctvs.destroy');    

    // REPORT ROUTES
    Route::get('/admin/reports', [StatusReportController::class, 'index'])->name('status_reports.index');
    Route::get('/admin/reports/create', [StatusReportController::class, 'create'])->name('status_reports.create');
    Route::get('/admin/reports/{id}', [StatusReportController::class, 'show'])->name('status_reports.show'); // Moved to before PUT
    Route::get('/admin/reports/{id}/edit', [StatusReportController::class, 'edit'])->name('status_reports.edit');
    
    Route::post('/admin/reports/store', [StatusReportController::class, 'store'])->name('status_reports.store');
    Route::put('/admin/reports/{id}', [StatusReportController::class, 'update'])->name('status_reports.update');
    Route::post('/devices-by-depot-location', [StatusReportController::class, 'getDevices'])->name('status_report.devices');

    Route::get('/admin/failed-devices', [FailureController::class, 'index'])->name('failed.devices');

});


Route::middleware(['auth'])->group(function(){
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::get('/user-settings', [UserController::class, 'settings'])->name('user.settings');
    Route::post('/user/update', [UserController::class, 'updateSetting'])->name('user.update');
});    
