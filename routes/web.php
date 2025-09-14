<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\DegustacijaController;
use App\Http\Controllers\DegustacioniPaketController;
use App\Http\Controllers\PrIjavaController;
use App\Http\Controllers\RoleController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('degustacijas.index')   
        : redirect()->route('login');
})->name('root');

Auth::routes();

Route::resource('degustacije', DegustacijaController::class)
    ->only(['index','show'])
    ->names('degustacijas')
    ->parameters(['degustacije' => 'degustacija']);


Route::middleware(['auth','can:manager'])->group(function () {
    Route::get('/degustacije/create',                   [DegustacijaController::class,'create'])->name('degustacijas.create');
    Route::post('/degustacije',                         [DegustacijaController::class,'store'])->name('degustacijas.store');

    Route::get('degustacijas/{degustacija}/paketi',     [DegustacijaController::class, 'paketi'])->name('degustacijas.paketi');
    Route::put('degustacijas/{degustacija}/paketi',     [DegustacijaController::class, 'paketiUpdate'])->name('degustacijas.paketi.update');
});


Route::middleware(['auth','can:managerOrAdmin'])->group(function () {
    Route::get('/degustacije/{degustacija}/edit',       [DegustacijaController::class,'edit'])->name('degustacijas.edit');
    Route::put('/degustacije/{degustacija}',            [DegustacijaController::class,'update'])->name('degustacijas.update');

    Route::put('/degustacije/{degustacija}/finish',     [DegustacijaController::class,'finish'])->name('degustacijas.finish');
    Route::put('/degustacije/{degustacija}/cancel',     [DegustacijaController::class,'cancel'])->name('degustacijas.cancel');

    Route::delete('/degustacije/{degustacija}',         [DegustacijaController::class,'destroy'])->name('degustacijas.destroy');
});



Route::delete('/degustacije/{degustacija}', [DegustacijaController::class, 'destroy'])
    ->middleware(['auth','can:admin'])
    ->name('degustacijas.destroy');


Route::resource('paketi', DegustacioniPaketController::class)
    ->names('degustacioni-pakets')
    ->parameters(['paketi' => 'degustacioni_paket'])
    ->middleware(['auth','can:admin']);


Route::middleware(['auth'])->group(function () {
    Route::get('/moje-prijave', [PrIjavaController::class,'index'])->name('prIjavas.index');
    
    Route::post('/degustacije/{degustacija}/prijave', [PrIjavaController::class,'store'])
        ->name('prIjavas.store');

    Route::delete('/prijave/{prijava}', [PrIjavaController::class,'destroy'])
        ->name('prIjave.destroy');
    
    Route::put('/prijave/{prijava}', [PrIjavaController::class,'update'])
    ->name('prIjave.update');
});


Route::middleware(['auth','can:managerOrAdmin'])->group(function () {
    Route::get('/degustacije/{degustacija}/prijave',
        [PrIjavaController::class,'forDegustacija']
    )->name('prIjavas.forDegustacija');
});

Route::middleware(['auth','can:manager'])->group(function () {
    Route::put('/prijave/{prijava}/status',
        [PrIjavaController::class,'updateStatus']
    )->name('prIjavas.updateStatus');

    Route::put('/prijave/{prijava}/approve',
        [PrIjavaController::class,'approve']
    )->name('prIjavas.approve');

    Route::put('/prijave/{prijava}/reject',
        [PrIjavaController::class,'reject']
    )->name('prIjavas.reject');

    Route::put('/prijave/{prijava}/check-in',
        [PrIjavaController::class,'checkIn']
    )->name('prIjavas.checkIn');
});

Route::middleware(['auth','can:admin'])->group(function () {
    Route::get('/admin/users', [RoleController::class, 'manageUsers'])->name('admin.users.index');
    Route::put('/admin/users/{user}/role', [RoleController::class, 'updateUserRole'])->name('admin.users.updateRole');
    Route::resource('roles', RoleController::class);

});




Route::resource('degustacijas', App\Http\Controllers\DegustacijaController::class);





Route::resource('degustacioni-pakets', App\Http\Controllers\DegustacioniPaketController::class);


Route::resource('prIjavas', App\Http\Controllers\PrIjavaController::class);
