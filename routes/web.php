<?php

use App\Http\Controllers\CustomerInfoController;
use App\Http\Controllers\EstimateController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\MechanicInfoController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\WorkstationController;
use App\Models\CustomerInfo;
use App\Models\Estimate;
use App\Models\MechanicInfo;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Route;


require __DIR__ . '/auth.php';
Route::middleware('auth')->group(function () {
    
    Route::get('/', function () {
        $workingOrdersCount = Order::where('state', 'In lavorazione')->count('id');
        $newOrdersCount = Order::where('state', 'Nuova')->count('id');
        $estimates = Estimate::count('id');
        $mechanics = MechanicInfo::count(); 
        $customers = CustomerInfo::count();
        return view('home', compact('workingOrdersCount', 'newOrdersCount', 'mechanics', 'customers', 'estimates'));
    })->name('home');
    Route::resource('/customers', CustomerInfoController::class);
    Route::resource('/mechanics', MechanicInfoController::class);
    Route::resource('/orders', OrderController::class);
    Route::get('/done_orders', [OrderController::class, 'done_orders'])->name('done_orders');
    Route::resource('/estimates', EstimateController::class);
    Route::resource('/invoices', InvoiceController::class);
    Route::resource('/workstations', WorkstationController::class);
    Route::get('/invoices_customers', [InvoiceController::class, 'indexForCustomers'])->name('invoicesCustomer');
    Route::get('/invoices_mechanics', [InvoiceController::class, 'indexForMechanics'])->name('invoicesMechanic');
    Route::get('/done_order/{order}', [OrderController::class, 'showDoneOrder'])->name('showDoneOrder');

    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->middleware(['verified'])->name('dashboard');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
