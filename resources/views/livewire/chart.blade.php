@push('sub-title')
    @if ($title)
        {{ $title }} |
    @endif
@endpush
<div class="relative flex flex-col-reverse">
    <div id="chartContainer" class="block">
        <h2 class="text-xl font-bold m-4 text-center">{{ $title }}</h2>
        <div wire:ignore id="legend"></div>
        <div wire:ignore id="myChart"></div>
    </div>
    <hr class="border-gray-200 dark:border-gray-600 my-2">
    <div class="h-full w-full p-4 flex-1">
        {{ $this->form }}
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
