@push('sub-title')
    @if ($title)
        {{ $title }} |
    @endif
@endpush
<div class="h-full w-full p-4">
    {{ $this->form }}
    <hr class="border-gray-200 dark:border-gray-600 my-4">
    <div class="relative table-wrapper">
        <div wire:loading.class="opacity-0"
            wire:target="previousPage, nextPage, gotoPage, tableRecordsPerPage, sortTable, getRankQuery, $set"
            x-transition.opacity>
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
        Livewire.on('titleUpdated', (title) => {
            if (title) {
                document.title = title + ' | Filter by Institute | JoSAA Analysis';
            } else {
                document.title = 'Filter by Institute | JoSAA Analysis';
            }
        });
    </script>
</div>
