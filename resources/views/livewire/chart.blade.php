@push('title')
    <title>
        {{ $title }}
    </title>
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

<div class="relative flex flex-col">
    @if ($hide_controls)
        <h1 class="text-xl font-bold m-4 text-center">{{ $title }}</h1>
    @endif
    <div class="relative h-full w-full flex-1">
        @if ($hide_controls)
            <div class="w-full text-center p-1">
                Cutoffs for open (general) category, gender-neutral seats are the following.
                <a rel="nofollow" href="{{ $alternative_url }}" wire:loading.class="opacity-100"
                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-300 dark:hover:text-indigo-100">
                    Click here
                </a>
                to view the cutoffs for other categories.
            </div>
        @endif
        @if (!$hide_controls)
            <div class="p-4">
                {{ $this->form }}
            </div>
        @endif
    </div>
    <hr class="border-gray-200 dark:border-gray-600 my-2">
    <main id="chartContainer" class="block relative">
        <div class="lds-spinner overlay-centered z-50 opacity-0 transition-opacity duration-500"
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
        @if (!$hide_controls)
            <h2 class="text-xl font-bold m-4 text-center">{{ $title }}</h2>
        @endif
        <div wire:ignore id="legend"></div>
        <div wire:ignore id="myChart"></div>
    </main>
    @vite('resources/js/chart.js')

</div>
