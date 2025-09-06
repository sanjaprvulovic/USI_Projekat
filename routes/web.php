<?php

use App\Http\Controllers\DegustacijaController;
use App\Http\Controllers\DegustacioniPaketController;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PrIjavaController;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return auth()->check()
        ? redirect()->route('degustacijas.index')   // Blueprint ime rute
        : redirect()->route('login');
})->name('root');

Auth::routes();

// Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::resource('degustacije', DegustacijaController::class)
    ->only(['index','show'])
    ->names('degustacijas')                            // degustacijas.index, .show
    ->parameters(['degustacije' => 'degustacija']);    // {degustacija}


// Route::prefix('/')
//     ->middleware('auth')
//     ->group(function () {});

Route::middleware(['auth','can:managerOrAdmin'])->group(function () {
    Route::get('/degustacije/create',  [DegustacijaController::class,'create'])->name('degustacijas.create');
    Route::post('/degustacije',         [DegustacijaController::class,'store'])->name('degustacijas.store');
    Route::get('/degustacije/{degustacija}/edit', [DegustacijaController::class,'edit'])->name('degustacijas.edit');
    Route::put('/degustacije/{degustacija}',      [DegustacijaController::class,'update'])->name('degustacijas.update');

    Route::get('degustacijas/{degustacija}/paketi',  [DegustacijaController::class, 'paketi'])->name('degustacijas.paketi');
    Route::put('degustacijas/{degustacija}/paketi',  [DegustacijaController::class, 'paketiUpdate'])->name('degustacijas.paketi.update');
});

Route::delete('/degustacije/{degustacija}', [DegustacijaController::class, 'destroy'])
    ->middleware(['auth','can:admin'])
    ->name('degustacijas.destroy');

Route::resource('paketi', DegustacioniPaketController::class)
    ->names('degustacioni-pakets')
    ->parameters(['paketi' => 'degustacioni_paket'])
    ->middleware(['auth','can:admin']);



// // ADMIN: kompletan CRUD za pakete
// Route::resource('degustacioni-pakets', DegustacioniPaketController::class)
//     ->middleware(['auth','can:admin']);  // imena ruta: degustacioni-pakets.*


Route::resource('prijave', PrIjavaController::class)
    ->only(['index','store','destroy']) // index: klijent/menadžer pogled; store: prijava; destroy: otkaz
    ->names('prIjavas')
    ->parameters(['prijave' => 'prijava'])
    ->middleware(['auth']);
Route::resource('roles', App\Http\Controllers\RoleController::class);


Route::resource('degustacijas', App\Http\Controllers\DegustacijaController::class);





Route::resource('degustacioni-pakets', App\Http\Controllers\DegustacioniPaketController::class);


Route::resource('prIjavas', App\Http\Controllers\PrIjavaController::class);
