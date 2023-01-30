@push('title')
    @if ($title)
        {{ $title }}
    @endif
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

<div class="h-full w-full">
    @if ($hide_controls)
        <h1 class="text-xl font-bold m-4 text-center">{{ $title }}</h2>
    @endif
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
    <hr class="border-gray-200 dark:border-gray-600 my-4">
    <main class="relative table-wrapper h-full px-4">
        <div wire:loading.class="opacity-0 invisible"
            wire:target="previousPage, nextPage, gotoPage, tableRecordsPerPage, sortTable, getRankQuery, $set">
            {{ $this->table }}
        </div>
        <div wire:loading.class.remove="opacity-0" class="opacity-0 table-overlay">
            <div class="lds-spinner overlay-centered">
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
        </div>
    </main>
    <script>
        const script = document.createElement('script');
        script.setAttribute('type', 'application/ld+json');
        script.setAttribute('id', 'json-ld');
        script.textContent = JSON.stringify({
            "@context": "https://schema.org",
            "@type": "Table",
            "name": "{{ $title }}" ||
                "View Institute-wise Cut-off Ranks in JoSAA Counselling | {{ config('app.name') }}",
            "cssSelector": ".filament-tables-table",
        });
        document.head.appendChild(script);
    </script>
</div>
