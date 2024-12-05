<?php

namespace App\Http\Controllers\Api;

use App\Events\MessageSent;
use App\Http\Controllers\Controller;
use App\Models\Chat;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function getChats($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Fetch all chats for the user
        $chats = $user->chats;

        // Add the unread messages count and messages with file_path to each chat
        $chats = $chats->map(function ($chat) use ($id) {

            $unreadMessagesCount = $this->getUnreadMessagesCount($chat->id, $id);
            $messagesWithFiles = $chat->messages()
                ->whereNotNull('file_path')
                ->get()
                ->map(function ($message) {
                    return [
                        'id' => $message->id,
                        'file_path' => $message->file_path,
                    ];
                });

            // Add the computed fields to the chat object
            $chat->unreadMessagesCount = $unreadMessagesCount;
            $chat->files = $messagesWithFiles;

            return $chat;
        });

        return response()->json($chats);
    }

    public function getUnreadMessagesCount($chatId, $userId)
    {
        // Get the chat and user information
        $chat = Chat::find($chatId);

        if (!$chat) {
            return 0; // If chat not found, return 0 unread messages
        }

        // Get the last read message ID from the pivot table for the current user
        $lastReadMessageId = $chat->users()->where('user_id', $userId)->first()->pivot->last_read_message_id;

        // Count unread messages by checking for messages with IDs greater than the last read message ID
        $unreadMessagesCount = $chat->messages()
                                    ->where('id', '>', $lastReadMessageId ?? 0)
                                    ->count();

        return $unreadMessagesCount;
    }

    public function getMessages($id, Request $request)
{
    $chat = Chat::find($id);

    // Ottieni i parametri offset e limit dalla richiesta
    $offset = $request->query('offset', 0); // Default 0
    $limit = $request->query('limit', 20);  // Default 20

    // Recupera i messaggi con eager loading, ordinati per created_at decrescente
    $messages = $chat->messages()
    ->with('user:id,image_path')
    ->orderBy('created_at', 'desc') // Ordine decrescente
    ->skip($offset)
    ->take($limit)
    ->get()
    ->reverse()->values();

    // Map dei messaggi per includere image_path dell'utente
    $messagesWithImages = $messages->map(function ($message) {
        return [
            'id' => $message->id,
            'chat_id' => $message->chat_id,
            'user_id' => $message->user_id,
            'content' => $message->content,
            'file_path' => $message->file_path,
            'created_at' => $message->created_at,
            'updated_at' => $message->updated_at,
            'image_path' => $message->user->image_path ?? null,
        ];
    });

    return response()->json($messagesWithImages);
}


    public function sendMessage(Request $request)
    {
        // Validazione dei dati
        $validated = $request->validate([
            'chat_id' => 'required|integer',
            'user_id' => 'required|integer',
            'content' => 'nullable|string',
            'file' => 'nullable|file|max:2048', // Limite di 2MB
        ]);
    
        $filePath = null; // Percorso del file
    
        // Logica per il caricamento del file
        if ($request->hasFile('file')) {
            $filePath = $request->file('file')->storeAs(
                "chats/{$request->chat_id}/files",
                $request->file('file')->getClientOriginalName(),
                'public'
            );
        }
        // Creazione del messaggio
        $message = Message::create([
            'chat_id' => $validated['chat_id'],
            'user_id' => $validated['user_id'],
            'content' => $validated['content'],
            'file_path' => $filePath, // Salva il percorso del file se presente
        ]);
    
        // Trasmissione tramite broadcasting
        MessageSent::broadcast($message)->toOthers();
        
        //leggi il messaggio appena letto
        $user = User::find($validated['user_id']);
        
        $user->chats()->updateExistingPivot($validated['chat_id'], [
            'last_read_message_id' => $message->id,
        ]);
        
        return response()->json('ok', 201);
    }

    public function readMessages(Request $request)
    {
        $lastMessage = Message::where('chat_id', $request->chat_id)->latest()->first();
        
        $user = User::find($request->user_id);
        if ($lastMessage) {
            $user->chats()->updateExistingPivot($request->chat_id, [
                'last_read_message_id' => $lastMessage->id,
            ]);
        }
        return response()->json('ok', 200);
    }
    
}
