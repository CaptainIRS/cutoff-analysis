@extends('layouts.app')

@push('title', 'Home | JoSAA Analysis')

@section('meta')
    <meta name="description"
        content="JoSAA Analysis is a tool that helps you decide your choices for JoSAA counselling based on 10 years of cut-off data.">
    <meta name="keywords"
        content="josaa, josaa analysis, josaa cut-offs, josaa closing rank, josaa counselling, josaa counselling analysis, josaa counselling tool, engineering, engineering aspirants, iits, nits, iiits">

    <meta property="og:title" content="JoSAA Analysis">
    <meta property="og:description"
        content="JoSAA Analysis is a tool that helps you decide your choices for JoSAA counselling based on 10 years of cut-off data.">
    <meta property="og:url" content="{{ route('home') }}">
    <meta property="og:type" content="website">
    <meta property="twitter:card" content="summary">
    <meta property="twitter:title" content="Home - JoSAA Analysis">
    <meta property="twitter:url" content="{{ route('home') }}">
    <meta property="twitter:site" content="@@JoSAA_Analysis">
    <meta property="twitter:creator" content="@@CaptainIRS">
    <meta property="twitter:description"
        content="JoSAA Analysis is a tool that helps you decide your choices for JoSAA counselling based on 10 years of cut-off data.">
    <meta property="twitter:image" content="{{ asset('favicon.png') }}">
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

        <div class="mt-10 mb-10">
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                <div class="bg-white dark:bg-gray-800 shadow-md overflow-hidden rounded-lg flex flex-col">
                    <div class="px-4 py-5 sm:px-6 flex-grow">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                            View All Branches
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-300">
                            View all the branches and courses belonging to the branches available for JoSAA counselling.
                        </p>
                    </div>
                    <div class="border-t border-gray-200 dark:border-gray-700">
                        <div class="px-4 py-4 sm:px-6">
                            <a href="{{ route('branch-list') }}" class="text-indigo-600 hover:text-indigo-900">Get
                                Started &rarr;</a>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-md overflow-hidden rounded-lg flex flex-col">
                    <div class="px-4 py-5 sm:px-6 flex-grow">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                            View All Institutes
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-300">
                            View all the institutes participating in JoSAA counselling and the courses offered by them.
                        </p>
                    </div>
                    <div class="border-t border-gray-200 dark:border-gray-700">
                        <div class="px-4 py-4 sm:px-6">
                            <a href="{{ route('institute-list') }}" class="text-indigo-600 hover:text-indigo-900">Get
                                Started &rarr;</a>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-md overflow-hidden rounded-lg flex flex-col">
                    <div class="px-4 py-5 sm:px-6 flex-grow">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                            View Branch-wise Cut-offs
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-300">
                            Filter by branch allows you to filter the cut-off data with the selected branches and further
                            narrow down with your choice of institutes.
                        </p>
                    </div>
                    <div class="border-t border-gray-200 dark:border-gray-700">
                        <div class="px-4 py-4 sm:px-6">
                            <a href="{{ route('search-by-branch') }}" class="text-indigo-600 hover:text-indigo-900">Get
                                Started &rarr;</a>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-md overflow-hidden rounded-lg flex flex-col">
                    <div class="px-4 py-5 sm:px-6 flex-grow">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                            View Institute-wise Cut-offs
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

                <div class="bg-white dark:bg-gray-800 shadow-md overflow-hidden rounded-lg flex flex-col">
                    <div class="px-4 py-5 sm:px-6 flex-grow">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                            Analyse Branch-wise Cut-off Trends
                        </h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500 dark:text-gray-300">
                            Branch trends highlight the trends of courses in a particular branch over the years. This helps
                            understand the popularity and perception of a branch among engineering aspirants, and thus helps
                            understand the demand for a particular branch during the counselling process.</p>
                    </div>
                    <div class="border-t border-gray-200 dark:border-gray-700">
                        <div class="px-4 py-4 sm:px-6">
                            <a href="{{ route('branch-trends') }}" class="text-indigo-600 hover:text-indigo-900">Get Started
                                &rarr;</a>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 shadow-md overflow-hidden rounded-lg flex flex-col">
                    <div class="px-4 py-5 sm:px-6 flex-grow">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                            Analyse Institute-wise Cut-off Trends
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

                <div class="bg-white dark:bg-gray-800 shadow-md overflow-hidden rounded-lg flex flex-col">
                    <div class="px-4 py-5 sm:px-6 flex-grow">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">
                            Analyse Round-wise Cut-off Trends
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

        <div class="sm:text-center lg:text-left my-10">
            <h2 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-3xl md:text-4xl">
                <span class="block xl:inline">About</span>
            </h2>
            <p
                class="mt-3 text-base text-gray-500 dark:text-gray-300 sm:mx-auto sm:mt-5 sm:max-w-3xl sm:text-lg md:mt-5 md:text-xl lg:mx-0">
                The site is written in PHP using the <a href="https://laravel.com/"
                    class="text-indigo-600 hover:text-indigo-900">Laravel</a> framework, and utilizes the <a
                    href="https://filamentphp.com" class="text-indigo-600 hover:text-indigo-900">FilamentPHP</a> library.
                The data is obtained from the JoSAA archives. The site is open source and can be found on <a
                    href="https://github.com/CaptainIRS/josaa-analysis"
                    class="text-indigo-600 hover:text-indigo-900">GitHub</a>.
            </p>
        </div>
    </main>
@endsection
