<div class=" p-6 rounded-md shadow-lg w-full max-w-4xl relative">
    <div class="flex items-center justify-between mb-4">

        <h2 class="text-lg font-semibold ">
            {{ $mechanicId ? 'Modifica Tecnico' : 'Nuovo Tecnico' }}
        </h2>
        <button wire:click="$dispatch('closeModal')" class="text-gray-500 hover:text-[#9F9F9F] text-3xl">
            &times;
        </button>
    </div>
    <form id="mechanicForm" wire:submit.prevent="submitForm">
        <div class="grid grid-cols-2 gap-4">
            <!-- Row 1 -->
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-[#9F9F9F] text-[13px]">Nome</label>
                <input type="text" id="name" name="name" wire:model="name" required
                    class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                <span id="error-name" class="text-red-500 text-xs mt-1"></span>
            </div>
            <div class="mb-4">
                <label for="surname" class="block text-sm font-medium text-[#9F9F9F] text-[13px]">Cognome</label>
                <input type="text" id="surname" name="surname" wire:model="surname" required
                    class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                <span id="error-surname" class="text-red-500 text-xs mt-1"></span>
            </div>
            <!-- Row 2 -->
            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-[#9F9F9F] text-[13px]">Email</label>
                <input type="email" id="email" name="email" wire:model="email" required
                    class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                <span id="error-email" class="text-red-500 text-xs mt-1"></span>
            </div>
            <div class="mb-4">
                <label for="cellphone" class="block text-sm font-medium text-[#9F9F9F] text-[13px]">Cellulare</label>
                <input type="text" id="cellphone" name="cellphone" wire:model="cellphone" required
                    class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                <span id="error-cellphone" class="text-red-500 text-xs mt-1"></span>
            </div>
            <!-- Row 3 -->
            <div class="mb-4">
                <label for="cdf" class="block text-sm font-medium text-[#9F9F9F] text-[13px]">Codice
                    fiscale</label>
                <input type="text" id="cdf" name="cdf" wire:model="cdf" required
                    class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                <span id="error-cdf" class="text-red-500 text-xs mt-1"></span>
            </div>
            <div class="mb-4">
                <label for="plain_password"
                    class="block text-sm font-medium text-[#9F9F9F] text-[13px]">Password</label>
                <input type="text" id="plain_password" name="plain_password" wire:model="plain_password" required
                    class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                <span id="error-plain_password" class="text-red-500 text-xs mt-1"></span>
            </div>
            <!-- Row 4 -->
            <div class="mb-4">
                <label for="address" class="block text-sm font-medium text-[#9F9F9F] text-[13px]">Indirizzo</label>
                <input type="text" id="address" name="address" wire:model="address" required
                    class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                <span id="error-address" class="text-red-500 text-xs mt-1"></span>
            </div>
            <div class="mb-4">
                <label for="cap" class="block text-sm font-medium text-[#9F9F9F] text-[13px]">Cap</label>
                <input type="number" id="cap" name="cap" wire:model="cap" required
                    class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                <span id="error-cap" class="text-red-500 text-xs mt-1"></span>
            </div>
            <!-- Row 5 -->
            <div class="mb-4">
                <label for="province" class="block text-sm font-medium text-[#9F9F9F] text-[13px]">Provincia</label>
                <input type="text" id="province" name="province" wire:model="province" required
                    class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                <span id="error-province" class="text-red-500 text-xs mt-1"></span>
            </div>
            <div class="mb-4">
                <label for="city" class="block text-sm font-medium text-[#9F9F9F] text-[13px]">Città</label>
                <input type="text" id="city" name="city" wire:model="city" required
                    class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                <span id="error-city" class="text-red-500 text-xs mt-1"></span>
            </div>
            {{--  <div class="mb-4">
                <label for="branch" class="block text-sm font-medium text-[#9F9F9F] text-[13px]">Filiale</label>
                <input type="text" id="branch" name="branch" wire:model="branch"
                    class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                <span id="error-branch" class="text-red-500 text-xs mt-1"></span>
            </div> --}}


        </div>
        <div class="mt-6 flex justify-end">
            <button id="modalSubmit" type="submit"
                class="py-2 px-4 bg-[#1E1B58] text-white rounded-md">Conferma</button>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const form = document.getElementById('mechanicForm');

        if (form) {
            form.addEventListener('submit', function(event) {
                console.log('submt')
                if (!validateForm()) {
                    event.preventDefault(); // Prevent form submission if validation fails
                    console.log('Form validation failed.');
                }
            });
        }

        // Listen for Livewire validation failure event
        Livewire.on('validationFailed', function() {
            console.log('Livewire validation failed');
        });
    });

    function validateForm() {
        const rules = {
            cellphone: {
                required: true,
                maxLength: 255
            },
            name: {
                required: true,
                maxLength: 255
            },
            surname: {
                required: true,
                maxLength: 255
            },
            email: {
                required: true,
                type: 'email'
            },
            plain_password: {
                required: true,
                minLength: 4
            },
            cdf: {
                required: true
            },
            address: {
                required: true,
                maxLength: 255
            },
            city: {
                required: true,
                maxLength: 255
            },
            province: {
                required: true,
                maxLength: 255
            },
            branch: {
                required: true,
                maxLength: 255
            },
            cap: {
                required: true,
                type: 'integer'
            }
        };

        let valid = true;

        // Clear previous errors
        for (const key in rules) {
            const errorElement = document.getElementById(`error-${key}`);
            if (errorElement) {
                errorElement.textContent = '';
            }
        }

        // Validate each ld
        for (const [field, rule] of Object.entries(rules)) {
            const input = document.querySelector(`[name="${field}"]`);
            if (input) {
                const errorElement = document.getElementById(`error-${field}`);

                // Check if the field is required and filled
                if (rule.required && !input.value.trim()) {
                    valid = false;
                    if (errorElement) {
                        errorElement.textContent = `${capitalize(field)} is required.`;
                    }
                }

                // Check the type of the field
                if (rule.type === 'email' && !validateEmail(input.value)) {
                    valid = false;
                    if (errorElement) {
                        errorElement.textContent = `Please enter a valid email address.`;
                    }
                }

                if (rule.type === 'integer' && isNaN(input.value)) {
                    valid = false;
                    if (errorElement) {
                        errorElement.textContent = `Please enter a valid number.`;
                    }
                }

                // Check minLength
                if (rule.minLength && input.value.length < rule.minLength) {
                    valid = false;
                    if (errorElement) {
                        errorElement.textContent =
                            `${capitalize(field)} must be at least ${rule.minLength} characters long.`;
                    }
                }

                // Check maxLength
                if (rule.maxLength && input.value.length > rule.maxLength) {
                    valid = false;
                    if (errorElement) {
                        errorElement.textContent =
                            `${capitalize(field)} must not exceed ${rule.maxLength} characters.`;
                    }
                }
            }
        }

        console.log(valid);
        return valid;
    }

    function validateEmail(email) {
        const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(String(email).toLowerCase());
    }

    function capitalize(str) {
        return str.charAt(0).toUpperCase() + str.slice(1);
    }
</script>
