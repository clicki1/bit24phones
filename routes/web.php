<?php

use App\Http\Controllers\IndexController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/', [IndexController::class, 'index']);
Route::post('/', [IndexController::class, 'index']);
Route::post('/install', [IndexController::class, 'install']);
Route::post('/handler', [IndexController::class, 'handler'])->name('handler');
Route::post('/leads', [IndexController::class, 'leads'])->name('leads');
Route::post('/placement', [IndexController::class, 'placement']);
Route::post('/test', [IndexController::class, 'test']);

//PHONE
Route::post('/phonesinstall', [\App\Http\Controllers\Phones\IndexController::class, 'install'])->name('index.phones');
Route::post('/phones', [\App\Http\Controllers\Phones\IndexController::class, 'index'])->name('index.phones');
Route::get('/phones', [\App\Http\Controllers\Phones\IndexController::class, 'index'])->name('index.phones');
//ДОБАВЛЕНИЕ/ИЗМЕНЕНИЕ ПАРАМЕТРОВ
Route::post('/phones/store', [\App\Http\Controllers\Phones\IndexController::class, 'store'])->name('store.phones');
//ОБРАБОТКА СОБЫТИЙ
Route::post('/phones/handler', [\App\Http\Controllers\Phones\IndexController::class, 'handler'])->name('handler.phones');
//ОБНОВЛЕНИЕ НОМЕРОВ
Route::post('/phonesupdate', [\App\Http\Controllers\Phones\IndexController::class, 'phonesupdate'])->name('phonesupdate.phones');

//PHONE


//Route::get('/', function () {
//    return Inertia::render('Welcome', [
//        'canLogin' => Route::has('login'),
//        'canRegister' => Route::has('register'),
//        'laravelVersion' => Application::VERSION,
//        'phpVersion' => PHP_VERSION,
//    ]);
//});
//
//Route::get('/dashboard', function () {
//    return Inertia::render('Dashboard');
//})->middleware(['auth', 'verified'])->name('dashboard');
//
//Route::middleware('auth')->group(function () {
//    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
//});

