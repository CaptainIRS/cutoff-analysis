@push('sub-title')
    @if ($title)
        {{ $title }} |
    @endif
@endpush
<div class="h-full w-full p-4">
    {{ $this->form }}
    <hr class="border-gray-200 dark:border-gray-600 my-4">
    <div class="relative table-wrapper">
        <div wire:loading.class="overlay-visible"
            wire:target="previousPage, nextPage, gotoPage, tableRecordsPerPage, sortTable, getRankQuery, $set"
            class="table-overlay dark:bg-black">
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
        {{ $this->table }}
    </div>
</div>
