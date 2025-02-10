<?php

namespace App\Livewire;

use Livewire\Component;
use LivewireUI\Modal\ModalComponent;
use App\Models\User;
use App\Models\Chat;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CreateChatModal extends ModalComponent
{
    public $activeTab = 'single'; // Default to single chat
    public $groupChatName;
    public $selectedUser;
    public $selectedUsers = [];

    public $users; // Holds the list of users

    public function mount()
    {
        $this->users = User::all()->reject(function ($user) {
            return $user->id === Auth::user()->id;
        });
    }

    public function setTab($tab)
    {
        $this->activeTab = $tab;
    }

    public function addUserToGroup()
    {
        if (!in_array($this->selectedUser, $this->selectedUsers)) {
            $this->selectedUsers[] = $this->selectedUser;
        }

        $this->selectedUser = null;
    }

    public function removeUser($userId)
    {
        $this->selectedUsers = array_filter($this->selectedUsers, function($user) use ($userId) {
            return $user != $userId;
        });
    }

    /**
     * Validate and create a single chat, ensuring no duplicate 1-to-1 chat exists.
     */
    public function createSingleChat()
    {
        $this->validate([
            'selectedUser' => 'required|exists:users,id',
        ]);
        
        // Check if a 1-to-1 chat between the authenticated user and the selected user already exists.
        $existingChat = Chat::where('type', 'single')
            ->whereHas('users', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->whereHas('users', function ($query) {
                $query->where('user_id', $this->selectedUser);
            })
            ->first();

        if ($existingChat) {
            // $this->dispatch('closeModal');
            session()->flash('error', 'Chat con questo utente già presente.');
            return;
        }

        // If no duplicate, create the new single chat
        $chat = Chat::create([
            'type' => 'single',
        ]);

        $chat->users()->attach([$this->selectedUser, Auth::user()->id]);
        
        $this->dispatch('closeModal');
        return redirect()->route('chats.index')->with('success', ['title' => 'Chat singola creata con successo.', 'subtitle' => 'Sarà disponibile nella lista dei tuoi messaggi']);
    }

    /**
     * Validate and create a group chat, ensuring no duplicate group chat name exists.
     */
    public function createGroupChat()
    {
        try {
          
            $this->validate([
                'groupChatName' => 'required|string|max:255|unique:chats,name', 
                'selectedUsers' => 'required|array|min:2', 
                'selectedUsers.*' => 'exists:users,id',
            ]);
          
            $chat = Chat::create([
                'name' => $this->groupChatName,
                'type' => 'group',
            ]);

            $this->selectedUsers[] = (string) Auth::user()->id;
            
            $chat->users()->attach($this->selectedUsers);
    
            $this->dispatch('closeModal');
            return redirect()->route('chats.index')->with('success', ['title' => 'Chat di gruppo creata con successo.', 'subtitle' => 'Sarà disponibile nella lista dei gruppi']);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            session()->flash('error', 'Errore durante la creazione: ' . $e->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.create-chat-modal', [
            'users' => $this->users,
        ]);
    }
}
