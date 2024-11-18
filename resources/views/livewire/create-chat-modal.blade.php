<div class="p-6">
    <div class="border-b border-gray-200">
        <nav class="-mb-px flex space-x-8">
            <a wire:click.prevent="setTab('single')" href="#"
                class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'single' ? 'border-[#1E1B58] text-[#1E1B58]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Chat singola
            </a>
            <a wire:click.prevent="setTab('group')" href="#"
                class="whitespace-nowrap pb-4 px-1 border-b-2 font-medium text-sm {{ $activeTab === 'group' ? 'border-[#1E1B58] text-[#1E1B58]' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                Chat di gruppo
            </a>
        </nav>
    </div>

    <!-- Tabs content -->
    <div class="mt-6">
        @if ($activeTab === 'single')
            <form wire:submit.prevent="createSingleChat">

                <div class="mb-4">
                    <label for="selectedUser" class="block text-sm font-medium text-gray-700">Seleziona utente</label>
                    <select wire:model="selectedUser" id="selectedUser"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-[#1E1B58] focus:ring-[#1E1B58] sm:text-sm">
                        <option value="">Seleziona un utente</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }} {{ $user->surname }}</option>
                        @endforeach
                    </select>
                </div>
                @error('selectedUser')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
                @if (session('error'))
                    <div class="text-red-500 text-sm mt-1">{{ session('error') }}</div>
                @endif
                <div class="mt-6 text-end">
                    <button type="submit"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-[#1E1B58] focus:outline-none">
                        Conferma
                    </button>
                </div>
            </form>
        @elseif ($activeTab === 'group')
            <!-- Group Chat form -->
            <form wire:submit.prevent="createGroupChat">
                <div class="mb-4">
                    <label for="groupChatName" class="block text-sm font-medium text-gray-700">Nome gruppo</label>
                    <input wire:model="groupChatName" type="text" id="groupChatName"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:[#1E1B58] sm:text-sm"
                        placeholder="Inserici il nome del gruppo">
                    @error('groupChatName')
                        <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="selectedUser" class="block text-sm font-medium text-gray-700">Seleziona utenti</label>
                    <select wire:model="selectedUser" id="selectedUser"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:[#1E1B58] sm:text-sm">
                        <option value="">Seleziona utenti</option>
                        @foreach ($users as $user)
                            <option wire:click="addUserToGroup" value="{{ $user->id }}">{{ $user->name }}
                                {{ $user->surname }}</option>
                        @endforeach
                    </select>

                </div>
                @if (session('error'))
                    <div class="text-red-500 text-sm mt-1">{{ session('error') }}</div>
                @endif
                <!-- Display selected users -->
                <div id="selectedUsers" class="flex flex-wrap gap-2">
                    @foreach ($selectedUsers as $selected)
                        @php
                            $user = $users->find($selected);
                        @endphp
                        <div
                            class="w-max bg-[#F2F1FB] text-[#4453A5] font-semibold rounded-md px-2 py-1.5 text-[13px] mt-2">
                            {{ $user->name }} {{ $user->surname }}
                            <button type="button" wire:click="removeUser({{ $selected }})"
                                class="ml-2 text-red-500">
                                &times;
                            </button>
                        </div>
                    @endforeach
                </div>

                <div class="mt-6 text-end">
                    <button type="submit"
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-[#1E1B58]">
                        Conferma chat di gruppo
                    </button>
                </div>
            </form>
        @endif
    </div>
</div>
