@php
    $selected = $getState();
@endphp

<div class="rounded-xl p-4 shadow-md space-y-2" style="background-color: #167000;">
    <!-- Label -->
    <label class="text-sm font-semibold text-gray-800">
        Nomor Meja
    </label>

    <!-- Grid Tombol -->
    <div class="grid grid-cols-7 gap-2 pt-1">
        @foreach (range(1, 20) as $number)
            <button wire:click="$set('{{ $getStatePath() }}', '{{ $number }}')" type="button"
                style="{{ $selected == $number
                    ? 'background-color: #4CAF50; color: white; border-color: #388E3C;'
                    : 'background-color: #ffffff; color: #333; border-color: #ccc;' }}"
                class="w-full px-4 py-2 rounded-md border text-sm font-semibold transition-all duration-200 hover:bg-green-100">
                {{ $number }}
            </button>
        @endforeach
    </div>
</div>
