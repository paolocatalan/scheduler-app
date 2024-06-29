<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\BookingController;

Route::get('/', function() {
    dd('welcome');
});

Route::get('/projects', [ProjectController::class, 'index']);
Route::get('/projects/create', [ProjectController::class, 'create'])->middleware('auth');
Route::get('projects/{project:slug}', [ProjectController::class, 'show']);
Route::post('/projects', [ProjectController::class, 'store'])
    ->middleware('auth')
    ->name('projects');
Route::get('projects/{project:slug}/edit', [ProjectController::class, 'edit'])->middleware('auth');
Route::patch('projects/{project:slug}', [ProjectController::class, 'update'])->middleware('auth');
Route::delete('projects/{project:slug}', [ProjectController::class, 'destroy'])->middleware('auth');

Route::get('/register', [RegisteredUserController::class, 'create'])->middleware('guest');
Route::post('/register/store', [RegisteredUserController::class, 'store'])->middleware('guest');

Route::get('/login', [SessionController::class, 'create'])->middleware('guest')->name('login');
Route::post('/login', [SessionController::class, 'store'])->middleware('guest');
Route::post('/logout', [SessionController::class, 'destroy'])->middleware('auth');

Route::get('/schedule-a-call', [BookingController::class, 'index']);
Route::get('/schedule-a-call/introduction', [BookingController::class, 'create']);
Route::post('/schedule-a-call/store', [BookingController::class, 'store']);
Route::get('/schedule-a-call/success', [BookingController::class, 'success'])->name('booking.success.route');

Route::get('/timeslots', function() {
    $date = (isset($_GET['date'])) ? $_GET['date'] : date('Y-m-d');
    $timezone = (isset($_GET['timezone'])) ? $_GET['timezone'] : 'Europe/Kyiv';
    return view('components.timeslots', [
        'date' => $date,
        'timezone' => $timezone
    ]);
});

Route::post('/api/schedule-a-call', function (Request $request) {
    // $timezone = $_POST['timezone'];
    $timezone = $request->timezone;



});

