<div class="pl-4 pr-9 py-4 flex justify-between items-center bg-white mx-6 my-4">
    <!-- Left Input -->
    <input type="text" placeholder="Cerca..."
        class="border border-gray-300 text-gray-900 text-sm rounded-lg bg-[#F5F5F5] px-4 py-2" />

    <!-- Right Section -->
    <div class="flex items-center space-x-4">
        <!-- Language Selector -->
        <div class="">
            <select class="border-none text-gray-900 text-sm rounded-lg">
                <option value="it">Italiano</option>
                <option value="en">English</option>
                <option value="fr">Français</option>
            </select>
        </div>

        <div class=" w-8 h-8 bg-[#F0F0F0] rounded-full flex items-center place-content-center">
            <img src="{{asset('images/chat icon.svg')}}" alt="Notifications" class="cursor-pointer hover:text-primary">
        </div>

        <!-- Notification Icon -->
        <div class=" w-8 h-8 bg-[#F0F0F0] rounded-full flex items-center place-content-center">
            <img src="{{asset('images/button header notifiche.svg')}}" alt="Notifications"
                class="cursor-pointer hover:text-primary">
        </div>

        <!-- Profile Image -->
        <div class="flex items-center">
            <img src="{{asset('images/Foto profilo.jpg')}}" alt="Profile"
                class="w-8 h-8 rounded-full cursor-pointer mr-2">
            <div>
                <span class="text-secondary text-sm">{{Auth::user()->name}}</span>
                <div class="text-xs text-[#747881]">superadmin</div>
            </div>
        </div>
    </div>
</div>