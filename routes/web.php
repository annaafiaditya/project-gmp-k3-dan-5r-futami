<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FindingController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\YearController;
use App\Models\Finding;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    $findings = Finding::with('closing')->latest()->get(); // Ambil semua data finding + relasi closing
    return view('landingpage', compact('findings'));
});

Route::get('/landingpage', [FindingController::class, 'landing'])->name('landing');
Route::get('/login', [UserController::class, 'showLogin'])->name('login');
Route::post('/loginAuth', [UserController::class, 'loginAuth'])->name('login.auth');
Route::get('/register', [UserController::class, 'showRegister'])->name('register');
Route::post('/register', [UserController::class, 'register'])->name('register.store');
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

Route::get('/forgot-password', [UserController::class, 'showForgotForm'])->name('password.request');
Route::post('/forgot-password', [UserController::class, 'sendOtp'])->name('password.sendOtp');
Route::get('/verify-otp', [UserController::class, 'showOtpForm'])->name('password.verifyForm');
Route::post('/verify-otp', [UserController::class, 'verifyOtp'])->name('password.verify');
Route::post('/reset-password', [UserController::class, 'resetPassword'])->name('password.reset');

Route::get('/error-permission', function () {
    return view('errors.permission');
})->name('error.permission');

Route::middleware('IsLogin')->group(function () {

    Route::get('/home', [FindingController::class, 'home'])->name('home');
    Route::get('/finding-closing', [FindingController::class, 'index'])->name('findings.index');
    Route::get('/export-findings', [ExportController::class, 'exportFindings'])->name('findings.export');
    Route::get('/findings/export-pdf', [ExportController::class, 'exportPDF'])->name('findings.exportPDF');
    Route::get('/profile', [UserController::class, 'index'])->name('user.index');
    Route::get('/profile/tambah', [UserController::class, 'create'])->name('user.create');
    Route::post('/profile/submit', [UserController::class, 'submit'])->name('user.submit');
    Route::get('/profile/edit/{id}', [UserController::class, 'edit'])->name('user.edit');
    Route::post('/profile/update/{id}', [UserController::class, 'update'])->name('user.update');
    Route::post('/profile/delete/{id}', [UserController::class, 'destroy'])->name('user.delete');

    // Notifications (available to all logged-in users)
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllRead'])->name('notifications.markAllRead');
    Route::get('/notifications/{id}/read', [NotificationController::class, 'markRead'])->name('notifications.markRead');
    Route::post('/notifications/{id}/unread', [NotificationController::class, 'markUnread'])->name('notifications.markUnread');
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy'])->name('notifications.destroy');

    Route::middleware('IsAdmin')->group(function () {

        Route::get('/finding/create', [FindingController::class, 'create'])->name('findings.create');
        Route::post('/finding/submit', [FindingController::class, 'submit'])->name('findings.submit');
        Route::get('/finding/edit/{id}', [FindingController::class, 'edit'])->name('findings.edit');
        Route::post('/finding/update/{id}', [FindingController::class, 'update'])->name('findings.update');
        Route::delete('/finding/delete/{id}', [FindingController::class, 'destroy'])->name('findings.delete');
        Route::patch('/findings/{id}/toggle-status', [FindingController::class, 'toggleStatus'])->name('findings.toggleStatus');
        Route::get('/verifikasi-akun', [UserController::class, 'showUnverified'])->name('admin.verifikasi');
        Route::post('/verifikasi-akun/{id}', [UserController::class, 'verifyUser'])->name('admin.verifikasi.user');

        // Admin Years Management
        Route::get('/admin/years', [YearController::class, 'index'])->name('admin.years.index');
        Route::get('/admin/years/create', [YearController::class, 'create'])->name('admin.years.create');
        Route::post('/admin/years', [YearController::class, 'store'])->name('admin.years.store');
        Route::delete('/admin/years/{year}', [YearController::class, 'destroy'])->name('admin.years.destroy');

        // New route for admin statistics export
        Route::get('/export-statistics', [ExportController::class, 'exportStatistics'])->name('findings.exportStatistics');

    });

    Route::middleware('IsUser')->group(function () {

        Route::get('/findings/upload-photo/{id}', [FindingController::class, 'uploadPhotoForm'])->name('findings.uploadPhotoForm');
        Route::post('/findings/upload-photo/{id}', [FindingController::class, 'uploadPhotoSubmit'])->name('findings.uploadPhotoSubmit');
        Route::get('/findings/{id}/edit-photo', [FindingController::class, 'editPhotoForm'])->name('findings.editPhotoForm');
        Route::post('/findings/{id}/edit-photo', [FindingController::class, 'updatePhoto'])->name('findings.updatePhoto');
        Route::delete('/finding/delete-photo-after/{id}', [FindingController::class, 'deletePhotoAfter'])->name('findings.deletePhotoAfter');

    });

});

