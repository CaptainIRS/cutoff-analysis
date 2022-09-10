<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ 'darkMode': false, 'isOpen': window.innerWidth >= 1280 }" x-init="darkMode = JSON.parse(localStorage.getItem('darkMode'));
$watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)))" class="h-full"
    :style="{ colorScheme: darkMode && 'dark' }">

<head>
    <meta charset="utf-8">

    <meta name="application-name" content="{{ config('app.name') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="author" content="CaptainIRS">
    <meta name="robots" content="index, follow">
    <meta property="og:image" content="{{ asset('favicon.svg') }}">

    @yield('meta')

    <link rel="icon" href="{{ asset('favicon.svg') }}" sizes="any" type="image/svg+xml">

    <link rel="preconnect" href="https://cdnjs.cloudflare.com" crossorigin>

    <style>
        html {
            font-size: 0.85em;
            --overlay-bg: white;
            --color-scheme: light;
            color-scheme: var(--color-scheme);
            scroll-behavior: smooth;
        }
    </style>
    <script>
        if (localStorage.getItem('darkMode') === 'true') {
            console.log('Dark mode enabled');
            document.documentElement.style.setProperty('--overlay-bg', 'black');
            document.documentElement.style.setProperty('--color-scheme', 'dark');
        } else {
            console.log('Dark mode disabled');
            document.documentElement.style.setProperty('--overlay-bg', 'white');
            document.documentElement.style.setProperty('--color-scheme', 'light');
        }
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @livewireScripts
    @stack('scripts')
</head>

<script async src="https://www.googletagmanager.com/gtag/js?id={{ config('app.ga4_tag') }}"></script>
<script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
        dataLayer.push(arguments);
    }
    gtag('js', new Date());

    gtag('config', "{{ config('app.ga4_tag') }}");
</script>

