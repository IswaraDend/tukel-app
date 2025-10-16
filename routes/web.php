<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\ProfileController;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AssignmentController;
use App\Http\Controllers\DistributionController;
use App\Http\Controllers\TeamController;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Redirect root ke login/dashboard
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return Auth::check()
        ? redirect()->route('dashboard')
        : redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| Dashboard (Inertia)
|--------------------------------------------------------------------------
*/
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

/*
|--------------------------------------------------------------------------
| Profile (Inertia)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| Google OAuth (Socialite)
|--------------------------------------------------------------------------
*/
Route::get('auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.redirect');
Route::get('auth/google/callback', [GoogleController::class, 'handleGoogleCallback'])->name('google.callback');

/*
|--------------------------------------------------------------------------
| Assignment Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/assignments/create', [AssignmentController::class, 'create'])->name('assignments.create');
    Route::post('/assignments', [AssignmentController::class, 'store'])->name('assignments.store');
    Route::get('/assignments/{assignment}', [AssignmentController::class, 'show'])->name('assignments.show');
    Route::get('/assignments/{assignment}/distribution', [AssignmentController::class, 'distributionPage'])
        ->name('assignments.distribution');
    Route::post('/assignments/{assignment}/distribution', [AssignmentController::class, 'saveDistribution'])
        ->name('assignments.distribution.save');

    Route::prefix('api')->group(function () {
        // ✅ Team endpoints
        Route::get('/teams', [TeamController::class, 'index']);
        Route::post('/teams', [TeamController::class, 'store']);

        // ✅ Assignment endpoints
        Route::get('/assignments', [AssignmentController::class, 'index']);
        Route::post('/assignments', [AssignmentController::class, 'store']);
        Route::post('/assignments/{assignment}/distribute', [AssignmentController::class, 'distribute']);
    });

    Route::put('/distributions/{distribution}', [DistributionController::class, 'update']);
});


Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');
/*
|--------------------------------------------------------------------------
| Auth scaffolding dari Breeze (jika digunakan)
|--------------------------------------------------------------------------
*/
require __DIR__ . '/auth.php';
