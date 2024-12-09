<?php

namespace App\Livewire;

use App\Events\MessageSent;
use Livewire\Component;
use App\Models\Chat;
use App\Models\Message;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Livewire\WithFileUploads;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

class ChatList extends Component
{
    use WithFileUploads;
    
    public $file; 
    public $selectedFile;
    public $chats;
    public $selectedChat = null; // Inizialmente nessuna chat è selezionata
    public $messages = [];
    public $newMessage = '';
    public $filterType = 'single';
    public $otherUser = null; // Aggiunto per gestire l'utente con cui stai chat
    public $messageCount = 20; // Numero iniziale di messaggi da caricare
    protected $listeners = ['loadMoreMessages', 'sendMessage', 'messageReceived', 'test', 'incrementUnreadCount', 'clearFileInput'];
    public $searchTerm = '';
    public $currentFilter = 'single';
    public $backgroundColors = [
        'A' => 'bg-[#FCEEEE]',
        'B' => 'bg-[#E3FCEF]',
        'C' => 'bg-[#EBF0FC]',
        'D' => 'bg-[#FCF3E4]',
        'Z' => 'bg-[#F7F0FC]',
    ];
    
    public $textColors = [
        'A' => 'text-[#FDA254]',
        'B' => 'text-[#68C9BB]',
        'C' => 'text-[#E57A7A]',
        'D' => 'text-[#FCF3E4]',
        'Z' => 'text-[#F7F0FC]',
    ];
    public $fileMessages = [];

    public function clearFileInput()
    {
        $this->file = null;
    }

    public function mount()
    {
        if (Auth::user()->roles->pluck('name')->first() == 'customer') {
            $this->filterType = 'client';
        }
        $this->loadChats();
      
        $this->dispatch('messagesLoaded');
    }


    public function test($message)
    {
        $this->messages[] = $message;
        $this->updateLastMessage($message);
        $this->dispatch('messageSent', $message);
    }

    public function loadChats()
    {
        // Load chats based on the filter type
        if ($this->filterType == 'client') {
            $this->chats = Auth::user()->chats()
                ->where('type', 'single')
                ->whereNotNull('order_id')
                ->with(['users', 'latestMessage']) // Eager load relationships
                ->get();
        } else {
            $this->chats = Auth::user()->chats()
                ->where('type', $this->filterType)
                ->whereNull('order_id')
                ->with(['users', 'latestMessage']) // Eager load relationships
                ->get();
        }

        // Calculate unread counts and last message details
        foreach ($this->chats as $index => $chat) {
            $pivotData = $chat->pivot;

            if (isset($pivotData->last_read_message_id)) {
                $lastReadMessageId = $pivotData->last_read_message_id;
                $lastReadMessage = Message::find($lastReadMessageId);

                if ($lastReadMessage) {
                    $this->chats[$index]['unread_count'] = Message::where('chat_id', $chat->id)
                        ->where('created_at', '>', $lastReadMessage->created_at)
                        ->count();
                } else {
                    $this->chats[$index]['unread_count'] = Message::where('chat_id', $chat->id)->count();
                }
            } else {
                $this->chats[$index]['unread_count'] = Message::where('chat_id', $chat->id)->count();
            }

            // Update last message details
            if (isset($chat->latestMessage)) {
                $this->chats[$index]['latest_message'] = [
                    'content' => $chat->latestMessage->content,
                    'created_at' => $chat->latestMessage->created_at,
                ];
                // Add the timestamp of the latest message for sorting
                $this->chats[$index]['latest_message_time'] = $chat->latestMessage->created_at;
            } else {
                // If there is no latest message, set a default value for sorting
                $this->chats[$index]['latest_message_time'] = null;
            }
        }

        // Sort chats based on latest message time (descending), and ensure chats with no messages are at the end
        $this->chats = $this->chats->sortByDesc(function ($chat) {
            return $chat['latest_message_time'] ?? Carbon::now()->subYear(); // Sort nulls to the end
        });

        // Filter chats if a search term is present
        if ($this->searchTerm) {
            $this->chats = $this->chats->filter(function ($chat) {
                if ($chat->type === 'single') {
                    $otherUser = collect($chat->users)
                        ->where('id', '!=', Auth::user()->id)
                        ->first();
                    return stripos($chat->order_id, $this->searchTerm) !== false || stripos($otherUser->name, $this->searchTerm) !== false || stripos($otherUser->surname, $this->searchTerm) !== false;
                } else {
                    return stripos($chat->name, $this->searchTerm) !== false;
                }
            });
        }

        $this->dispatch('chatsFiltered');
        $this->chats = $this->chats->toArray();
    }

