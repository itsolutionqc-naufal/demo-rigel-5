<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeviceTokenController;

Route::get('/favicon.ico', function () {
    $png = public_path('images/logo.png');
    if (is_file($png)) {
        return response()->file($png, ['Content-Type' => 'image/png']);
    }

    return response()->noContent();
})->name('favicon');

Route::get('/', function () {
    return redirect()->route('login');
})->name('home');

Route::post('/device-tokens', [DeviceTokenController::class, 'store'])
    ->middleware(['auth', 'verified'])
    ->name('device-tokens.store');

Route::get('dashboard', [App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified', 'nonadmin.to.app'])
    ->name('dashboard');

Route::get('merketing/dashboard', function () {
    return redirect()->route('marketing.dashboard', [], 301);
})->middleware(['auth', 'verified'])->name('merketing.dashboard');

Route::prefix('marketing')->middleware(['auth', 'verified', 'marketing.only'])->name('marketing.')->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\MarketingDashboardController::class, 'index'])
        ->name('dashboard');

    Route::resource('user', App\Http\Controllers\UserController::class)
        ->except(['destroy'])
        ->names('users');

    Route::prefix('sales')->name('sales.')->group(function () {
        Route::get('/', [App\Http\Controllers\SaleTransactionController::class, 'index'])->name('index');
        Route::get('/{saleTransaction}', [App\Http\Controllers\SaleTransactionController::class, 'show'])->name('show');
    });

    Route::get('/reports/sales', [App\Http\Controllers\SalesReportController::class, 'index'])
        ->name('reports.sales');

    Route::get('/reports/sales/export/{format}', [App\Http\Controllers\SalesReportController::class, 'export'])
        ->whereIn('format', ['xlsx', 'pdf'])
        ->name('reports.sales.export');

    Route::get('/report', function () {
        return redirect()->route('marketing.reports.sales');
    })->name('report');

    Route::prefix('wallet')->name('wallet.')->group(function () {
        Route::get('/', [App\Http\Controllers\WalletController::class, 'index'])->name('index');
        Route::post('/{id}/approve', [App\Http\Controllers\WalletController::class, 'approve'])->name('approve');
        Route::post('/{id}/reject', [App\Http\Controllers\WalletController::class, 'reject'])->name('reject');
    });

    Route::get('/withdraw', function (Request $request) {
        $request->merge(['type' => 'withdrawal']);

        return app(App\Http\Controllers\WalletController::class)->index($request);
    })->name('withdraw.index');

    Route::post('/withdraw/request', [App\Http\Controllers\WalletController::class, 'requestMarketingWithdrawal'])
        ->name('withdraw.request');

	    Route::prefix('transactions')->name('transactions.')->group(function () {
	        Route::get('/', [App\Http\Controllers\TransactionController::class, 'index'])->name('index');
	        Route::get('/{transaction}', [App\Http\Controllers\TransactionController::class, 'show'])->name('show');
	    });
	});

Route::view('articles', 'articles')
    ->middleware(['auth', 'verified', 'role:admin'])
    ->name('articles');

Route::get('articles/create', [App\Http\Controllers\ArticleController::class, 'create'])
    ->middleware(['auth', 'verified', 'role:admin'])
    ->name('articles.create');

Route::post('articles', [App\Http\Controllers\ArticleController::class, 'store'])
    ->middleware(['auth', 'verified', 'role:admin'])
    ->name('articles.store');

Route::get('articles/{article}/edit', [App\Http\Controllers\ArticleController::class, 'edit'])
    ->middleware(['auth', 'verified', 'role:admin'])
    ->name('articles.edit');

Route::put('articles/{article}', [App\Http\Controllers\ArticleController::class, 'update'])
    ->middleware(['auth', 'verified', 'role:admin'])
    ->name('articles.update');

Route::get('articles/{article}', [App\Http\Controllers\ArticleController::class, 'show'])
    ->middleware(['auth', 'verified', 'role:admin'])
    ->name('articles.show');

