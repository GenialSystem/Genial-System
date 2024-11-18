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
use App\Models\MechanicInfo;
use App\Models\Order;
use App\Models\User;
use App\Notifications\EstimateRequest;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Route;

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
Route::get('test', function () {
    return response()->json(['message' => 'Success']);
});

// Route::get('test', function () {
//     for ($i = 1; $i <= 50; $i++) {
//         // Alterna user_id tra 1 e 17
//         $user_id = $i % 2 === 0 ? 2 : 1;

//         // Genera un timestamp unico per ogni messaggio (aggiungi secondi per ogni ciclo)
//         $createdAt = Carbon::now()->addSeconds($i);
        
//         // Crea il messaggio usando Eloquent
//         Message::create([
//             'chat_id' => 1,
//             'user_id' => $user_id,
//             'content' => (string) $i,
//             'created_at' => $createdAt,
//             'updated_at' => $createdAt,
//         ]);
//     }

//     return '50 messaggi creati con successo con timestamp unici!';
// });