<body class="antialiased flex flex-col h-full" :class="{ 'dark': darkMode === true }">
    <div x-cloak class="overlay">
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
    <nav class="fixed flex items-center justify-between flex-wrap w-full z-10 top-0 bg-gray-200 dark:bg-gray-800 shadow-md"
        @click.away="
        if (window.innerWidth < 1280) {
            isOpen = false;
        }
        "
        @keydown.escape="isOpen = false">

        <div class="flex items-center flex-shrink-0 text-white mr-6">
            <a class="inline-flex items-center gap-2 text-gray-600 dark:text-gray-200 no-underline hover:text-gray-500 hover:text-underline py-3 px-2 w-full"
                href="{{ route('home') }}">
                <span class="text-xl pl-2 inline-flex items-start"><img src="{{ asset('favicon.svg') }}"
                        class="h-6 w-6 mr-2" alt="Logo"> {{ config('app.name') }}</span>
            </a>
        </div>

        <button @click="isOpen = !isOpen" type="button"
            class="block xl:hidden px-2 text-gray-800 dark:text-gray-200 focus:outline-none"
            :class="{ 'transition transform-180': isOpen }">
            <svg class="h-6 w-6 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path x-show="isOpen" fill-rule="evenodd" clip-rule="evenodd"
                    d="M18.278 16.864a1 1 0 0 1-1.414 1.414l-4.829-4.828-4.828 4.828a1 1 0 0 1-1.414-1.414l4.828-4.829-4.828-4.828a1 1 0 0 1 1.414-1.414l4.829 4.828 4.828-4.828a1 1 0 1 1 1.414 1.414l-4.828 4.829 4.828 4.828z" />
                <path x-show="!isOpen" fill-rule="evenodd"
                    d="M4 5h16a1 1 0 0 1 0 2H4a1 1 0 1 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2z" />
            </svg>
        </button>

        <div class="pl-2 w-full flex-grow xl:flex xl:items-center xl:w-auto shadow-xs"
            @resize.window="
                if (window.innerWidth >= 1280) {
                    isOpen = true;
                } else {
                    isOpen = false;
                }
            "
            x-show="isOpen" x-transition>
            <ul class="list-reset xl:flex justify-end flex-1 items-center">
                <li class="mr-3">
                    <a class="inline-flex items-center gap-2 text-gray-600 dark:text-gray-200 no-underline hover:text-gray-500 hover:text-underline py-3 px-2 w-full text-lg"
                        href="{{ route('search-by-program') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" />
                        </svg>

                        Filter by Program
                    </a>
                </li>
                <li class="mr-3">
                    <a class="inline-flex items-center gap-2 text-gray-600 dark:text-gray-200 no-underline hover:text-gray-500 hover:text-underline py-3 px-2 w-full text-lg"
                        href="{{ route('search-by-institute') }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 3c2.755 0 5.455.232 8.083.678.533.09.917.556.917 1.096v1.044a2.25 2.25 0 01-.659 1.591l-5.432 5.432a2.25 2.25 0 00-.659 1.591v2.927a2.25 2.25 0 01-1.244 2.013L9.75 21v-6.568a2.25 2.25 0 00-.659-1.591L3.659 7.409A2.25 2.25 0 013 5.818V4.774c0-.54.384-1.006.917-1.096A48.32 48.32 0 0112 3z" />
                        </svg>

                        Filter by Institute
                    </a>
                </li>
                <li class="mr-3">
                    <a class="inline-flex items-center gap-2 text-gray-600 dark:text-gray-200 no-underline hover:text-gray-500 hover:text-underline py-3 px-2 w-full text-lg"
                        href={{ route('branch-trends') }}><svg xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                        </svg>
                        Branch Trends
                    </a>
                </li>
                <li class="mr-3">
                    <a class="inline-flex items-center gap-2 text-gray-600 dark:text-gray-200 no-underline hover:text-gray-500 hover:text-underline py-3 px-2 w-full text-lg"
                        href={{ route('program-trends') }}><svg xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                        </svg>
                        Program Trends
                    </a>
                </li>
                <li class="mr-3">
                    <a class="inline-flex items-center gap-2 text-gray-600 dark:text-gray-200 no-underline hover:text-gray-500 hover:text-underline py-3 px-2 w-full text-lg"
                        href={{ route('institute-trends') }}><svg xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                        </svg>
                        Institute Trends
                    </a>
                </li>
                <li class="mr-3">
                    <a class="inline-flex items-center gap-2 text-gray-600 dark:text-gray-200 no-underline hover:text-gray-500 hover:text-underline py-3 px-2 w-full text-lg"
                        href={{ route('round-trends') }}><svg xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                        </svg>
                        Round Trends
                    </a>
                </li>
                <li class="mr-3">
                    <div class="flex justify-start items-center space-x-2 py-4 px-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor"
                            class="w-5 h-5 text-gray-800 dark:text-gray-500">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                        </svg>

                        <label for="toggle"
                            class="flex items-center h-5 p-1 duration-300 ease-in-out bg-gray-300 rounded-full cursor-pointer w-9 dark:bg-gray-600">
                            <div
                                class="w-4 h-4 duration-300 ease-in-out transform bg-white rounded-full shadow-md toggle-dot dark:translate-x-3">
                            </div>
                        </label>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-gray-400 dark:text-white">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
                        </svg>

                        <input id="toggle" type="checkbox" class="hidden" :value="darkMode"
                            @change="darkMode = !darkMode" />
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    <div class="container m-auto flex-grow mt-16">
        <div class="container flex-1">
            @yield('content')
        </div>
    </div>

    <div class="footer text-center p-2">
        <hr class="border-gray-200 dark:border-gray-600 my-2">
        Made with <svg class="inline h-5 w-5 pb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
            </path>
        </svg> by <a href="https://github.com/CaptainIRS"
            class="text-blue-500 hover:text-blue-600 underline">@@CaptainIRS</a>
    </div>

    <div x-data="{ scrollBackTop: false }" x-cloak>
        <button x-show="scrollBackTop"
            x-on:scroll.window="scrollBackTop = (window.pageYOffset > window.outerHeight * 0.1) ? true : false"
            @click="window.scrollTo({top: 0, behavior: 'smooth'})" aria-label="Back to top"
            class="fixed bottom-0 left-1/2 -translate-x-1/2 p-2 mb-12 bg-gray-200 dark:bg-gray-800 hover:bg-gray-300 dark:hover:bg-gray-700 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 15.75l7.5-7.5 7.5 7.5" />
            </svg>
        </button>
    </div>
</body>

</html>
