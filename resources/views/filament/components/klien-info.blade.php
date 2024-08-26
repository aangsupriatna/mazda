<div class="fi-in-entry-wrp">
    <div class="grid gap-y-2">
        @if ($getRecord()->klien->nama)
            <div class="flex gap-x-3 justify-between items-center">
                <!--[if BLOCK]><![endif]-->
                <dt class="inline-flex gap-x-3 items-center fi-in-entry-wrp-label">
                    <span class="text-sm font-medium leading-6 text-gray-950 dark:text-white">
                        {{ __('proyek.klien') }}
                    </span>
                </dt>
                <!--[if ENDBLOCK]><![endif]-->
            </div>
        @endif
        <div class="flex items-center space-x-2">
            @if ($getRecord()->klien->logo)
            <img src="{{ Storage::url($getRecord()->klien->logo) }}" alt="{{ $getRecord()->klien->nama }}" class="object-contain w-10 h-10">
            @endif
            <span class="text-sm leading-6 text-gray-950 dark:text-white">{{ $getRecord()->klien->nama }}</span>
        </div>
    </div>
</div>
