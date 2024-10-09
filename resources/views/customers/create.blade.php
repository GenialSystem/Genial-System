@extends('layout')

@section('content')
    <div class="mx-auto p-4 bg-white 2xl:w-[1000px] rounded-md shadow-sm">
        @if ($errors->any())
            <div id="error-banner" class="bg-red-500 text-white text-center py-2 rounded-md my-4">
                {{ $errors->first() }}
            </div>
        @endif
        <h4 class="text-[#222222] font-semibold text-xl mb-4">Crea nuovo cliente</h4>
        <div class="flex place-items-center space-x-4 mb-4">
            <div id="step-indicator-1"
                class="text-sm font-medium bg-[#F2F1FB] rounded-full w-8 h-8 flex items-center justify-center text-[#1E1B58] border border-[#1E1B58]">
                1
            </div>
            <span class="text-[#1E1B58] text-[15px]">Dati anagrafici</span>
            <div id="step-indicator-2"
                class="text-sm font-medium text-[#9F9F9F] bg-[#E0E0E0] rounded-full w-8 h-8 flex items-center justify-center">
                2
            </div>
            <span id="step-2-text" class="text-[#9F9F9F] text-[15px]">Permessi</span>
        </div>
        <form action="{{ route('customers.store') }}" method="POST" class="mt-4 text-[#9F9F9F] text-[13px]">
            @csrf

            <!-- Step 1 -->
            <div id="step-1">
                <!-- Row 1 -->
                <div class="grid grid-cols-2 2xl:grid-cols-3 gap-4 mb-4">
                    <div class="">
                        <label for="name" class="block text-sm font-medium">Nome</label>
                        <input type="text" name="name" id="name"
                            class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                        <span id="name-error" class="text-red-500 text-xs hidden">Campo obbligatorio.</span>
                    </div>
                    <div class="">
                        <label for="surname" class="block text-sm font-medium">Cognome</label>
                        <input type="text" name="surname" id="surname"
                            class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                        <span id="surname-error" class="text-red-500 text-xs hidden">Campo obbligatorio.</span>
                    </div>
                    <div>
                        <label for="assigned_cars" class="block text-sm font-medium">Auto assegnate</label>
                        <input type="number" name="assigned_cars" id="assigned_cars"
                            class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                        <span id="assigned_cars-error" class="text-red-500 text-xs hidden">Campo obbligatorio.</span>
                    </div>
                    <div>
                        <label for="city" class="block text-sm font-medium">Città</label>
                        <input type="text" name="city" id="city"
                            class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                        <span id="city-error" class="text-red-500 text-xs hidden">Campo obbligatorio.</span>
                    </div>
                </div>

                <!-- Row 2 -->
                <div class="grid grid-cols-2 2xl:grid-cols-3 gap-4">
                    <div>
                        <label for="admin_name" class="block text-sm font-medium">Responsabile</label>
                        <input type="text" name="admin_name" id="admin_name"
                            class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                        <span id="admin_name-error" class="text-red-500 text-xs hidden">Campo obbligatorio.</span>
                    </div>
                    <div>
                        <label for="cellphone" class="block text-sm font-medium">Cellulare</label>
                        <input type="text" name="cellphone" id="cellphone"
                            class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                        <span id="cellphone-error" class="text-red-500 text-xs hidden">Campo obbligatorio.</span>
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium">Email</label>
                        <input type="text" name="email" id="email"
                            class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                        <span id="email-error" class="text-red-500 text-xs hidden">Campo obbligatorio.</span>
                    </div>
                </div>
                <div class="border-dashed border mb-2 mt-4"></div>
                <!-- Row 3 -->
                <div class="grid grid-cols-2 2xl:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label for="rag_sociale" class="block text-sm font-medium">Ragione Sociale</label>
                        <input type="text" name="rag_sociale" id="rag_sociale"
                            class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                        <span id="rag_sociale-error" class="text-red-500 text-xs hidden">Campo obbligatorio.</span>
                    </div>
                    <div>
                        <label for="iva" class="block text-sm font-medium">Partita IVA</label>
                        <input type="text" name="iva" id="iva"
                            class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                        <span id="iva-error" class="text-red-500 text-xs hidden">Campo obbligatorio.</span>
                    </div>
                    <div>
                        <label for="pec" class="block text-sm font-medium">PEC</label>
                        <input type="text" name="pec" id="pec"
                            class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                        <span id="pec-error" class="text-red-500 text-xs hidden">Campo obbligatorio.</span>
                    </div>
                </div>

                <!-- Row 4 -->
                <div class="grid grid-cols-2 2xl:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label for="sdi" class="block text-sm font-medium">SDI</label>
                        <input type="text" name="sdi" id="sdi"
                            class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                        <span id="sdi-error" class="text-red-500 text-xs hidden">Campo obbligatorio.</span>
                    </div>
                    <div>
                        <label for="legal_address" class="block text-sm font-medium">Indirizzo Sede Legale</label>
                        <input type="text" name="legal_address" id="legal_address"
                            class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                        <span id="legal_address-error" class="text-red-500 text-xs hidden">Campo obbligatorio.</span>
                    </div>
                    <div>
                        <label for="cap" class="block text-sm font-medium">Cap</label>
                        <input type="number" name="cap" id="cap"
                            class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                        <span id="cap-error" class="text-red-500 text-xs hidden">Campo obbligatorio.</span>
                    </div>
                </div>

                <!-- Row 5 -->
                <div class="grid grid-cols-2 2xl:grid-cols-3 gap-4 mb-4">
                    <div>
                        <label for="province" class="block text-sm font-medium">Provincia</label>
                        <input type="text" name="province" id="province"
                            class="mt-1 block w-full px-3 py-2 border border-[#F0F0F0] rounded-md focus:outline-none">
                        <span id="province-error" class="text-red-500 text-xs hidden">Campo obbligatorio.</span>
                    </div>
                </div>

                <!-- Buttons -->
            </div>

            <!-- Step 2 -->

            <div id="step-2" class="hidden">
                <span class="text-[15px] text-[#222222]">Seleziona quello che il cliente vedrà della sezione ‘dati
                    generali’:</span>
                <div class="mb-4 text-[#9F9F9F] text-[13px]">
                    <div class="mb-6 mt-6">
                        <input type="checkbox"
                            class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0 rounded-sm"
                            name="view_order_id" id="view order id">
                        <label for="view order id" class="ml-2 text-[#222222]">N. Riparazione</label>
                    </div>
                    <div class="mb-6">
                        <input type="checkbox"
                            class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0 rounded-sm"
                            name="view_start_date" id="view start date">
                        <label for="view start date" class="ml-2 text-[#222222]">Data inizio</label>
                    </div>
                    <div class="mb-6">
                        <input type="checkbox"
                            class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0 rounded-sm"
                            name="view_work_deadline" id="view work deadline">
                        <label for="view work deadline" class="ml-2 text-[#222222]">Scadenza lavori</label>
                    </div>
                    <div class="mb-6">
                        <input type="checkbox"
                            class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0 rounded-sm"
                            name="view_branch" id="view branch">
                        <label for="view branch" class="ml-2 text-[#222222]">Filiale</label>
                    </div>
                    <div class="mb-6">
                        <input type="checkbox"
                            class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0 rounded-sm"
                            name="view_mechanic" id="view mechanic">
                        <label for="view mechanic" class="ml-2 text-[#222222]">Tecnico</label>
                    </div>
                    <div class="mb-6">
                        <input type="checkbox"
                            class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0 rounded-sm"
                            name="view_mechanic_earn" id="view mechanic earn">
                        <label for="view mechanic earn" class="ml-2 text-[#222222]">Guadagno Tecnico</label>
                    </div>
                    <div class="mb-6">
                        <input type="checkbox"
                            class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0 rounded-sm"
                            name="view_car_data" id="view car data">
                        <label for="view car data" class="ml-2 text-[#222222]">Dati auto</label>
                    </div>
                    <div class="mb-6">
                        <input type="checkbox"
                            class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0 rounded-sm"
                            name="view_total_price" id="view total price">
                        <label for="view total price" class="ml-2 text-[#222222]">Importo totale</label>
                    </div>
                    <div class="mb-6">
                        <input type="checkbox"
                            class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0 rounded-sm"
                            name="view_iva" id="view iva">
                        <label for="view iva" class="ml-2 text-[#222222]">IVA</label>
                    </div>
                    <span class="text-[15px] text-[#222222]">Seleziona quello che il cliente vedrà della sezione ‘scheda
                        tecnica’:</span>
                    <div class="mb-6 mt-6">
                        <input type="checkbox"
                            class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0 rounded-sm"
                            name="view_technical_data" id="view technical data">
                        <label for="view technical data" class="ml-2 text-[#222222]">Dati scheda tecnica</label>
                    </div>
                    <div class="mb-6">
                        <input type="checkbox"
                            class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0 rounded-sm"
                            name="view_total_stamps" id="view total stamps">
                        <label for="view total stamps" class="ml-2 text-[#222222]">Totale bolli</label>
                    </div>
                    <div class="mb-6">
                        <input type="checkbox"
                            class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0 rounded-sm"
                            name="view_assembly" id="view assembly">
                        <label for="view assembly" class="ml-2 text-[#222222]">Montaggio/Smontaggio</label>
                    </div>
                    <div class="mb-6">
                        <input type="checkbox"
                            class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0 rounded-sm"
                            name="view_aluminum" id="view aluminum">
                        <label for="view aluminum" class="ml-2 text-[#222222]">Alluminio</label>
                    </div>
                    <div class="mb-6">
                        <input type="checkbox"
                            class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0 rounded-sm"
                            name="view_stamps_diameter" id="view stamps diameter">
                        <label for="view stamps diameter" class="ml-2 text-[#222222]">Diametro bolli</label>
                    </div>
                    <div class="mb-6">
                        <input type="checkbox"
                            class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0 rounded-sm"
                            name="view_car_size" id="view car size">
                        <label for="view car size" class="ml-2 text-[#222222]">Dimensione veicolo</label>
                    </div>
                    <div class="mb-6">
                        <input type="checkbox"
                            class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0 rounded-sm"
                            name="view_spare_parts" id="view spare parts">
                        <label for="view spare parts" class="ml-2 text-[#222222]">Ricambi</label>
                    </div>
                    <div class="mb-6">
                        <input type="checkbox"
                            class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0 rounded-sm"
                            name="view_notes" id="view notes">
                        <label for="view notes" class="ml-2 text-[#222222]">Note</label>
                    </div>
                    <span class="text-[15px] text-[#222222]">Seleziona quello che il cliente vedrà della sezione
                        ‘foto’:</span>
                    <div class="mb-6 mt-6">
                        <input type="checkbox"
                            class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0 rounded-sm"
                            name="view_photos" id="view photos">
                        <label for="view photos" class="ml-2 text-[#222222]">Foto</label>
                    </div>
                    <div class="mb-6">
                        <input type="checkbox"
                            class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0 rounded-sm"
                            name="view_deassembly_photos" id="view deassembly photos">
                        <label for="view deassembly photos" class="ml-2 text-[#222222]">Foto smontaggio</label>
                    </div>
                    <div class="mb-6">
                        <input type="checkbox"
                            class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B] focus:ring-0 rounded-sm"
                            name="view_photo_edits" id="view photo edits">
                        <label for="view photo edits" class="ml-2 text-[#222222]">Modifica della foto</label>
                    </div>
                    <div class="mb-6">
                        <input type="checkbox"
                            class="border border-[#D6D6D6] checked:bg-[#7FBC4B] text-[#7FBC4B]W focus:ring-0"
                            name="view_upload_photos" id="view upload photos">
                        <label for="view upload photos" class="ml-2 text-[#222222]">Carica nuove foto</label>
                    </div>

                </div>
            </div>


            <div class="text-end h-8 mt-10">
                <button type="button" id="next-step"
                    class="py-2 px-4 bg-[#1E1B58] text-white rounded-md text-sm">Conferma</button>
            </div>
        </form>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const nextStepButton = document.getElementById('next-step');
            const step1 = document.getElementById('step-1');
            const step2 = document.getElementById('step-2');
            const form = document.querySelector('form');
            let stepCounter = 1;

            nextStepButton.addEventListener('click', function(event) {
                let valid = true;

                if (stepCounter === 1) {
                    const requiredFieldsStep1 = ['name', 'surname', 'assigned_cars', 'city', 'admin_name',
                        'cellphone',
                        'email', 'rag_sociale', 'iva', 'pec', 'sdi', 'legal_address', 'cap', 'province'
                    ];

                    requiredFieldsStep1.forEach(field => {
                        const input = document.getElementById(field);
                        const errorSpan = document.getElementById(`${field}-error`);
                        if (!input.value.trim()) {
                            input.classList.add('border-red-500');
                            errorSpan.classList.remove('hidden');
                            valid = false;
                        } else {
                            input.classList.remove('border-red-500');
                            errorSpan.classList.add('hidden');
                        }
                    });

                    if (valid) {
                        document.getElementById('step-indicator-1').classList.replace('bg-[#1E1B58]',
                            'bg-[#E0E0E0]');
                        document.getElementById('step-indicator-1').classList.replace('text-white',
                            'text-[#9F9F9F]');
                        document.getElementById('step-indicator-2').classList.replace('bg-[#E0E0E0]',
                            'bg-[#1E1B58]');
                        document.getElementById('step-indicator-2').classList.replace('text-[#9F9F9F]',
                            'text-white');
                        document.getElementById('step-2-text').classList.replace('text-[#9F9F9F]',
                            'text-[#1E1B58]');
                        step1.classList.add('hidden');
                        step2.classList.remove('hidden');
                        stepCounter++;
                    }
                } else if (stepCounter === 2) {
                    if (valid) {
                        form.submit();
                    }
                }
            });
        });
    </script>
@endsection
