<div class="flex items-center">
    <div class="flex justify-center items-center mr-2 w-4 h-4">
        @if ($klien->logo)
            <img src="{{ Storage::url($klien->logo->path) }}" alt="{{ $klien->nama }}" class="object-contain max-w-full max-h-full">
        @else
            <x-heroicon-o-building-office class="w-6 h-6" />
        @endif
    </div>
    <span class="px-2">{{ $klien->nama }}</span>
</div>