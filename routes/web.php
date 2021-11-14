<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ChapterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FormationController;
use App\Http\Controllers\SignupRequestController;
use App\Http\Controllers\TypeController;
use App\Http\Controllers\UsersController;
use Illuminate\Support\Facades\Route;

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


Route::get('/', [FormationController::class, 'index'])->name('formations-list');
Route::get('/formations/filter', [FormationController::class, 'filter'])->name('formations-filter');

Route::get('/formations/add-formation', [FormationController::class, 'add'])->name('formation-add')->middleware('auth');
Route::post('/formations/store-formation', [FormationController::class, 'store'])->name('formation-store')->middleware('auth');

Route::get('/formations/{id}', [FormationController::class, 'details'])->name('formation-details');
Route::put('/formations/{id}/update', [FormationController::class, 'update'])->name('formation-update')->middleware('auth');
Route::put('/formations/{id}/update-picture', [FormationController::class, 'updatePicture'])->name('formation-update-picture')->middleware('auth');
Route::delete('/formations/{id}', [FormationController::class, 'delete'])->name('formation-delete')->middleware('auth');

Route::get('/formations/{formation}/chapter/{chapter}', [ChapterController::class, 'index'])->name('formation-chapter');
Route::post('/upload-image', [ChapterController::class, 'uploadImage'])->name('chapter-upload-image');
Route::put('/formations/{id}/chapter/{chapter}/update-chapter', [ChapterController::class, 'update'])->name('chapter-update')->middleware('auth');
Route::delete('/formations/{id}/chapter/{chapter}/delete-chapter', [ChapterController::class, 'delete'])->name('chapter-delete')->middleware('auth');
Route::get('/formations/{id}/add-chapter', [ChapterController::class, 'add'])->name('chapter-add')->middleware('auth');
Route::post('/formations/{id}/store-chapter', [ChapterController::class, 'store'])->name('chapter-store')->middleware('auth');

Route::get('/types', [TypeController::class, 'index'])->name('type-list')->middleware('auth');
Route::post('/types/store-type', [TypeController::class, 'store'])->name('type-store')->middleware('auth');
Route::delete('/types/{id}/delete-type', [TypeController::class, 'delete'])->name('type-delete')->middleware('auth');

Route::get('/categories', [CategoryController::class, 'index'])->name('category-list')->middleware('auth');
Route::post('/categories/store-category', [CategoryController::class, 'store'])->name('category-store')->middleware('auth');
Route::delete('/categories/{id}/delete-category', [CategoryController::class, 'delete'])->name('category-delete')->middleware('auth');

Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');
Route::put('/dashboard/change-picture', [DashboardController::class, 'updatePicture'])->name('update-picture')->middleware('auth');
Route::put('/dashboard/change-password', [DashboardController::class, 'updatePassword'])->name('update-password')->middleware('auth');

Route::get('/signup-request', [SignupRequestController::class, 'index'])->name('signup-request-list')->middleware('auth');
Route::post('/signup-request/{id}/accept-registration', [SignupRequestController::class, 'store'])->name('accept-registration')->middleware('auth');
Route::delete('/signup-request/{id}/delete-registration', [SignupRequestController::class, 'delete'])->name('delete-registration')->middleware('auth');

Route::get('/user-list', [UsersController::class, 'index'])->name('user-list')->middleware('auth');
Route::get('/user-list/{id}/details', [UsersController::class, 'detailsUser'])->name('user-details')->middleware('auth');
Route::put('/user-list/{id}/update', [UsersController::class, 'updateUser'])->name('user-update')->middleware('auth');
Route::put('/user-list/{id}/update-email', [UsersController::class, 'updateEmail'])->name('user-update-email')->middleware('auth');
Route::put('/user-list/{id}/update-password', [UsersController::class, 'updatePassword'])->name('user-update-password')->middleware('auth');
Route::put('/user-list/{id}/delete-image', [UsersController::class, 'deleteUserImage'])->name('user-delete-image')->middleware('auth');
Route::delete('/user-list/{id}/delete', [UsersController::class, 'deleteUser'])->name('user-delete')->middleware('auth');

require __DIR__ . '/auth.php';
