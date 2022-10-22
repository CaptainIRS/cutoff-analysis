<div class="h-full w-full p-4">
    <div class="mt-10 mb-10">
        <div class="grid grid-cols-1 gap-4 xs:grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 x">
            @foreach ($branches as $branch)
                <div class="bg-white dark:bg-gray-800 shadow-md overflow-hidden rounded-lg flex flex-col">
                    <div class="px-4 py-5 sm:px-6 flex-grow flex flex-col">
                        <h3 class="text-xl leading-6 font-medium text-gray-900 dark:text-white">
                            {{ $branch['id'] }}
                        </h3>
                    </div>
                    <div class="border-t border-gray-200 dark:border-gray-700">
                        <div class="px-4 py-4 sm:px-6">
                            <a href="{{ route('branch-details', ['branch' => $branch['id']]) }}"
                                class="text-indigo-600 hover:text-indigo-900">View
                                Detail &rarr;</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
