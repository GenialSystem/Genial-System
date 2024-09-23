<div class="bg-[#FEF0F5] w-6 p-1 flex items-center justify-center group hover:bg-[#DC0814] duration-200 rounded-sm">
    <button
        wire:click="$dispatch('openModal', { component: 'delete-button-modal', arguments: { modelId: {{ $modelId }}, modelClass: '{{ addslashes($modelClass) }}', modelName: '{{ $modelName }}', customRedirect: '{{ $customRedirect }}' }})">
        <svg id="Raggruppa_3305" data-name="Raggruppa 3305" xmlns="http://www.w3.org/2000/svg" width="13.996" height="15.733"
            viewBox="0 0 13.996 15.733">
            <path class="group-hover:fill-white" id="Tracciato_731" data-name="Tracciato 731"
                d="M37.38,21.567H33.988v-.99A1.389,1.389,0,0,0,32.6,19.19H29.236a1.389,1.389,0,0,0-1.387,1.387v.99H24.457a.537.537,0,1,0,0,1.073h1.012V31.88a3.047,3.047,0,0,0,3.043,3.043h4.814a3.047,3.047,0,0,0,3.043-3.043V22.641H37.38a.537.537,0,1,0,0-1.073ZM35.295,31.88a1.971,1.971,0,0,1-1.97,1.97H28.511a1.971,1.971,0,0,1-1.97-1.97v-9.2h8.753Zm-6.373-11.3a.314.314,0,0,1,.314-.314H32.6a.314.314,0,0,1,.314.314v.99H28.922Z"
                transform="translate(-23.92 -19.19)" fill="#dc0851" />
            <path class="group-hover:fill-white" id="Tracciato_732" data-name="Tracciato 732"
                d="M42.589,48.265a.591.591,0,0,0,.589-.589v-5.9a.589.589,0,0,0-1.179,0v5.9A.591.591,0,0,0,42.589,48.265Z"
                transform="translate(-37.188 -35.688)" fill="#dc0851" />
            <path class="group-hover:fill-white" id="Tracciato_733" data-name="Tracciato 733"
                d="M54.589,48.265a.591.591,0,0,0,.589-.589v-5.9a.589.589,0,0,0-1.179,0v5.9A.591.591,0,0,0,54.589,48.265Z"
                transform="translate(-45.994 -35.688)" fill="#dc0851" />
        </svg>
    </button>
</div>
