<?php

namespace App\Livewire;

use App\Models\Chat;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use LivewireUI\Modal\ModalComponent;

class DeleteChatModal extends ModalComponent
{
    public $chatId;

    public function delete()
    {
        // Find the chat by ID
        $chat = Chat::with('messages')->find($this->chatId); // Eager load messages

        if ($chat) {
            // Optionally, detach users if necessary
            $chat->users()->detach(); // Detach users associated with the chat
            
            // Force delete messages if using soft deletes
            // This will permanently delete all messages associated with the chat
            $chat->messages()->forceDelete();
    
            // Delete the chat itself
            $chat->delete();
    
            // Remove the folder from storage
            $folderPath = 'public/chats/' . $this->chatId; // Define the folder path
            if (Storage::exists($folderPath)) {
                Storage::deleteDirectory($folderPath); // Delete the folder
            }

            $this->dispatch('closeModal');
            return redirect()->route('chats.index')->with('success', ['title' => 'Chat eliminata con successo', 'subtitle' => 'Non sarà più visibile in questa pagina']);
        }
        dd();
    }

    public function render()
    {
        return view('livewire.delete-chat-modal');
    }
}
