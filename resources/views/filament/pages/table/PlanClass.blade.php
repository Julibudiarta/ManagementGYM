<div class="flex items-center justify-between w-full border-b border-gray-300 pb-2 mb-2">
    <span class="text-xl font-bold text-gray-800">
        {{ $getRecord()->name }}
    </span>

    <div class="relative">
        <x-filament::button color="gray" size="sm" icon="heroicon-o-ellipsis-vertical" 
            class="text-gray-500 hover:text-gray-600 focus:outline-none relative"
            onclick="togglePopup({{ $getRecord()->id }})">
        </x-filament::button>

        <!-- Popup Menu -->
        <div id="popup-{{ $getRecord()->id }}" 
            class="absolute right-0 mt-2 w-48 bg-white border border-gray-300 rounded shadow-lg z-10 hidden">
            <ul class="py-2 text-gray-700">
                <li>
                    <a href="{{ route('filament.admin.resources.plan-classes.view', $getRecord()->id) }}" 
                       class="block px-4 py-2 hover:bg-gray-100">View</a>
                </li>
                <li>
                    <a href="{{ route('filament.admin.resources.plan-classes.edit', $getRecord()->id) }}" 
                       class="block px-4 py-2 hover:bg-gray-100">Edit</a>
                </li>
                <li>
                    <form action="{{ route('plan-classes.destroy', $getRecord()->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="block px-4 py-2 hover:bg-gray-100">Hapus</button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>

<script>
    function togglePopup(id) {
        const popup = document.getElementById(`popup-${id}`);
        const allPopups = document.querySelectorAll('[id^="popup-"]');

        // Sembunyikan semua popup dulu
        allPopups.forEach(p => {
            if (p.id !== `popup-${id}`) p.classList.add('hidden');
        });

        // Toggle popup yang diklik
        popup.classList.toggle('hidden');
    }

    // Tutup popup jika klik di luar
    document.addEventListener('click', function(event) {
        const isButton = event.target.closest('button');
        const isPopup = event.target.closest('[id^="popup-"]');
        if (!isButton && !isPopup) {
            document.querySelectorAll('[id^="popup-"]').forEach(p => p.classList.add('hidden'));
        }
    });
</script>
