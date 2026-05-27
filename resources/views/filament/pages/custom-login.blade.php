<x-filament::page>
    <form wire:submit.prevent="login" class="space-y-4">
        <x-filament::input.wrapper>
            <x-filament::input.label for="email" value="Email" />
            <x-filament::input id="email" type="email" wire:model.defer="email" />
        </x-filament::input.wrapper>

        <x-filament::input.wrapper>
            <x-filament::input.label for="password" value="Password" />
            <x-filament::input id="password" type="password" wire:model.defer="password" />
        </x-filament::input.wrapper>

        <x-filament::button type="submit" color="primary">
            Login
        </x-filament::button>
    </form>
</x-filament::page>
