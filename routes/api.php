<?php

use App\Events\TestEvent;
use App\Events\UserOnlineEvent;
use App\Http\Controllers\Api\CalendarController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\LoginController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\PdfController;
use App\Models\Event;
use App\Models\MechanicInfo;
use App\Models\User;
use App\Notifications\EventNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Pusher\Pusher;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('login', [LoginController::class, 'login']);
Route::get('get_orders_for_user/{id}/{year}', [OrderController::class, 'orders']);
Route::put('update_car_part', [OrderController::class, 'updateCarPart']);
Route::get('get_chats/{id}', [ChatController::class, 'getChats']);
Route::get('get_messages/{id}', [ChatController::class, 'getMessages']);
Route::post('send_message', [ChatController::class, 'sendMessage']);
Route::post('read_messages', [ChatController::class, 'readMessages']);
Route::get('get_orders_car_parts/{id}', [OrderController::class, 'orderCarParts']);
Route::get('mechanic_events/{id}', [CalendarController::class, 'events']);
Route::get('user_image/{id}', [UserController::class, 'userImage']);
Route::get('notifications/{id}', [UserController::class, 'getNotifications']);
Route::post('read_notification', [UserController::class, 'readNotification']);
Route::post('upload_user_image', [UserController::class, 'uploadProfileImage']);
Route::post('logout', [UserController::class, 'logout']);
Route::get('get_mechanics', [UserController::class, 'getMechanics']);
Route::put('update_profile', [UserController::class, 'updateProfile']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('get_user/{id}', [UserController::class, 'getUser']);
    
    Route::get('/get-notification-preferences/{id}', [UserController::class, 'getNotificationPreferences']);
    Route::put('/update-notification-preferences', [UserController::class, 'updateNotificationPreferences']);
});
Route::post('create_event', [CalendarController::class, 'createEvent']);
Route::post('update_availability', [CalendarController::class, 'updateAvailability']);
Route::post('update_availability_range', [CalendarController::class, 'updateAvailabilityRange']);
Route::get('get_orders_files/{id}', [OrderController::class, 'orderFiles']);
Route::post('upload_images/{id}/{type}', [OrderController::class, 'uploadImages']);
Route::post('upload_files/{id}', [OrderController::class, 'uploadFiles']);
// Route::get('download_images/{id}', [OrderController::class, 'downloadImages']);
Route::apiResource('orders', OrderController::class);
Route::get('testdownloadPDF/{model}/{ids}', [PdfController::class, 'downloadPdfs'])->name('downloadPDF');
// Route::middleware('auth:sanctum')->get('test', function (){return response()->json('ok');});
Route::get('get_orders_images/{id}/{disassembly}', [OrderController::class, 'orderImages']);
Route::middleware('auth:sanctum')->post('logout', [LoginController::class, 'logout']);

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
        // Genera la stringa di autenticazione
        $authData = $pusher->authorizePresenceChannel(
            $channelName,
            $socketId,
            (string) $user->id,
            ['id' => $user->id,'name' => $user->name] // Dati aggiuntivi utente
        );
        
        // Restituisci la risposta JSON
        return response()->json(json_decode($authData, true));
    } else {
        return response()->json(['message' => 'Forbidden'], 403);
    }
});
// Route::post('/broadcasting/auth', function (Request $request) {
//     $user = User::find($request->input('user_id'));

//     $socketId = $request->input('socket_id');
//     $channelName = $request->input('channel_name');

//     $pusher = new Pusher(
//         env('PUSHER_APP_KEY'),
//         env('PUSHER_APP_SECRET'),
//         env('PUSHER_APP_ID'),
//         ['cluster' => env('PUSHER_APP_CLUSTER')]
//     );

//     if ($user) {
//         // Genera la stringa di autenticazione
//         $authData = $pusher->authorizePresenceChannel(
//             $channelName,
//             $socketId,
//             (string) $user->id,
//             ['name' => $user->name] // Dati aggiuntivi utente
//         );

//         // Restituisci la risposta JSON
//         return response()->json(json_decode($authData, true));
//     } else {
//         return response()->json(['message' => 'Forbidden'], 403);
//     }
// });



Route::get('test', function(){
    // $user = User::find(2);
    // $user->notify(new OrderCreate($user));
    $users = User::find(2);
    UserOnlineEvent::broadcast($users);

    return $users;
});