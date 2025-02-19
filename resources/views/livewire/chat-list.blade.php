<div>
    <div class="flex justify-between">
        <h3 class="text-[#222222] text-[22px] font-semibold mb-4">Chat</h3>
        @role('admin')
            <button
                class="h-8 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-[#1E1B58]"
                wire:click="$dispatch('openModal', {component: 'create-chat-modal'})">+ Crea nuova conversazione</button>
        @endrole
    </div>
    <div class="flex gap-4">
        <!-- Lista delle chat -->
        <div class="w-1/4 bg-white overflow-y-auto p-4 h-[700px]">
            <div class="flex flex-col 2xl:flex-row gap-3">
                @hasanyrole(['admin', 'mechanic'])
                    <button
                        class="text-sm p-2 rounded {{ $currentFilter === 'single' ? 'bg-[#4453A5] text-white' : 'bg-[#F0F0F0] text-[#9F9F9F]' }}"
                        wire:click="filterChats('single')">
                        I miei messaggi
                    </button>
                    <button
                        class="text-sm p-2 rounded {{ $currentFilter === 'group' ? 'bg-[#4453A5] text-white' : 'bg-[#F0F0F0] text-[#9F9F9F]' }}"
                        wire:click="filterChats('group')">
                        Messaggi Gruppi
                    </button>
                @endhasanyrole
                @role('admin')
                    <button
                        class="text-sm p-2 rounded {{ $currentFilter === 'client' ? 'bg-[#4453A5] text-white' : 'bg-[#F0F0F0] text-[#9F9F9F]' }}"
                        wire:click="filterChats('client')">
                        Clienti
                    </button>
                @endrole
            </div>

            <input type="text" wire:model.debounce.300ms.live="searchTerm" placeholder="Cerca elemento..."
                class="border border-gray-300 h-8 p-2 rounded w-full my-4 text-[#9F9F9F] text-sm">
            <ul>
                @foreach ($chats as $chat)
                    <li class="py-4 hover:bg-[#FAFAFA] cursor-pointer border-b border-b-[#F0F0F0] truncate"
                        wire:click="selectChat({{ $chat['id'] }})" wire:click='markAsRead("{{ $chat['id'] }}")' wire:key="chat-{{ $chat['id'] }}">
                        @if ($chat['type'] === 'single')
                            @php
                                $otherUser = collect($chat['users'])
                                    ->where('id', '!=', Auth::user()->id)
                                    ->first();
                                $lastMessage = $chat['latest_message']['content'] ?? 'Nessun messaggio'; // Ultimo messaggio
                            @endphp
                            <div class="flex flex-col xl:flex-row">
                                <div class="relative">
                                    <img class="w-8 h-8 rounded-full border mr-2"
                                        src="{{ asset($otherUser['image_path'] ?? 'images/placeholder.png') }}"
                                        alt="profile image">
                                    @if ($chat['unread_count'] > 0)
                                        <div
                                            class="absolute bottom-2 left-0 w-4 h-4 rounded-full bg-[#FCE5E8] text-[#DC0814] text-[11px] text-center">
                                            {{ $chat['unread_count'] }}
                                        </div>
                                    @endif
                                </div>
                                <div class="w-full ml-1">
                                    <div class="flex flex-col xl:flex-row justify-between">
                                        <span
                                            class="text-[#222222]">{{ $otherUser['name'] . ' ' . $otherUser['surname'] ?? 'Utente non trovato' }}</span>
                                        @if ($chat['latest_message'])
                                            <p wire:ignore id="last-message-time-{{ $chat['id'] }}"
                                                class="text-gray-500 text-sm mt-1 last-message-time"
                                                data-time="{{ $chat['latest_message']['created_at'] }}"
                                                data-chat-id="{{ $chat['id'] }}">
                                                {{ \Carbon\Carbon::parse($chat['latest_message']['created_at'])->diffForHumans() }}
                                                <!-- Use diffForHumans for better readability -->
                                            </p>
                                        @endif
                                    </div>
                                    @if ($chat['order_id'])
                                        <p class="text-[#4453A5] text-sm mt-1 w-1">Riparazione #{{ $chat['order_id'] }}
                                        </p>
                                    @else
                                        <p translate="no" class="text-[#656565] text-sm mt-1 w-1">{{ $lastMessage }}</p>
                                    @endif
                                </div>
                            </div>
                        @else
                            @php
                                $initial = strtoupper(substr($chat['name'], 0, 1));
                                $bgColor = $backgroundColors[$initial] ?? reset($backgroundColors);
                                $textColor = $textColors[$initial] ?? reset($textColors);
                                $lastMessage = $chat['latest_message']['content'] ?? 'Nessun messaggio';
                            @endphp
                            <div class="flex items-center">
                                <div
                                    class="w-10 h-10 flex items-center justify-center rounded-full {{ $bgColor }} mr-2">
                                    <span class="font-semibold {{ $textColor }}">{{ $initial }}</span>
                                </div>
                                <div>
                                    <span class="text-[#222222]">{{ $chat['name'] }}</span>
                                    <p class="text-gray-500 text-sm mt-1">{{ $lastMessage }}</p>
                                </div>
                                <div class="flex-grow"></div>
                                @if ($chat['unread_count'] > 0)
                                    <div class="w-2 h-2 rounded-full bg-[#DC0851]"></div>
                                @endif
                            </div>
                        @endif
                    </li>
                @endforeach

            </ul>
        </div>

        <!-- Messaggi della chat selezionata -->
        <div class="w-3/4 flex flex-col bg-white h-[700px]">
            @if ($selectedChat)
                @php
                    $initial = strtoupper(substr($selectedChat['name'], 0, 1));
                    $bgColor = $backgroundColors[$initial] ?? reset($backgroundColors);
                    $textColor = $textColors[$initial] ?? reset($textColors);
                    $otherUser = $selectedChat->users->where('id', '!=', Auth::user()->id)->first();
                @endphp
                <div id="sidebar"
                    class="fixed top-0 z-50 right-0 w-[450px] h-full bg-white shadow-lg transform translate-x-full transition-transform duration-300 ease-in-out">
                    <div>
                        <div class="flex justify-between">
                            <button
                                onclick="document.getElementById('sidebar').style.transform = 'translateX(100%)'; document.getElementById('overlay').classList.add('hidden');"
                                class="text-[#9F9F9F] m-6 hover:text-[#9F9F9F] text-3xl">
                                &times;
                            </button>
                            @role('admin')
                                <button
                                    wire:click="$dispatch('openModal', { component: 'delete-chat-modal', arguments : {'chatId' : {{ $selectedChat['id'] }}}})"
                                    class="bg-[#DC0814] text-white font-semibold h-8 px-3 rounded-md m-6">Elimina</button>
                            @endrole
                        </div>
                        <div class="flex items-center flex-col">
                            <div
                                class="w-10 h-10 flex items-center justify-center rounded-full {{ $bgColor }} mr-2">
                                <span class="font-semibold {{ $textColor }}">{{ $initial }}</span>
                            </div>
                            <h2 class="text-[15px] mb-4">{{ $selectedChat['name'] }}</h2>
                        </div>
                        <div class="mt-2 text-[#222222] text-[15px] border-y border-[#F0F0F0] p-5">
                            <span>Membri</span>
                            @foreach ($selectedChat['users'] as $user)
                                <div wire:ignore class="flex space-x-2 my-5" id="user-{{ $user['id'] }}">
                                    <img class="w-10 h-10 rounded-full"
                                        src="{{ asset($user['image_path'] ?? 'images/placeholder.png') }}"
                                        alt="profile image">
                                    <div>
                                        <div class="flex items-center">
                                            <span id="status-{{ $user['id'] }}"
                                                class="w-2 h-2 rounded-full mr-2 bg-red-500"></span>
                                            <!-- Default to red -->
                                            {{ $user['name'] }} {{ $user['surname'] }}
                                        </div>
                                        <span class="text-[#656565] text-[13px]">{{ $user['email'] }}</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="text-[#222222] text-[15px] p-5">
                            <span>File condivisi</span>
                            @if (count($fileMessages) < 0)
                                <p class="text-gray-500 text-sm mt-2">Nessun file condiviso in questa chat.</p>
                            @else
                                <div class="space-y-2 mt-4 h-96 overflow-y-auto">
                                    @foreach ($fileMessages as $fileMessage)
                                        @php
                                            $filePath = $fileMessage->file_path;
                                            $fileName = basename($filePath); // Get just the file name from the path

                                            // Check if the file exists before trying to get its size
                                            if (Storage::disk('public')->exists($filePath)) {
                                                $fileSizeInBytes = Storage::disk('public')->size($filePath);

                                                // Format the size
                                                $fileSizeFormatted = '';
                                                if ($fileSizeInBytes >= 1073741824) {
                                                    // 1 GB
                                                    $fileSizeFormatted =
                                                        number_format($fileSizeInBytes / 1073741824, 2) . ' GB';
                                                } elseif ($fileSizeInBytes >= 1048576) {
                                                    // 1 MB
                                                    $fileSizeFormatted =
                                                        number_format($fileSizeInBytes / 1048576, 2) . ' MB';
                                                } else {
                                                    $fileSizeFormatted =
                                                        number_format($fileSizeInBytes / 1024, 2) . ' KB'; // Show in KB if smaller
                                                }
                                            } else {
                                                $fileSizeFormatted = 'File non trovato';
                                            }
                                        @endphp
                                        <div
                                            class="flex items-center justify-between space-x-2 border-b border-b-[#F0F0F0] pb-3">
                                            <a href="{{ asset('storage/' . $fileMessage->file_path) }}" target="_blank"
                                                class="text-[#222222] hover:underline">
                                                {{ $fileName }}
                                            </a>
                                            <span class="text-gray-500 text-sm">{{ $fileSizeFormatted }}</span>
                                        </div>
                                    @endforeach

                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <!-- Sezione fissa per nome utente o nome gruppo -->
                <div class="p-4 border-b border-gray-300">
                    @if ($selectedChat['type'] === 'group')
                        <div class="flex justify-between place-items-center">
                            <div class="flex">
                                <div wire:ignore
                                    class="w-10 h-10 flex items-center justify-center rounded-full {{ $bgColor }} mr-2">
                                    <span class="font-semibold {{ $textColor }}">{{ $initial }}</span>
                                </div>

                                <div class="flex flex-col">
                                    <span class="text-[#222222] text-[15px]">{{ $selectedChat['name'] }}</span>
                                    <span class="text-[#9F9F9F] text-xs">{{ count($selectedChat['users']) }}
                                        membri</span>
                                </div>
                            </div>
                            <div onclick="document.getElementById('sidebar').style.transform = 'translateX(0%)'; document.getElementById('overlay').classList.remove('hidden');"
                                class="cursor-pointer w-4 h-4 border border-black rounded-full text-black text-center text-[10px]">
                                i</div>
                        </div>
                    @else
                        <div class="flex justify-between">
                            <div class="flex">
                                <img class="inline-block w-8 h-8 rounded-full border mr-2"
                                    src="{{ asset($otherUser['image_path'] ?? 'images/placeholder.png') }}"
                                    alt="profile image">
                                <div class="flex flex-col">
                                    <span
                                        class="text-[#222222] text-[15px]">{{ $otherUser ? $otherUser['name'] . ' ' . $otherUser['surname'] : 'Utente non trovato' }}</span>
                                    <span wire:ignore id="status-text-{{ $otherUser['id'] }}"
                                        class="text-[#9F9F9F] text-xs"></span>
                                </div>
                            </div>
                            @role('admin')
                                <button
                                    wire:click="$dispatch('openModal', { component: 'delete-chat-modal', arguments : {'chatId' : {{ $selectedChat['id'] }}}})"
                                    class="bg-[#DC0814] text-white font-semibold h-8 px-3 rounded-md">Elimina</button>
                            @endrole
                        </div>
                    @endif
                </div>

                <!-- Sezione dei messaggi -->
                <div class="flex-1 p-4 overflow-y-auto" id="messages">
                    <div class="space-y-4">
                        @php
                            $previousDate = null;
                        @endphp
                        @foreach ($messages as $message)
                            @php
                                $messageDate = \Carbon\Carbon::parse($message['created_at'])
                                    ->locale('it')
                                    ->format('Y-m-d');
                            @endphp

                            <!-- Check if it's a new day and display a date separator -->
                            @if ($previousDate !== $messageDate)
                                <div class="text-center text-[#747881] text-xs my-2">
                                    <span>{{ \Carbon\Carbon::parse($message['created_at'])->locale('it')->format('F j, Y') }}</span>
                                </div>
                                @php
                                    $previousDate = $messageDate;
                                @endphp
                            @endif

                            <div
                                class="flex place-items-end {{ $message['user']['id'] === Auth::user()->id ? 'flex-row-reverse' : 'flex-row' }}">
                                <img class="inline-block w-8 h-8 rounded-full border"
                                    src="{{ asset($message['user']['image_path'] ?? 'images/placeholder.png') }}"
                                    alt="profile image">
                                <div class="w-4"></div>
                                <div >
                                    <div
                                        class="max-w-sm 2xl:max-w-3xl p-4 {{ $message['user']['id'] === Auth::user()->id ? 'bg-[#EEEDFA]' : 'bg-[#F5F5F5]' }} rounded shadow break-words">
                                        <span class="font-semibold">{{ $message['user']['name'] }}</span>

                                        <!-- Display message content -->
                                        @if ($message['content'])
                                            <p translate="no" class="whitespace-pre-wrap">{{ $message['content'] }}</p>
                                        @endif

                                        @if (
                                            $message['file_path'] &&
                                                Str::startsWith(mime_content_type(storage_path('app/public/' . $message['file_path'])), 'image'))
                                            <a href="{{ asset('storage/' . $message['file_path']) }}"
                                                target="_blank">
                                                <img src="{{ asset('storage/' . $message['file_path']) }}"
                                                    alt="file-allegato"
                                                    class="mt-2 max-w-3xl max-h-80 rounded shadow object-cover">
                                            </a>
                                        @elseif ($message['file_path'])
                                            <a href="{{ asset('storage/' . $message['file_path']) }}"
                                                target="_blank">
                                                <div class="w-20 text-6xl my-4">
                                                    📄
                                                </div>
                                                <div class="text-[#4453A5]">
                                                    {{ collect(explode('_', basename($message['file_path'])))->last() }}
                                                </div>

                                            </a>
                                        @endif
                                    </div>
                                    <span class="text-xs my-2 text-[#747881]">
                                        {{ \Carbon\Carbon::parse($message['created_at'])->format('h:i A') }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div wire:ignore id="fileDetails" class="items-center mt-y hidden mx-8">
                    <span id="fileName" class="mr-2 text-gray-600"></span>
                    <button type="button" class="text-red-500 py-1 rounded" onclick="clearFile()">X</button>
                </div>
                <!-- Input per inviare nuovi messaggi -->
                <div class="flex h-12 space-x-4 px-10 mb-6">
                    <div class="flex items-center border border-[#F0F0F0] rounded w-full p-1">
                        <input type="text" id="messageInput" wire:model="newMessage"
                            placeholder="Scrivi un messaggio..."
                            class="flex-1 p-2 rounded-none border-transparent focus:border-transparent focus:ring-0">

                        <!-- File Input -->
                        <div class="relative">
                            <input type="file" id="fileInput" wire:model.live="file" class="hidden"
                                onchange="handleFileSelect(event)">
                            <button type="button" class="px-4 text-black rounded-l h-full"
                                onclick="document.getElementById('fileInput').click()">
                                📎
                            </button>
                        </div>

                        <!-- Send Message Button -->
                        <button class="bg-[#4453A5] px-4 text-white rounded-r h-full"
                            onclick="validateAndSendMessage()">Invia</button>
                    </div>

                </div>
            @else
                <div class="flex-1 flex items-center justify-center">
                    <p>Seleziona una chat per iniziare a messaggiare</p>
                </div>
            @endif
        </div>
    </div>
    <!-- Overlay for the Sidebar -->
    <div id="overlay" class="fixed inset-0 bg-black opacity-40 hidden z-40"
        onclick="document.getElementById('sidebar').style.transform = 'translateX(100%)'; document.getElementById('overlay').classList.add('hidden');">
    </div>
</div>
<script>
    function validateAndSendMessage() {
        const messageValue = messageInput.value.trim();
        const fileInput = document.getElementById('fileInput');
        const selectedFile = fileInput.files.length > 0;

        // Check if there's either a message or a selected file
        if (messageValue !== '' || selectedFile) {
            Livewire.dispatch('sendMessage');
        }
    }

    // Function to clear the file input and related UI
    function clearFile() {
        const fileInput = document.getElementById('fileInput');
        fileInput.value = '';

        // Clear file name display
        document.getElementById('fileName').textContent = '';

        // Hide file details
        document.getElementById('fileDetails').classList.add('hidden');
        Livewire.dispatch('clearFileInput');
    }

    function handleFileSelect(event) {
        const fileInput = event.target;
        const file = fileInput.files[0];
        if (file) {
            document.getElementById('fileName').textContent = 'File pronto all\'invio: ' + file.name;
            document.getElementById('fileDetails').classList.remove('hidden');
        }
    }
    document.addEventListener('DOMContentLoaded', function() {
        let activeChatId;
        let messageContainer;
        let messageInput;


        const lastMessageElements = document.querySelectorAll('.last-message-time');

        function calculateTimeAgo(createdAt) {
            const now = new Date();
            const messageTime = new Date(createdAt);
            const diffInMilliseconds = now - messageTime;
            const diffInMinutes = Math.floor(diffInMilliseconds / 60000);
            const diffInHours = Math.floor(diffInMinutes / 60);
            const diffInDays = Math.floor(diffInHours / 24);

            if (diffInDays > 0) {
                return `${diffInDays} giorn${diffInDays > 1 ? 'i' : 'o'}`;
            } else if (diffInHours > 0) {
                return `${diffInHours} ore`;
            } else if (diffInMinutes < 1) {
                return "Adesso";
            } else {
                return `${diffInMinutes} minut${diffInMinutes < 2 ? 'o' : 'i'}`;
            }
        }

        function updateLastMessageTimes() {
            const lastMessageElements = document.querySelectorAll(
                '.last-message-time'); // Fetch all elements again

            lastMessageElements.forEach(element => {
                const createdAt = element.getAttribute('data-time');
                element.innerText = calculateTimeAgo(createdAt);

            });
        }

        // Initialize the time display
        updateLastMessageTimes();
        setInterval(updateLastMessageTimes, 60000);

        let activeListeners = {};

        function echoListenForAllChats(chatsToListen) {
            // Clean up previous listeners
            chatsToListen = Array.isArray(chatsToListen) ? chatsToListen : Object.values(chatsToListen);
            for (const chatId in activeListeners) {
                if (activeListeners[chatId]) {
                    activeListeners[chatId].stopListening(".newMessage");
                    delete activeListeners[chatId];
                }
            }
            console.log(chatsToListen);
            chatsToListen.forEach(chat => {
                if (!activeListeners[chat.id]) {
                    // console.log('Listening to chat:', chat.id);
                    activeListeners[chat.id] = window.Echo.private("chat-" + chat.id).listen(
                        ".newMessage", (event) => { // event should be passed here
                            if (event && event.message) { // Ensure event and message are defined
                                // console.log("Message received in chat " + chat.id + ":", event
                                //     .message);

                                const lastMessageElement = document.querySelector(
                                    `.last-message-time[data-chat-id="${event.message.chat_id}"]`
                                );

                                if (lastMessageElement) {
                                    lastMessageElement.setAttribute('data-time', event.message
                                        .created_at);
                                    lastMessageElement.innerText = calculateTimeAgo(event.message
                                        .created_at);
                                }

                                // Show the new message if in active chat
                                const messageElement = document.createElement('div');
                                messageElement.className = 'message';
                                messageElement.innerHTML = `<span>${event.message.content}</span>`;

                                if (activeChatId === event.message.chat_id) {
                                    Livewire.dispatch('test', {
                                        message: event.message
                                    });
                                    messageContainer.appendChild(messageElement);
                                } else {
                                    Livewire.dispatch('incrementUnreadCount', {
                                        chatId: chat.id,
                                        message: event.message
                                    });
                                }

                                setTimeout(() => {
                                    updateLastMessageTimes();
                                }, 100); // Allow time for DOM to render
                                scrollDown();
                            } else {
                                console.error("Received an invalid event:", event);
                            }
                        }
                    );
                }
            });

        }
        let chatLoadedOnce = false;

        Livewire.on('chatSelected', (event) => {
            if (activeChatId !== event[0]) {
                activeChatId = event[0];
                setTimeout(() => {
                    initializeChatElements();
                    scrollDown();
                    const statusText = document.getElementById(`status-text-${event[1]}`);
                    const isOnline = window.onlineUsers[event[1]] === true;
                    if (statusText) {
                        statusText.innerText = isOnline ? 'Online' : 'Offline';
                    }
                    const users = event[2];
                    users.forEach(user => {
                        const statusElement = document.getElementById(
                            `status-${user.id}`);

                        if (statusElement) {
                            const isOnline = window.onlineUsers[user.id] ||
                                false; // Check if the user is online
                            // Update the class name and inner text based on online status
                            statusElement.className =
                                `w-2 h-2 rounded-full mr-2 ${isOnline ? "bg-green-500" : "bg-red-500"}`;
                        }
                    });

                }, 200);
            }
        });

        Livewire.on('messageSent', (message) => {

            setTimeout(() => {
                const lastMessageElement = document.querySelector(
                    `.last-message-time[data-chat-id="${message[0]['chat_id']}"]`
                );
                if (lastMessageElement) {
                    lastMessageElement.setAttribute('data-time', message[0]['created_at']);
                    lastMessageElement.innerText = calculateTimeAgo(message[0]['created_at']);
                }

                updateLastMessageTimes(); // Trigger a full update for all timestamps
                scrollDown();
                clearFile();
            }, 100); // Allow time for DOM to render
        });



        const chats = @json($chats);
        if (chats) {
            echoListenForAllChats(chats);
        }

        const chat = @json($selectedChat);
        if (chat) {
            activeChatId = chat.id;
            scrollDown();
        }

        // Declare these globally
        function scrollDown() {
            setTimeout(() => {
                if (messageContainer) {

                    messageContainer.scrollTop = messageContainer.scrollHeight;
                }
            }, 100);
        }

        function initializeChatElements() {
            if (chatLoadedOnce) {
                return;
            }
            chatLoadedOnce = true;
            messageContainer = document.getElementById('messages');
            messageInput = document.getElementById('messageInput');

            messageInput.addEventListener('keyup', function(event) {
                if (event.key === 'Enter') {
                    event.preventDefault();
                    const messageValue = messageInput.value.trim();
                    // console.log(messageValue);
                    if (messageValue !== '') {
                        Livewire.dispatch('sendMessage');
                    }
                }
            });
            // Move the function definitions outside the conditional block


            let isLoading = false;
            messageContainer.addEventListener('scroll', () => {
                if (messageContainer.scrollTop === 0 && !isLoading) {
                    isLoading = true;

                    // Save the current scroll height and position
                    const previousScrollHeight = messageContainer.scrollHeight;
                    const previousScrollTop = messageContainer.scrollTop;

                    // Disable scrolling during loading
                    messageContainer.style.overflow = 'hidden';

                    setTimeout(() => {
                        // Load more messages using Livewire
                        Livewire.dispatch('loadMoreMessages');

                        // Use a small delay to ensure the messages are rendered before adjusting the scroll position
                        setTimeout(() => {
                            // Calculate the new scroll height difference
                            const newScrollHeight = messageContainer.scrollHeight;
                            const scrollDifference = newScrollHeight -
                                previousScrollHeight;

                            // Adjust the scroll position to maintain the user's current view
                            messageContainer.scrollTop = scrollDifference +
                                previousScrollTop;

                            // Enable scrolling again after the loading is done
                            messageContainer.style.overflow = 'auto';

                            // Loading complete
                            isLoading = false;
                        }, 100); // Adjust delay to ensure the new messages are fully rendered
                    }, 100);
                }
            });
        }

        Livewire.on('chatsFiltered', () => {

            setTimeout(() => {
                const lastMessageElements = document.querySelectorAll('.last-message-time');
                if (lastMessageElements.length === 0) {

                } else {
                    updateLastMessageTimes();
                }
            }, 10);
        });

        Livewire.on('listenChats', (chats) => {
            // Access the first element of the outer array
            const chatObject = chats[0];

            // Convert the chatObject (which is an object of objects) into an array
            const chatArray = Object.values(chatObject);

            chatArray.forEach(chat => {
                activeListeners[chat.id] = null; // Initialize listener for each chat
            });

            echoListenForAllChats(chatArray); // Pass the first chat object if needed
        });

    });
</script>
