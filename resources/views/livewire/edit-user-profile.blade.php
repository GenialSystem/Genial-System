<div>
    <h4 class="text-[22px] text-[#222222] font-semibold mb-4">Profilo</h4>

    <div class="2xl:flex 2xl:space-x-4 spacey-y-4 2xl:space-y-0">
        <div class="flex gap-4 2xl:block 2xl:w-1/3 2xl:space-y-4">
            <div class="bg-white p-4 rounded-sm w-full">
                <div class="p-1 bg-[#F2F1FB] mb-4">
                    <span class="text-[15px] text-[#222222]">Dati anagrafici</span>

                </div>

                <form wire:submit.prevent="updateUser"> <!-- Form for submitting updates -->

                    <div class="flex justify-between mb-4">
                        <div class="rounded-full w-20 h-20 relative" wire:loading.class='opacity-50'>
                            <div class="z-20 absolute rounded-full w-5 h-5 bg-[#4453A5] bottom-0 right-0">
                                <img src="{{ asset('images/Camera Icona.svg') }}" alt="camera-icona">
                            </div>
                            <input type="file" wire:model="profileImage" accept="image/*" placeholder="Foto"
                                @disabled(!$isEditing)
                                class="z-20 absolute inset-0 w-full h-full opacity-0 cursor-pointer" />
                            @if ($profileImageUrl)
                                <img src="{{ $profileImageUrl }}" alt="Profile Image"
                                    class="rounded-full w-full h-full object-cover absolute inset-0">
                            @else
                                <img src="{{ asset('images/placeholder.png') }}" alt="Profile Image"
                                    class="rounded-full w-full h-full object-cover absolute inset-0">
                            @endif
                        </div>



                        @if ($isEditing)
                            <div>
                                <button type="button" wire:click="toggleEdit"
                                    class="mr-2 bg-[#F5F5F5] text-[#9F9F9F] px-4 text-[15px] font-semibold py-1 rounded-md focus:outline-none">
                                    Annulla
                                </button>
                                <button type="submit"
                                    class="bg-[#EDF8FB] text-[#66C0DB] px-4 text-[15px] font-semibold py-1 rounded-md focus:outline-none">
                                    Salva
                                </button>

                            </div>
                        @else
                            <div
                                class="bg-[#EDF8FB] h-8 w-6 p-1 flex items-center justify-center group hover:bg-[#66C0DB] duration-200 rounded-sm">

                                <button type="button" wire:click="toggleEdit" class="flex items-center justify-center">

                                    <svg xmlns="http://www.w3.org/2000/svg" width="15.559" height="20.152"
                                        viewBox="0 0 15.559 20.152" class="group-hover:fill-white">
                                        <g id="noun-pencil-3690771" transform="translate(-7.171 -0.615)">
                                            <g id="Layer_19" data-name="Layer 19" transform="translate(7.48 0.946)">
                                                <path id="Tracciato_755" data-name="Tracciato 755"
                                                    d="M1.085,16.985a.43.43,0,0,0,.617.263l3.5-1.874a.43.43,0,0,0,.192-.21L9.939,4.493h0l.83-1.948a.429.429,0,0,0-.226-.563L5.977.035a.43.43,0,0,0-.564.227L.034,12.879a.431.431,0,0,0-.018.284ZM6.035.993,9.811,2.6,9.318,3.759,5.545,2.143Zm-.827,1.94L8.981,4.55,4.659,14.687l-2.892,1.55L.884,13.078Z"
                                                    transform="matrix(0.966, 0.259, -0.259, 0.966, 4.477, 0)"
                                                    fill="#66c0db"
                                                    class="group-hover:fill-white transition-colors duration-200" />
                                            </g>
                                        </g>
                                    </svg> <!-- Show SVG when not editing -->
                                </button>
                            </div>
                        @endif
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="name" class="block text-sm text-[#9F9F9F] text-[13px]">Nome</label>
                            <input type="text" wire:model="name" name="name" id="name"
                                class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none"
                                @disabled(!$isEditing) /> <!-- Editable based on $isEditing -->
                        </div>
                        <div>
                            <label for="surname" class="block text-sm text-[#9F9F9F] text-[13px]">Cognome</label>
                            <input type="text" wire:model="surname" name="surname" id="surname"
                                class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none"
                                @disabled(!$isEditing) /> <!-- Editable based on $isEditing -->
                        </div>
                        <div>
                            <label for="email" class="block text-sm text-[#9F9F9F] text-[13px]">Email</label>
                            <input type="text" wire:model="email" name="email" id="email"
                                class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none"
                                @disabled(!$isEditing) /> <!-- Editable based on $isEditing -->
                        </div>
                        <div>
                            <label for="cellphone" class="block text-sm text-[#9F9F9F] text-[13px]">Cellulare</label>
                            <input type="text" wire:model="cellphone" name="cellphone" id="cellphone"
                                class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none"
                                @disabled(!$isEditing) /> <!-- Editable based on $isEditing -->
                        </div>
                        <div>
                            <label for="cdf" class="block text-sm text-[#9F9F9F] text-[13px]">Codice
                                Fiscale</label>
                            <input type="text" wire:model="cdf" name="cdf" id="cdf"
                                class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none"
                                @disabled(!$isEditing) /> <!-- Editable based on $isEditing -->
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <div>
                            <label for="address" class="block text-sm text-[#9F9F9F] text-[13px]">Indirizzo</label>
                            <input type="text" wire:model="address" name="address" id="address"
                                class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none"
                                @disabled(!$isEditing) /> <!-- Editable based on $isEditing -->
                        </div>
                        <div>
                            <label for="cap" class="block text-sm text-[#9F9F9F] text-[13px]">Cap</label>
                            <input type="text" wire:model="cap" name="cap" id="cap"
                                class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none"
                                @disabled(!$isEditing) /> <!-- Editable based on $isEditing -->
                        </div>
                        <div>
                            <label for="city" class="block text-sm text-[#9F9F9F] text-[13px]">Città</label>
                            <input type="text" wire:model="city" name="city" id="city"
                                class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none"
                                @disabled(!$isEditing) /> <!-- Editable based on $isEditing -->
                        </div>
                        <div>
                            <label for="province" class="block text-sm text-[#9F9F9F] text-[13px]">Provincia</label>
                            <input type="text" wire:model="province" name="province" id="province"
                                class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none"
                                @disabled(!$isEditing) /> <!-- Editable based on $isEditing -->
                        </div>
                        {{-- <div>
                            <label for="password" class="block text-sm text-[#9F9F9F] text-[13px]">Password</label>
                            <input type="password" wire:model='password' name="password" id="password"
                                class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none"
                                @disabled(!$isEditing) /> <!-- Editable based on $isEditing -->
                        </div> --}}
                    </div>
                    <div class="grid grid-cols-2 gap-4 mt-4">
                        <!-- Old Password -->
                        <div>
                            <label for="oldPassword" class="block text-sm text-[#9F9F9F] text-[13px]">Vecchia
                                Password</label>
                            <input type="password" wire:model="oldPassword" id="oldPassword"
                                class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none"
                                @disabled(!$isEditing) />

                            <!-- Show oldPassword error message -->
                            @error('oldPassword')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- New Password -->
                        <div>
                            <label for="newPassword" class="block text-sm text-[#9F9F9F] text-[13px]">Nuova
                                Password</label>
                            <input type="password" wire:model="newPassword" id="newPassword"
                                class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none"
                                @disabled(!$isEditing) />

                            <!-- Show newPassword error message -->
                            @error('newPassword')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="confirmPassword" class="block text-sm text-[#9F9F9F] text-[13px]">Conferma
                                Password</label>
                            <input type="password" wire:model="confirmPassword" id="confirmPassword"
                                class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none"
                                @disabled(!$isEditing) />

                            <!-- Show confirmPassword error message -->
                            @error('confirmPassword')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                </form>
            </div>
        </div>

        <div class="2xl:w-2/3 w-full bg-white p-4 h-min">
            <div class="p-1 bg-[#F2F1FB] mb-4">
                <span class="text-[15px] text-[#222222]">Notifiche</span>
            </div>
            @livewire('notification-preferences')

        </div>
    </div>

    <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="py-2 px-4 bg-red-500 text-white rounded-md text-sm mt-10" wire:loading.remove>
            Logout
        </button>
    </form>


</div>