    public function markLastMessageAsRead($chatId)
    {
        $lastMessage = Message::where('chat_id', $chatId)->latest()->first();
        // dd($lastMessage);
        if ($lastMessage) {
            Auth::user()->chats()->updateExistingPivot($chatId, [
                'last_read_message_id' => $lastMessage->id,
            ]);
        }
    }

    public function updatedSearchTerm()
    {
        $this->loadChats(); // Ricarica le chat ogni volta che il termine di ricerca cambia
    }

    public function resetUnreadCount($chatId)
    {
        foreach ($this->chats as &$chat) {
            if ($chat['id'] == $chatId) {
                $chat['unread_count'] = 0;

                // Aggiorna il campo last_read_message_id nella tabella pivot per l'utente corrente
                $lastMessage = Message::where('chat_id', $chatId)->latest()->first();
                if ($lastMessage) {
                    Auth::user()->chats()->updateExistingPivot($chatId, [
                        'last_read_message_id' => $lastMessage->id,
                    ]);
                }
                break;
            }
        }
    }


    public function selectChat($chatId)
    {
        // Trova e seleziona la chat
        $this->selectedChat = Auth::user()->chats()->with('users')->find($chatId);
        
        if ($this->selectedChat) {
            $this->loadMessages();
            $this->setOtherUser();

            // Azzera il contatore dei messaggi non letti per la chat selezionata
            foreach ($this->chats as &$chat) {
                if ($chat['id'] == $chatId) {
                    $chat['unread_count'] = 0; // Azzera il conteggio
                    $this->resetUnreadCount($chat['id']);
                    break;
                }
            }
        }
        $this->fileMessages = Message::where('chat_id', $this->selectedChat['id'])
        ->whereNotNull('file_path')
        ->get();
           $receiverId = $this->selectedChat->users->where('id', '!=', Auth::user()->id)->pluck('id')[0];
      
        $this->dispatch('chatSelected', $this->selectedChat->id, $receiverId, $this->selectedChat->users);
    }


    public function loadMessages()
    {
        $this->messages = Message::where('chat_id', $this->selectedChat->id)
            ->latest() // Carica i messaggi più recenti
            ->with('user') // Assicurati di caricare anche l'utente
            ->take(20) // Carica i primi 20 messaggi
            ->get()
            ->reverse() // Inverti l'array per visualizzarli correttamente
            ->toArray();
    
        // Invia l'evento dopo aver caricato i messaggi
        $this->dispatch('messagesLoaded');
    
        // Per debug: rimuovi o commenta questa riga in produzione
        // dd($this->messages);
    }
    

    public function loadMoreMessages()
    {
        
     $this->messageCount += 20; // Aumenta il numero di messaggi da caricare
        $this->messages = Message::where('chat_id', $this->selectedChat->id)
            ->latest() // Carica i messaggi più recenti
            ->with('user') // Assicurati di caricare anche l'utente
            ->take($this->messageCount) // Carica i primi 20 messaggi
            ->get()
            ->reverse() // Inverti l'array per visualizzarli correttamente
            ->toArray();
    
        // Invia l'evento dopo aver caricato i messaggi
        $this->dispatch('messagesLoaded');
            // dd(0);
    }
    
    


    public function setOtherUser()
    {
        if ($this->selectedChat && count($this->selectedChat['users']) > 1) {
            // Trova l'utente che non è l'utente attualmente connesso
            // dd($this->selectedChat['users']);
            $this->otherUser = $this->selectedChat['users'][0]['id'] === Auth::user()->id 
                ? $this->selectedChat['users'][1] 
                : $this->selectedChat['users'][0];
        } else {
            // Se ci sono meno di 2 utenti, imposta otherUser a null o gestisci il caso come preferisci
            $this->otherUser = null;
        }
    }

