<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Session;

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

// To clear the cache of website
Route::get('/clear-cache', function () {
    $exitCode = Artisan::call('optimize');
    Session::flash('flash_message', 'Caches Cleared!');
    Session::flash('flash_type', 'alert-success');
    return redirect()->back();
})->name('clear-cache');

Route::get('/logout-session', function () {
    Auth::logout();
    return redirect('/login');
})->name('logout-session');

Route::group(['middleware' => ['web']], function () {

    // Route::middleware(['auth:sanctum', 'verified'])->get('/home', [HomeController::class, 'index'])->name('home');

    // For Login
    Route::get('/login', function () {
        return redirect('/home');
    })->middleware(['auth', '2fa']);

    Route::get('/', function () {
        return redirect('/login');
    });

    // Homepage Route
    Route::get('/home', [HomeController::class, 'index'])->name('home')->middleware(['auth', '2fa']);


    // TFA Setup Routes
    Route::post('/complete-registration', [RegisterController::class, 'completeRegistration']);

    Route::get('/get-user', [HomeController::class, 'getUser']);

    Route::post('/2fa', function () {
        return redirect('/home');
    })->name('2fa')->middleware('2fa');

    // To redirect the register page without middleware check
    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');

    // This route will redirect to the particular react router path.
    Route::get('/{path?}', [
        'uses' => 'App\Http\Controllers\HomeController@index',
        'as' => 'react',
        'where' => ['path' => '.*']
    ])->middleware(['auth', '2fa']);
});

Auth::routes();
