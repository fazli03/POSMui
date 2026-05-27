<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div class="p-4 bg-white shadow rounded-lg">
        <h3 class="text-sm text-gray-500">Menu Tersedia</h3>
        <p class="text-xl font-bold text-green-600">{{ $tersedia }}</p>
    </div>

    <div class="p-4 bg-white shadow rounded-lg">
        <h3 class="text-sm text-gray-500">Menu Tidak Tersedia</h3>
        <p class="text-xl font-bold text-red-600">{{ $tidakTersedia }}</p>
    </div>
</div>
    