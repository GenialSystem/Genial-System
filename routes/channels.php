<?php

use App\Models\Chat;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    // Log::info("User {$user->id} trying to join channel {$id}.");
    return (int) $user->id === (int) $id;
});

Broadcast::channel('users.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Broadcast::channel('online-users', function ($user) {
//     Log::info("User attempting to join the presence channel: {$user->id} - {$user->name}");
//     if ($user) {
//         Log::info("User found and authorized: {$user->id} - {$user->name}");
//     } else {
//         Log::warning("User not found.");
//     }
//     return ['id' => $user->id, 'name' => $user->name]; 
// });

Broadcast::channel('online-users', function (User $user) {
    return ['id' => $user->id, 'name' => $user->name];
});

Broadcast::channel('chat-{chatId}', function ($user, $chatId) {
    // Controlla se l'utente è parte della chat
    return Chat::find($chatId)?->users->contains($user->id);
});