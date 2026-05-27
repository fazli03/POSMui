<div wire:poll.1s class="p-4 rounded-xl shadow bg-white dark:bg-gray-800 dark:text-blue-300 text-center">
    <div class="text-sm text-gray-500 dark:text-gray-400">
        {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
    </div>
    <div class="text-3xl font-mono font-bold text-gray-900 dark:text-white">
        {{ now()->format('H:i:s') }}
    </div>
</div>
