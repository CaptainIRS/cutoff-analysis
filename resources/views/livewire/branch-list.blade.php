<div class="h-full w-full p-4">
    <div class="mt-10 mb-10">
        <div class="grid grid-cols-1 gap-4 xs:grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 x">
            @foreach ($branches as $branch)
                <div class="bg-white dark:bg-gray-800 shadow-md overflow-hidden rounded-lg flex flex-col">
                    <a href="{{ route('branch-details', ['branch' => $branch['slug']]) }}">
                        <div class="px-4 py-5 sm:px-6 text-xl flex-grow flex justify-between align-baseline">
                            <h3 class="leading-6 font-medium text-gray-900 dark:text-white">
                                {{ $branch['id'] }}
                            </h3>
                            <span class="ml-2 text-indigo-600 hover:text-indigo-900">&rarr;</span>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</div>
