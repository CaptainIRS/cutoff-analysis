@push('title')
    {{ $title }}
@endpush

@push('robots')
    @if ($hide_controls)
        <meta name="robots" content="index, follow">
    @else
        <meta name="robots" content="noindex">
    @endif

    @if (!$is_canonical)
        <link rel="canonical" href="{{ $canonical_url }}">
    @endif
@endpush

<div class="relative flex flex-col-reverse">
    <main id="chartContainer" class="block">
        <h2 class="text-xl font-bold m-4 text-center">{{ $title }}</h2>
        <div wire:ignore id="legend"></div>
        <div wire:ignore id="myChart"></div>
    </main>
    <hr class="border-gray-200 dark:border-gray-600 my-2">
    <div class="relative h-full w-full flex-1">
        @if ($hide_controls)
            <a rel="nofollow" class="absolute w-full h-full z-40 bg-gray-100/60 dark:bg-gray-800/60 cursor-pointer"
                href="{{ $alternative_url }}" wire:loading.class="opacity-100">
                <div class="flex flex-col items-center justify-center h-full">
                    <div class="text-gray-500 dark:text-gray-400 text-xl font-bold">
                        {{ __('Click to customise...') }}
                    </div>
                </div>
            </a>
        @endif
        <div class="p-4">
            {{ $this->form }}
        </div>
    </div>
    <div class="lds-spinner lds-spinner-bottom opacity-0 transition-opacity duration-500"
        wire:loading.class="opacity-100">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
        <div></div>
    </div>
    @vite('resources/js/chart.js')

</div>
