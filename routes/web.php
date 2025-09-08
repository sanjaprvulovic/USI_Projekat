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
        ? redirect()->route('degustacijas.index')   // ime rute po Blueprint konvenciji
        : redirect()->route('login');
})->name('root');

Auth::routes();

/*
 * JAVNO: lista i detalj degustacija
 * URL: /degustacije
 * Imena ruta: degustacijas.index, degustacijas.show
 */
Route::resource('degustacije', DegustacijaController::class)
    ->only(['index','show'])
    ->names('degustacijas')
    ->parameters(['degustacije' => 'degustacija']);

/*
 * MENADŽER/ADMIN: kreiranje/izmena degustacija + dodela paketa
 */
Route::middleware(['auth','can:managerOrAdmin'])->group(function () {
    Route::get('/degustacije/create',                   [DegustacijaController::class,'create'])->name('degustacijas.create');
    Route::post('/degustacije',                         [DegustacijaController::class,'store'])->name('degustacijas.store');
    Route::get('/degustacije/{degustacija}/edit',       [DegustacijaController::class,'edit'])->name('degustacijas.edit');
    Route::put('/degustacije/{degustacija}',            [DegustacijaController::class,'update'])->name('degustacijas.update');

    // dodela/snimanje paketa za konkretnu degustaciju
    Route::get('degustacijas/{degustacija}/paketi',     [DegustacijaController::class, 'paketi'])->name('degustacijas.paketi');
    Route::put('degustacijas/{degustacija}/paketi',     [DegustacijaController::class, 'paketiUpdate'])->name('degustacijas.paketi.update');
});

/*
 * ADMIN: brisanje degustacije
 */
Route::delete('/degustacije/{degustacija}', [DegustacijaController::class, 'destroy'])
    ->middleware(['auth','can:admin'])
    ->name('degustacijas.destroy');

/*
 * ADMIN: kompletan CRUD za PAKETE
 * URL: /paketi
 * Imena ruta: degustacioni-pakets.*
 */
Route::resource('paketi', DegustacioniPaketController::class)
    ->names('degustacioni-pakets')
    ->parameters(['paketi' => 'degustacioni_paket'])
    ->middleware(['auth','can:admin']);

/*
 * PRIJAVE (klijent i menadžer/admin)
 */
Route::middleware(['auth'])->group(function () {
    // moje prijave (lista)
    Route::get('/moje-prijave', [PrIjavaController::class,'index'])->name('prIjavas.index');

    // klijent šalje prijavu za konkretnu degustaciju (forma iz show.blade)
    // <form action="{{ route('prIjavas.store', $degustacija) }}" method="POST">
    Route::post('/degustacije/{degustacija}/prijave', [PrIjavaController::class,'store'])
        ->name('prIjavas.store');

    // otkaz prijave (menja status u Otkazana)
    Route::delete('/prijave/{prijava}', [PrIjavaController::class,'destroy'])
        ->name('prIjave.destroy');
});

// MENADŽER / ADMIN – pregled prijava za degustaciju + promene statusa
Route::middleware(['auth','can:managerOrAdmin'])->group(function () {
    Route::get('/degustacije/{degustacija}/prijave', [PrIjavaController::class,'forDegustacija'])
        ->name('prIjavas.forDegustacija');

    Route::put('/prijave/{prijava}/status', [PrIjavaController::class,'updateStatus'])
        ->name('prIjavas.updateStatus');

    Route::put('/prijave/{prijava}/approve', [PrIjavaController::class, 'approve'])
        ->name('prIjavas.approve');

    Route::put('/prijave/{prijava}/reject', [PrIjavaController::class, 'reject'])
        ->name('prIjavas.reject');
});

/*
 * Uloge (ako koristiš)
 */
Route::resource('roles', RoleController::class);



Route::resource('degustacijas', App\Http\Controllers\DegustacijaController::class);





Route::resource('degustacioni-pakets', App\Http\Controllers\DegustacioniPaketController::class);


Route::resource('prIjavas', App\Http\Controllers\PrIjavaController::class);
