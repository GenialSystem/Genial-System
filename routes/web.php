<?php

use App\Events\TestEvent;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\CalendarController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\CustomerInfoController;
use App\Http\Controllers\EstimateController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\MechanicInfoController;
use App\Http\Controllers\OfficeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PdfController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WorkstationController;
use App\Models\CustomerInfo;
use App\Models\Estimate;
use App\Models\Event;
use App\Models\MechanicInfo;
use App\Models\Order;
use App\Models\User;
use App\Notifications\EstimateRequest;
use App\Notifications\EventNotification;
use App\Notifications\OrderCreate;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;
use Pusher\Pusher;


require __DIR__ . '/auth.php';
Route::middleware('auth')->group(function () {
    Route::get('edit_profile',[ProfileController::class, 'edit'])->name('editProfile');

    Route::get('/', function () {
        $workingOrdersCount = Order::where('state', 'In lavorazione')->count('id');
        $newOrdersCount = Order::where('state', 'Nuova')->count('id');
        $estimates = Estimate::count();
        $mechanics = MechanicInfo::count();
        $customers = CustomerInfo::count();
        return view('home', compact('workingOrdersCount', 'newOrdersCount', 'mechanics', 'customers', 'estimates'));
    })->name('home');

    Route::get('/mechanic-calendar/{mechanic}', [MechanicInfoController::class, 'calendar'])->name('mechanic-calendar');
    Route::resource('/calendar', CalendarController::class);
    Route::resource('/orders', OrderController::class);
    Route::get('downloadPDF/{model}/{ids}', [PdfController::class, 'downloadPdfs'])->name('downloadPDF');
    Route::get('/done_orders', [OrderController::class, 'done_orders'])->name('done_orders');
    Route::group(['middleware' => ['role:admin|customer']], function () {
        Route::resource('/estimates', EstimateController::class);
    });

    Route::group(['middleware' => ['role:admin']], function () {
        Route::resource('/customers', CustomerInfoController::class);
        Route::resource('/mechanics', MechanicInfoController::class);
        Route::resource('/office', OfficeController::class);
        Route::resource('/workstations', WorkstationController::class);
        Route::get('/invoices_customers', [InvoiceController::class, 'indexForCustomers'])->name('invoicesCustomer');
        Route::get('/invoices_mechanics', [InvoiceController::class, 'indexForMechanics'])->name('invoicesMechanic');
        Route::get('invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
    });
    Route::resource('/chats', ChatController::class);
    Route::get('/done_order/{order}', [OrderController::class, 'showDoneOrder'])->name('showDoneOrder');
    Route::post('/update-customer', [CalendarController::class, 'updateCustomer'])->name('update.customer');
    Route::post('/update-mechanic-day', [AvailabilityController::class, 'store'])->name('update.mechanicDay');

    // Dashboard
    Route::get('/dashboard', function () {
        return view('home');
    })->middleware(['verified'])->name('dashboard');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