    public function messageReceived($message)
    {
        $this->messages[] = $message;  // Aggiungi il nuovo messaggio alla lista
    }
    
    public function sendMessage()
    {
        // Validate the new message and file (if file exists)
        $this->validate([
            'newMessage' => 'nullable|string|max:255', // Message can be empty if a file is attached
            'file' => 'nullable|file|max:10240', // File can be up to 10MB
        ]);

        // Initialize file path as null
        $filePath = null;

                
        if ($this->file) {
            // Get the original file name and extension
            $originalName = pathinfo($this->file->getClientOriginalName(), PATHINFO_FILENAME);
            $extension = $this->file->getClientOriginalExtension();

            // Sanitize the file name (remove special characters, spaces, etc.)
            $sanitizedName = Str::slug($originalName, '_');

            // Append timestamp to ensure uniqueness
            $timestampedName = time() . $sanitizedName . '.' . $extension;

            // Store the file
            $filePath = $this->file->storeAs(
                "chats/{$this->selectedChat['id']}/files",
                $timestampedName,
                'public'
            );

            // Clear the file input after upload
            $this->file = null;
        }

        // Create the new message with or without a file
        $message = Message::create([
            'chat_id' => $this->selectedChat['id'],
            'user_id' => Auth::user()->id,
            'content' => $this->newMessage, // Allowing empty content if file is attached
            'file_path' => $filePath, // Add the file path if it exists
        ]);

        // Load the user relationship (if necessary)
        $message->user;

        // Broadcast the message to others (if using broadcasting)
        MessageSent::broadcast($message)->toOthers();

        // Append the new message to the current list of messages
        $this->messages[] = $message->toArray();

        // Update the last message for the chat (if applicable)
        $this->updateLastMessage($message);

        // Reset the new message input field
        $this->newMessage = '';
       
        // Dispatch a messageSent event for any further actions
        $this->dispatch('messageSent', $message);
    }

    public function filterChats($type = null)
    {
        $this->filterType = $type;
        $this->currentFilter = $type;
        // dd($this->filterType);
        $this->loadChats();
        $this->dispatch('chatsFiltered');
        
        $this->dispatch('listenChats', $this->chats);
        // $this->selectedChat = null;
    }

    public function incrementUnreadCount($chatId, $message)
    {
        foreach ($this->chats as $key => $chat) {
            if ($chat['id'] == $chatId) {
                // Increment unread message count
                $this->chats[$key]['unread_count'] = isset($chat['unread_count']) ? $chat['unread_count'] + 1 : 1;
                
                // Update the latest message content and timestamp
                $this->chats[$key]['latest_message'] = [
                    'content' => $message['content'],
                    'created_at' => $message['created_at'],
                ];

                // Remove the chat from its current position
                $updatedChat = $this->chats[$key];
                unset($this->chats[$key]);

                // Reindex the array to avoid gaps after unset
                $this->chats = array_values($this->chats);

                // Add the updated chat to the front
                array_unshift($this->chats, $updatedChat);
                
                break; // Exit the loop after finding the chat
            }
        }
    }

    
    public function updateLastMessage($message)
    {
        foreach ($this->chats as $key => &$chat) {
            if ($chat['id'] == $message['chat_id']) {
                // Aggiorna l'ultimo messaggio e la sua data
                $chat['latest_message'] = [
                    'content' => $message['content'],
                    'created_at' => $message['created_at'],
                ];

                // Rimuovi il chat aggiornato dall'array originale
                unset($this->chats[$key]);

                // Inserisci il chat aggiornato all'inizio dell'array
                array_unshift($this->chats, $chat);

                // Salva il nuovo stato per renderlo persistente
                Auth::user()->chats()->updateExistingPivot($chat['id'], [
                    'last_read_message_id' => $message['id'],
                ]);
                
                break;
            }
        }
    }

    public function render()
    {
        return view('livewire.chat-list');
    }
}