Route::delete('articles/{article}', [App\Http\Controllers\ArticleController::class, 'destroy'])
    ->middleware(['auth', 'verified', 'role:admin'])
    ->name('articles.destroy');

// Wallet routes
Route::prefix('wallet')->middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/', [App\Http\Controllers\WalletController::class, 'index'])->name('wallet.index');
    Route::post('/{id}/approve', [App\Http\Controllers\WalletController::class, 'approve'])->name('wallet.approve');
    Route::post('/{id}/reject', [App\Http\Controllers\WalletController::class, 'reject'])->name('wallet.reject');
});

// Transaction routes
Route::prefix('transactions')->middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/', [App\Http\Controllers\TransactionController::class, 'index'])->name('transactions.index');
    Route::get('/{transaction}', [App\Http\Controllers\TransactionController::class, 'show'])->name('transactions.show');
    Route::post('/', [App\Http\Controllers\TransactionController::class, 'store'])->name('transactions.store');
    Route::put('/{transaction}', [App\Http\Controllers\TransactionController::class, 'update'])->name('transactions.update');
    Route::put('/{transaction}/status', [App\Http\Controllers\TransactionController::class, 'updateStatus'])->name('transactions.updateStatus');
    Route::delete('/{transaction}', [App\Http\Controllers\TransactionController::class, 'destroy'])->name('transactions.destroy');
});

// Notification routes
Route::prefix('notifications')->middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/', [App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/{notification}/read', [App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.mark.read');
    Route::post('/mark-all-read', [App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark.all.read');
    Route::get('/unread-count', [App\Http\Controllers\NotificationController::class, 'getUnreadCount'])->name('notifications.unread.count');
});

// Help routes
Route::prefix('help')->middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/', [App\Http\Controllers\HelpController::class, 'index'])->name('help.index');
    Route::post('/', [App\Http\Controllers\HelpController::class, 'update'])->name('help.update');
});

Route::resource('users', App\Http\Controllers\UserController::class)->except(['destroy'])
    ->middleware(['auth', 'verified', 'role:admin'])
    ->names('users');

Route::delete('users/{user}', [App\Http\Controllers\UserController::class, 'destroy'])
    ->middleware(['auth', 'verified', 'admin.only'])
    ->name('users.destroy');

Route::resource('services', App\Http\Controllers\ServiceController::class)
    ->middleware(['auth', 'verified', 'role:admin'])
    ->names('services');

Route::post('/articles/upload', [App\Http\Controllers\ArticleController::class, 'upload'])
    ->middleware(['auth', 'verified', 'role:admin'])
    ->name('articles.upload');

Route::prefix('app')->middleware(['auth', 'verified', 'role:user'])->group(function () {
    Route::get('/{page?}', [App\Http\Controllers\MobileDashboardController::class, 'index'])->name('mobile.app');

    Route::post('/upload-proof', [App\Http\Controllers\MobileDashboardController::class, 'uploadProof'])->name('mobile.upload-proof');

    Route::post('/withdraw', [App\Http\Controllers\MobileDashboardController::class, 'withdraw'])->name('mobile.withdraw');

    Route::post('/submit-order', [App\Http\Controllers\MobileDashboardController::class, 'submitOrder'])->name('mobile.submit-order');

    Route::post('/submit-host', [App\Http\Controllers\MobileDashboardController::class, 'submitHost'])->name('mobile.submit-host');

    Route::get('/check-transaction/{transactionCode}', [App\Http\Controllers\MobileDashboardController::class, 'checkTransactionStatus'])->name('mobile.check-transaction');

    Route::get('/transaction-status/{transactionCode}', function ($transactionCode) {
        return view('mobile.pages.transaction-status', ['transactionCode' => $transactionCode]);
    })->name('mobile.transaction-status');

    Route::post('/upload-avatar', [App\Http\Controllers\MobileDashboardController::class, 'uploadAvatar'])->name('mobile.upload-avatar');

    Route::post('/update-profile', [App\Http\Controllers\MobileDashboardController::class, 'updateProfile'])->name('mobile.update-profile');
});

