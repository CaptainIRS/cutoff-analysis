@push('sub-title')
    {{ $branch['id'] }} |
@endpush
<div>
    <h2 class="text-2xl font-bold ml-4 mt-10 mb-10 print:hidden">Compare cut-offs of institutes offering programs in
        {{ $branch['id'] }} branch</h2>

    <div class="mx-4 my-10">
        {{ $this->form }}
    </div>
    <hr class="border-gray-200 dark:border-gray-600 my-2">
    <div class="m-4">
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">
            @foreach ($courses as $course)
                <div class="bg-white dark:bg-gray-800 shadow-md overflow-hidden rounded-lg flex flex-col">
                    <div class="px-2 py-3 sm:px-4 flex-grow flex flex-col justify-between">
                        <div class="flex flex-col justify-between flex-grow">
                            <h3 class="text-xl leading-6 font-medium text-gray-900 dark:text-white">
                                {!! $course->institute_alias !!}
                            </h3>
                            <h4 class="text-lg leading-6 font-light text-gray-900 dark:text-white flex-grow">
                                {!! $course->course_alias !!}, {{ $course->program_id }}
                            </h4>
                            <div class="mt-4">
                                @foreach (explode(',', $course->years) as $year)
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-100">
                                        {{ $year }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                        <div class="flex justify-between flex-wrap mt-4 gap-2">
                            <a href="{{ route('search-by-branch', ['rank' => $course->institute_type === 'iit' ? 'jee-advanced' : 'jee-main', 'institutes' => [$course->institute_id], 'courses' => [$course->course_id], 'branches' => [$branch['id']]]) }}"
                                target="_blank"
                                class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-300 dark:hover:text-indigo-100 flex gap-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 01-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-7.5A1.125 1.125 0 0112 18.375m9.75-12.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125m19.5 0v1.5c0 .621-.504 1.125-1.125 1.125M2.25 5.625v1.5c0 .621.504 1.125 1.125 1.125m0 0h17.25m-17.25 0h7.5c.621 0 1.125.504 1.125 1.125M3.375 8.25c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m17.25-3.75h-7.5c-.621 0-1.125.504-1.125 1.125m8.625-1.125c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125M12 10.875v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 10.875c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125M13.125 12h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125M20.625 12c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5M12 14.625v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 14.625c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125m0 1.5v-1.5m0 0c0-.621.504-1.125 1.125-1.125m0 0h7.5" />
                                </svg>
                                View Cut-off Ranks
                            </a>
                            <a href="{{ route('round-trends', ['rank' => $course->institute_type === 'iit' ? 'jee-advanced' : 'jee-main', 'institute' => $course->institute_id, 'course' => $course->course_id, 'program' => $course->program_id]) }}"
                                target="_blank"
                                class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-300 dark:hover:text-indigo-100 flex gap-2"><svg
                                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                                </svg>
                                Analyze Cut-off Trends
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <h2 class="text-2xl font-bold ml-4 mt-10 mb-10 print:hidden">Compare the cut-offs of all institutes offering
        programs in {{ $branch['id'] }} branch from 2012-2022</h2>

    <div class="m-4">
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-5">
            @foreach (['last' => 'Last Round', 'all' => 'All Rounds', '1' => 'Round 1', '2' => 'Round 2', '3' => 'Round 3', '4' => 'Round 4', '5' => 'Round 5', '6' => 'Round 6'] as $key => $value)
                <div class="bg-white dark:bg-gray-800 shadow-md overflow-hidden rounded-lg flex flex-col">
                    <div class="px-2 py-3 sm:px-4 flex-grow flex flex-row justify-between">
                        <h3 class="text-xl leading-6 font-medium text-gray-900 dark:text-white">
                            {{ $value }}
                        </h3>
                        <div class="flex justify-between">
                            <div class="px-2">
                                <a href="{{ route('search-by-branch', ['branches' => [$branch['id']], 'round-display' => $key !== 'last' ? $key : null]) }}"
                                    target="_blank"
                                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-300 dark:hover:text-indigo-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 01-1.125-1.125M3.375 19.5h7.5c.621 0 1.125-.504 1.125-1.125m-9.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-7.5A1.125 1.125 0 0112 18.375m9.75-12.75c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125m19.5 0v1.5c0 .621-.504 1.125-1.125 1.125M2.25 5.625v1.5c0 .621.504 1.125 1.125 1.125m0 0h17.25m-17.25 0h7.5c.621 0 1.125.504 1.125 1.125M3.375 8.25c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m17.25-3.75h-7.5c-.621 0-1.125.504-1.125 1.125m8.625-1.125c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125M12 10.875v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 10.875c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125M13.125 12h7.5m-7.5 0c-.621 0-1.125.504-1.125 1.125M20.625 12c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h7.5M12 14.625v-1.5m0 1.5c0 .621-.504 1.125-1.125 1.125M12 14.625c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125m0 1.5v-1.5m0 0c0-.621.504-1.125 1.125-1.125m0 0h7.5" />
                                    </svg>
                                </a>
                            </div>
                            <div class="px-2">
                                <a href="{{ route('branch-trends', ['branches' => [$branch['id']], 'round-display' => $key !== 'last' ? $key : null]) }}"
                                    target="_blank"
                                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-300 dark:hover:text-indigo-100"><svg
                                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

</div>
