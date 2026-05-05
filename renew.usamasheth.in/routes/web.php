<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Tenant\DashboardController;
use App\Http\Controllers\Tenant\ClientController;
use App\Http\Controllers\Tenant\ServiceController;
use App\Http\Controllers\Tenant\ClientSubscriptionController;
use App\Http\Controllers\Tenant\InvoiceController;
use App\Http\Controllers\Tenant\PaymentController;
use App\Http\Controllers\Tenant\ReportController;
use App\Http\Controllers\Tenant\SettingsController;
use App\Http\Controllers\SuperAdmin\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Landing Pages
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/features', [HomeController::class, 'features'])->name('features');
Route::get('/pricing', [HomeController::class, 'pricing'])->name('pricing');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

// Guest Authentication
Route::middleware('guest')->group(function () {
    Route::get('register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('register', [RegisteredUserController::class, 'store']);
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store']);
});

// Protected Authentication
Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    // Tenant / Agency Routes
    Route::prefix('app')->name('app.')->middleware('tenant')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        
        Route::resource('clients', ClientController::class);
        Route::resource('services', ServiceController::class);
        
        Route::post('subscriptions/{subscription}/suspend', [ClientSubscriptionController::class, 'suspend'])->name('subscriptions.suspend');
        Route::post('subscriptions/{subscription}/activate', [ClientSubscriptionController::class, 'activate'])->name('subscriptions.activate');
        Route::resource('subscriptions', ClientSubscriptionController::class);

        Route::resource('invoices', InvoiceController::class);
        
        Route::resource('payments', PaymentController::class)->only(['index', 'store']);

        Route::get('reports/revenue', [ReportController::class, 'revenue'])->name('reports.revenue');
        Route::get('reports/subscriptions', [ReportController::class, 'subscriptions'])->name('reports.subscriptions');

        Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::post('settings/company', [SettingsController::class, 'updateCompany'])->name('settings.company');
        Route::post('settings/smtp', [SettingsController::class, 'updateSmtp'])->name('settings.smtp');
    });

    Route::get('/admin', function() {
        return redirect()->route('admin.dashboard');
    });

    // Super Admin Routes
    Route::prefix('admin')->name('admin.')->middleware('admin')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
        Route::get('/tenants', [AdminController::class, 'tenants'])->name('tenants');
        Route::post('/tenants/{id}/suspend', [AdminController::class, 'suspendTenant'])->name('tenants.suspend');
        Route::post('/tenants/{id}/activate', [AdminController::class, 'activateTenant'])->name('tenants.activate');
    });
});

// Edit routes for services and clients
Route::get('/app/services/{service}/edit', [ServiceController::class, 'edit'])->name('app.services.edit');
Route::get('/app/clients/{client}/edit', [ClientController::class, 'edit'])->name('app.clients.edit');