Route::post('/profile/upload-avatar', [App\Http\Controllers\MobileDashboardController::class, 'uploadAvatar'])
    ->middleware(['auth', 'verified', 'role:admin,marketing'])
    ->name('profile.upload-avatar');

// Admin dashboard routes
Route::prefix('admin')->middleware(['auth', 'verified', 'admin.only'])->group(function () {
    Route::get('/dashboard', [App\Http\Controllers\AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/commission-summary', [App\Http\Controllers\AdminDashboardController::class, 'commissionSummary'])->name('admin.commission.summary');
    Route::get('/host-submissions', [App\Http\Controllers\AdminHostSubmissionController::class, 'index'])->name('admin.host-submissions.index');

    Route::get('/marketing-settings', [App\Http\Controllers\AdminMarketingSettingsController::class, 'index'])
        ->name('admin.marketing-settings');
    Route::post('/marketing-settings', [App\Http\Controllers\AdminMarketingSettingsController::class, 'update'])
        ->name('admin.marketing-settings.update');

    // Admin avatar upload
    Route::post('/upload-avatar', [App\Http\Controllers\MobileDashboardController::class, 'uploadAdminAvatar'])->name('admin.upload-avatar');

    // Settings routes (inside admin prefix)
    Route::get('/settings', [App\Http\Controllers\SettingsController::class, 'index'])->name('admin.settings');

    // Payment method management from settings
    Route::post('/settings/payment-methods', [App\Http\Controllers\SettingsController::class, 'storePaymentMethod'])->name('admin.settings.payment-methods.store');
    Route::post('/settings/payment-methods/{paymentMethod}/toggle', [App\Http\Controllers\SettingsController::class, 'togglePaymentMethod'])->name('admin.settings.payment-methods.toggle');
    Route::delete('/settings/payment-methods/{paymentMethod}', [App\Http\Controllers\SettingsController::class, 'deletePaymentMethod'])->name('admin.settings.payment-methods.delete');

    // Service commission management
    Route::post('/settings/service-commissions', [App\Http\Controllers\SettingsController::class, 'updateServiceCommissions'])->name('admin.settings.service-commissions.update');

    // Telegram Bots Management
    Route::get('/telegram-bots', [App\Http\Controllers\Admin\TelegramBotController::class, 'index'])->name('admin.telegram-bots.index');
    Route::get('/telegram-bots/create', [App\Http\Controllers\Admin\TelegramBotController::class, 'create'])->name('admin.telegram-bots.create');
    Route::post('/telegram-bots', [App\Http\Controllers\Admin\TelegramBotController::class, 'store'])->name('admin.telegram-bots.store');
    Route::get('/telegram-bots/{telegramBot}/edit', [App\Http\Controllers\Admin\TelegramBotController::class, 'edit'])->name('admin.telegram-bots.edit');
    Route::put('/telegram-bots/{telegramBot}', [App\Http\Controllers\Admin\TelegramBotController::class, 'update'])->name('admin.telegram-bots.update');
    Route::delete('/telegram-bots/{telegramBot}', [App\Http\Controllers\Admin\TelegramBotController::class, 'destroy'])->name('admin.telegram-bots.destroy');
    Route::post('/telegram-bots/{telegramBot}/toggle', [App\Http\Controllers\Admin\TelegramBotController::class, 'toggle'])->name('admin.telegram-bots.toggle');
    Route::get('/telegram-bots/{telegramBot}/test', [App\Http\Controllers\Admin\TelegramBotController::class, 'test'])->name('admin.telegram-bots.test');
});

// Payment methods routes
Route::prefix('payment-methods')->middleware(['auth', 'verified', 'admin.only'])->group(function () {
    Route::get('/', [App\Http\Controllers\PaymentMethodController::class, 'index'])->name('payment-methods.index');
    Route::get('/create', [App\Http\Controllers\PaymentMethodController::class, 'create'])->name('payment-methods.create');
    Route::post('/', [App\Http\Controllers\PaymentMethodController::class, 'store'])->name('payment-methods.store');
    Route::get('/{paymentMethod}', [App\Http\Controllers\PaymentMethodController::class, 'show'])->name('payment-methods.show');
    Route::get('/{paymentMethod}/edit', [App\Http\Controllers\PaymentMethodController::class, 'edit'])->name('payment-methods.edit');
    Route::put('/{paymentMethod}', [App\Http\Controllers\PaymentMethodController::class, 'update'])->name('payment-methods.update');
    Route::delete('/{paymentMethod}', [App\Http\Controllers\PaymentMethodController::class, 'destroy'])->name('payment-methods.destroy');
});

// Sales routes
Route::prefix('sales')->middleware(['auth', 'verified', 'role:admin'])->group(function () {
    Route::get('/', [App\Http\Controllers\SaleTransactionController::class, 'index'])->name('sales.index');
    Route::get('/create', [App\Http\Controllers\SaleTransactionController::class, 'create'])->name('sales.create');
    Route::post('/', [App\Http\Controllers\SaleTransactionController::class, 'store'])->name('sales.store');
    Route::get('/{saleTransaction}', [App\Http\Controllers\SaleTransactionController::class, 'show'])->name('sales.show');
    Route::get('/{saleTransaction}/edit', [App\Http\Controllers\SaleTransactionController::class, 'edit'])->name('sales.edit');
    Route::put('/{saleTransaction}', [App\Http\Controllers\SaleTransactionController::class, 'update'])->name('sales.update');
    Route::put('/{saleTransaction}/status', [App\Http\Controllers\SaleTransactionController::class, 'updateStatus'])
        ->name('sales.updateStatus');
    Route::post('/{saleTransaction}/approve', [App\Http\Controllers\SaleTransactionController::class, 'approve'])
        ->name('sales.approve');
    Route::post('/{saleTransaction}/reject', [App\Http\Controllers\SaleTransactionController::class, 'reject'])
        ->name('sales.reject');
    Route::post('/{saleTransaction}/process', [App\Http\Controllers\SaleTransactionController::class, 'process'])
        ->name('sales.process');
});

// Sales Reports route
Route::get('/reports/sales', [App\Http\Controllers\SalesReportController::class, 'index'])
    ->middleware(['auth', 'verified', 'role:admin'])
    ->name('reports.sales');

Route::get('/reports/sales/export/{format}', [App\Http\Controllers\SalesReportController::class, 'export'])
    ->middleware(['auth', 'verified', 'role:admin'])
    ->whereIn('format', ['xlsx', 'pdf'])
    ->name('reports.sales.export');

// WhatsApp Webhook routes (for Wa Nugasin callbacks)
// Main webhook endpoint - receives incoming messages from Wa Nugasin
Route::post('/webhook/register', [App\Http\Controllers\WhatsAppCallbackController::class, 'handleWebhook'])
    ->name('whatsapp.webhook');

// Status update endpoint (optional - for delivery status)
Route::post('/whatsapp/status', [App\Http\Controllers\WhatsAppCallbackController::class, 'handleStatusUpdate'])
    ->name('whatsapp.status');

// Telegram Webhook route (for button clicks)
Route::get('/telegram/webhook', function () {
    return response()->json(['ok' => true]);
})->name('telegram.webhook.ping');

Route::post('/telegram/webhook', [App\Http\Controllers\TelegramWebhookController::class, 'handleWebhook'])
    ->withoutMiddleware([
        \Illuminate\Cookie\Middleware\EncryptCookies::class,
        \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
    ])
    ->name('telegram.webhook');

require __DIR__.'/settings.php';
