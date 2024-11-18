<div class="z-40">
    <!-- Button to trigger sidebar -->
    <div id="toggleSidebar" wire:click='toggleSidebar'
        class="cursor-pointer w-8 h-8 bg-[#F0F0F0] rounded-full flex items-center place-content-center">
        <img src="{{ asset('images/button header notifiche.svg') }}" alt="notifications"
            class="cursor-pointer hover:text-primary">
    </div>

    <!-- Overlay -->
    <div id="overlayNotification" class="fixed inset-0 bg-black opacity-40 {{ $isSidebarOpen ? 'block' : 'hidden' }} z-40"
        wire:click="closeSidebar"></div>

    <!-- Sidebar -->
    <div id="notificationSidebar"
        class="z-50 fixed top-0 right-0 w-96 h-full bg-white shadow-lg transform {{ $isSidebarOpen ? 'translate-x-0' : 'translate-x-full' }} transition-transform duration-300 ease-in-out overflow-y-auto">
        <div class="bg-[#F5F5F5] h-20 flex justify-between place-items-center px-6">
            <span class="text-[#222222] text-lg">Notifiche</span>
            <button wire:click="closeSidebar" class="text-gray-500 hover:text-[#9F9F9F] text-3xl">&times;</button>
        </div>

        <!-- Notification List -->
        <div class="pb-6">
            <ul id="notificationList" class="mb-4">
                @if ($groupedNotifications->isEmpty())
                    <li>
                        <p class="text-gray-500 px-4">Nessuna notifica</p>
                    </li>
                @else
                    @foreach ($groupedNotifications as $group)
                        <h5 class="text-[15px] text-[#9F9F9F] px-4 mt-4">{{ $group['formatted_date'] }}</h5>
                        @foreach ($group['notifications'] as $notification)
                            <li class="px-4 flex justify-between items-center py-2 border-b border-b-[#F0F0F0] cursor-pointer"
                                wire:click='markAsRead("{{ $notification['id'] }}")'>
                                <div class="text-[#222222] text-sm">
                                    {{ $notification['data']['title'] }}
                                    <div>{{ $notification['data']['message'] }}</div>
                                    <span class="text-[#747881] text-xs">
                                        {{ \Carbon\Carbon::parse($notification['created_at'])->locale('it')->diffForHumans() }}
                                    </span>
                                </div>

                                @if (is_null($notification['read_at']))
                                    <button id="read-{{ $notification['id'] }}" type="button"
                                        class="w-1.5 h-1.5 bg-[#DC0814] rounded-full"></button>
                                @endif
                            </li>
                        @endforeach
                    @endforeach
                @endif
            </ul>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('notificationSidebar');
        const overlay = document.getElementById('overlayNotification');

        // Listen for closeSidebar trigger
        Livewire.on('closeSidebar', function() {
            sidebar.classList.add('translate-x-full');
            overlay.classList.add('hidden');
        });

        // Listen for new notifications via Echo
        window.Echo.private('users.' + @json($id))
            .notification((notification) => {
                console.log(notification);
                if (notification.type === 'App\\Notifications\\OrderFinished') {
                    console.log('TIPO CORRETT');
                }
                // Dispatch event to Livewire with the new notification data
                Livewire.dispatch('addNotification', {
                    notificationData: {
                        id: notification.id,
                        title: notification.title,
                        message: notification.message,
                        created_at: notification.created_at, // Ensure this matches your data
                    }
                });
            });
    });
</script>
