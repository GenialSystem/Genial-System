<?php

use App\Models\Chat;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    Log::info("User {$user->id} trying to join channel {$id}.");
    return (int) $user->id === (int) $id;
});

Broadcast::channel('users.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('online-users', function ($user) {
        return $user;
});

Broadcast::channel('chat-{chatId}', function ($user, $chatId) {
    // Controlla se l'utente è parte della chat
    return Chat::find($chatId)?->users->contains($user->id);
});