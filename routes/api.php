<?php

use App\Http\Controllers\Api\{
    CalendarController,
    ChatController,
    LoginController,
    OrderController,
    UserController
};
use App\Http\Controllers\PdfController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Pusher\Pusher;

// Auth & User Routes
Route::post('login', [LoginController::class, 'login']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn(Request $request) => $request->user());
    Route::post('logout', [LoginController::class, 'logout']);
    Route::get('get_user/{id}', [UserController::class, 'getUser']);
    Route::get('/get-notification-preferences/{id}', [UserController::class, 'getNotificationPreferences']);
    Route::put('/update-notification-preferences', [UserController::class, 'updateNotificationPreferences']);
});

// User Management
Route::get('user_image/{id}', [UserController::class, 'userImage']);
Route::post('upload_user_image', [UserController::class, 'uploadProfileImage']);
Route::get('notifications/{id}', [UserController::class, 'getNotifications']);
Route::post('read_notification', [UserController::class, 'readNotification']);
Route::get('get_mechanics', [UserController::class, 'getMechanics']);
Route::put('update_profile', [UserController::class, 'updateProfile']);

// Order Management
Route::apiResource('orders', OrderController::class)->names([
    'index' => 'apiOrders.index',
    'show' => 'apiOrders.show',
    'store' => 'apiOrders.store',
    'update' => 'apiOrders.update',
    'destroy' => 'apiOrders.destroy',
]);
Route::get('get_orders_for_user/{id}/{year}', [OrderController::class, 'orders']);
Route::get('get_orders_car_parts/{id}', [OrderController::class, 'orderCarParts']);
Route::get('get_orders_files/{id}', [OrderController::class, 'orderFiles']);
Route::get('get_orders_images/{id}/{disassembly}', [OrderController::class, 'orderImages']);
Route::put('update_car_part', [OrderController::class, 'updateCarPart']);

// File & Image Uploads
Route::post('upload_images/{id}/{type}', [OrderController::class, 'uploadImages']);
Route::post('upload_files/{id}', [OrderController::class, 'uploadFiles']);
Route::put('order/delete-image/{image}', [OrderController::class, 'deleteImage']);

// Calendar & Events
Route::get('mechanic_events/{id}', [CalendarController::class, 'events']);
Route::post('create_event', [CalendarController::class, 'createEvent']);
Route::post('update_availability', [CalendarController::class, 'updateAvailability']);
Route::post('update_availability_range', [CalendarController::class, 'updateAvailabilityRange']);

// Chat
Route::get('get_chats/{id}', [ChatController::class, 'getChats']);
Route::get('get_messages/{id}', [ChatController::class, 'getMessages']);
Route::post('send_message', [ChatController::class, 'sendMessage']);
Route::post('read_messages', [ChatController::class, 'readMessages']);

// PDF Downloads
Route::get('testdownloadPDF/{model}/{ids}', [PdfController::class, 'downloadPdfs'])->name('testdownloadPDF');

// Real-time Broadcasting
Route::post('/broadcasting/auth', function (Request $request) {
    $user = User::find($request->input('user_id'));
    $socketId = $request->input('socket_id');
    $channelName = $request->input('channel_name');

    $pusher = new Pusher(
        env('PUSHER_APP_KEY'),
        env('PUSHER_APP_SECRET'),
        env('PUSHER_APP_ID'),
        ['cluster' => env('PUSHER_APP_CLUSTER')]
    );

    if ($user) {
        $authData = $pusher->authorizePresenceChannel(
            $channelName,
            $socketId,
            (string) $user->id,
            ['id' => $user->id, 'name' => $user->name]
        );
        return response()->json(json_decode($authData, true));
    } else {
        return response()->json(['message' => 'Forbidden'], 403);
    }
});
