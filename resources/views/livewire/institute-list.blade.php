<div class="h-full w-full p-4">
    {{ $this->form }}
    <hr class="border-gray-200 dark:border-gray-600 my-4">
    <div class="mt-10 mb-10">
        <div class="grid grid-cols-1 gap-4 xs:grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 x">
            @foreach ($institutes as $institute)
                <div
                    class="bg-white dark:bg-gray-800 shadow-md overflow-hidden rounded-lg hover:shadow-lg hover:bg-gray-50 hover:dark:bg-gray-700">
                    <a href="{{ route('institute-details', ['institute' => $institute['id']]) }}"
                        class="px-4 py-5 sm:px-6 h-full flex-grow flex flex-col">
                        <div class="flex-grow">
                            <h3
                                class="text-xl leading-6 font-medium py-2 text-indigo-600 hover:text-indigo-900 dark:text-indigo-300 dark:hover:text-indigo-100">
                                {!! $institute['alias'] !!}
                            </h3>
                            @if ($institute['alias'] !== $institute['id'])
                                <p class="max-w-2xl text-lg py-2 text-gray-500 dark:text-gray-300">
                                    {{ $institute['name'] }}
                                </p>
                            @endif
                        </div>
                        <p class="max-w-2xl text-sm flex-shrink py-2 text-gray-500 dark:text-gray-300 bottom-0">
                            {{ $institute['state'] }}
                        </p>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
