@push('title')
    @if ($title)
        {{ $title }}
    @endif
@endpush
<div class="h-full w-full p-4">
    {{ $this->form }}
    <hr class="border-gray-200 dark:border-gray-600 my-4">
    <div class="relative table-wrapper">
        <div wire:loading.class="opacity-0 invisible"
            wire:target="previousPage, nextPage, gotoPage, tableRecordsPerPage, sortTable, getRankQuery, $set">
            {{ $this->table }}
        </div>
        <div class="opacity-0 table-overlay" wire:loading.class="opacity-100">
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
    </div>
    <script>
        const script = document.createElement('script');
        script.setAttribute('type', 'application/ld+json');
        script.setAttribute('id', 'json-ld');
        script.textContent = JSON.stringify({
            "@context": "https://schema.org",
            "@type": "Table",
            "name": "{{ $title }}" ||
                "View Institute-wise Cut-off Ranks in JoSAA Counselling | JoSAA Analysis",
            "cssSelector": ".filament-tables-table",
        });
        document.head.appendChild(script);
        Livewire.on('titleUpdated', (title) => {
            if (title) {
                document.title = title + ' | JoSAA Analysis';
            } else {
                document.title = 'View Institute-wise Cut-off Ranks in JoSAA Counselling | JoSAA Analysis';
            }
            document.getElementById('json-ld').textContent = JSON.stringify({
                "@context": "https://schema.org",
                "@type": "Table",
                "name": document.title ||
                    "View Institute-wise Cut-off Ranks in JoSAA Counselling | JoSAA Analysis",
                "cssSelector": ".filament-tables-table",
            });
        });
    </script>
</div>
