<div>
    <button id="goBackButton" class="text-[#808080] text-[13px] my-2 flex place-items-center"><img
            src="{{ asset('images/Icon ionic-ios-arrow-back.svg') }}" alt="back-icon" class="mr-1.5">Indietro</button>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('goBackButton').addEventListener('click', function() {
            window.history.back();
        });
    });
</script>
