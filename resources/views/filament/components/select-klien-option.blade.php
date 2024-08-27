<div class="flex items-center">
    @if ($klien->logo)
        <img src="{{ Storage::url($klien->logo->path) }}" alt="{{ $klien->nama }}" class="mr-2 w-6 h-6 rounded-full">
    @else
        <x-heroicon-o-building-office class="mr-2 w-6 h-6" />
    @endif
    <span class="px-2">{{ $klien->nama }}</span>
</div>
