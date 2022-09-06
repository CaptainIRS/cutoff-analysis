@extends('layouts.app')

@section('meta')
    <meta name="description" content="JoSAA Analysis is a tool that helps you decide your choices for JoSAA counselling.">
    <meta name="keywords"
        content="josaa, josaa analysis, josaa counselling, josaa counselling analysis, josaa counselling tool, engineering, engineering aspirants, iits, nits, iiits">

    <meta property="og:title" content="Filter by Institute - JoSAA Analysis">
    <meta property="og:description"
        content="JoSAA Analysis is a tool that helps you decide your choices for JoSAA counselling.">
    <meta property="og:url" content="{{ route('home') }}">

    <title>{{ config('app.name') }}</title>
@endsection

@section('content')
    <main class="mx-auto mt-5 max-w-7xl px-4 sm:mt-6 sm:px-6 md:mt-8 lg:mt-10 lg:px-8 xl:mt-14">
        <div class="sm:text-center lg:text-left">
            <h1 class="text-4xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-5xl md:text-6xl">
                <span class="block xl:inline">JoSAA Analysis</span>
            </h1>
            <p
                class="mt-3 text-base text-gray-500 dark:text-gray-300 sm:mx-auto sm:mt-5 sm:max-w-xl sm:text-lg md:mt-5 md:text-xl lg:mx-0">
                A tool that helps you find your optimal choices for JoSAA counselling.</p>
        </div>

        <!-- cards explaining features -->
        <div class="mt-10">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div class="bg-white dark:bg-gray-800 shadow overflow-hidden rounded-lg flex flex-col">
                    <div class="px-4 py-5 sm:px-6 flex-grow">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                            Filter by Program
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-300">
                            Filter by program allows you to filter the cut-off data with the selected programs and further
                            narrow down with your choice of institutes.
                        </p>
                    </div>
                    <div class="border-t border-gray-200 dark:border-gray-700">
                        <div class="px-4 py-4 sm:px-6">
                            <a href="{{ route('search-by-program') }}" class="text-indigo-600 hover:text-indigo-900">Get
                                Started &rarr;</a>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow overflow-hidden rounded-lg flex flex-col">
                    <div class="px-4 py-5 sm:px-6 flex-grow">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                            Filter by Institute
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-300">
                            Filter by institute allows you to filter the cut-off data with the selected institutes and
                            further narrow down with your choice of programs.
                        </p>
                    </div>
                    <div class="border-t border-gray-200 dark:border-gray-700">
                        <div class="px-4 py-4 sm:px-6">
                            <a href="{{ route('search-by-institute') }}" class="text-indigo-600 hover:text-indigo-900">Get
                                Started &rarr;</a>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow overflow-hidden rounded-lg flex flex-col">
                    <div class="px-4 py-5 sm:px-6 flex-grow">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                            Field Trends
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-300">
                            Field trends highlight the trends of courses in a particular field over the years. This helps
                            understand the popularity and perception of a field among engineering aspirants, and thus helps
                            understand the demand for a particular field during the counselling process.</p>
                    </div>
                    <div class="border-t border-gray-200 dark:border-gray-700">
                        <div class="px-4 py-4 sm:px-6">
                            <a href="{{ route('field-trends') }}" class="text-indigo-600 hover:text-indigo-900">Get Started
                                &rarr;</a>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow overflow-hidden rounded-lg flex flex-col">
                    <div class="px-4 py-5 sm:px-6 flex-grow">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                            Program Trends
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-300">
                            Program trends highlight the trends of various institutes offering a particular program over the
                            years. This helps understand the popularity and perception of institutes offering the program,
                            and thus helps understand the demand for a particular institute offering the program during
                            JoSAA counselling.
                        </p>
                    </div>
                    <div class="border-t border-gray-200 dark:border-gray-700">
                        <div class="px-4 py-4 sm:px-6">
                            <a href="{{ route('program-trends') }}" class="text-indigo-600 hover:text-indigo-900">Get
                                Started
                                &rarr;</a>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow overflow-hidden rounded-lg flex flex-col">
                    <div class="px-4 py-5 sm:px-6 flex-grow">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                            Institute Trends
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-300">
                            Institute trends highlight the trends of various programs offered by a particular institute over
                            the years. This helps understand the popularity and perception of programs offered by the
                            institute, and thus helps understand the demand for a particular program in the institute
                            during the counselling process.
                        </p>
                    </div>
                    <div class="border-t border-gray-200 dark:border-gray-700">
                        <div class="px-4 py-4 sm:px-6">
                            <a href="{{ route('institute-trends') }}" class="text-indigo-600 hover:text-indigo-900">Get
                                Started
                                &rarr;</a>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow overflow-hidden rounded-lg flex flex-col">
                    <div class="px-4 py-5 sm:px-6 flex-grow">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                            Round Trends
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-300">
                            Round trends highlight the general trend of closing ranks throughout the rounds of the
                            counselling process. This helps understand the likely range of changes to the closing ranks
                            throught the counselling process.
                        </p>
                    </div>
                    <div class="border-t border-gray-200 dark:border-gray-700">
                        <div class="px-4 py-4 sm:px-6">
                            <a href="{{ route('round-trends') }}" class="text-indigo-600 hover:text-indigo-900">Get Started
                                &rarr;</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
