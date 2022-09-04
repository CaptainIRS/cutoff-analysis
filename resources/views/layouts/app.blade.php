<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ 'darkMode': false, 'isOpen': false }" x-init="darkMode = JSON.parse(localStorage.getItem('darkMode'));
$watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)))"
    :style="{ colorScheme: darkMode && 'dark' }">

<head>
    <meta charset="utf-8">

    <meta name="application-name" content="{{ config('app.name') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description"
        content="JoSAA Analysis is a tool that helps you decide your choices for JoSAA counselling.">
    <meta name="keywords"
        content="josaa, josaa analysis, josaa counselling, josaa counselling analysis, josaa counselling tool, josaa counselling tool 2021, josaa counselling tool 2020, josaa counselling tool 2019">
    <meta name="author" content="CaptainIRS">
    <meta name="robots" content="index, follow">

    <meta property="og:title" content="JoSAA Analysis">
    <meta property="og:description"
        content="JoSAA Analysis is a tool that helps you decide your choices for JoSAA counselling.">

    <title>{{ config('app.name') }}</title>

    <style>
        html {
            font-size: 0.85em;
        }

        [x-cloak] {
            display: none !important;
        }

        .filament-tables-container {
            overflow: hidden;
        }

        .filament-tables-container> :nth-child(2) {
            display: block;
            max-height: 80vh;
            overflow-y: auto;
        }

        .dark .filament-tables-table>thead>tr {
            background-color: black !important;
        }

        table {
            display: inline-table;
            overflow: auto;
            width: 100%;
        }

        thead {
            position: sticky;
            top: 0;
            z-index: 1;
        }

        .filament-tables-tags-column {
            flex-wrap: nowrap !important;
        }

        .filament-tables-tags-column>span {
            white-space: nowrap;
        }

        .filament-tables-cell {
            padding: 0;
            margin: 0;
        }

        canvas {
            max-height: 80vh !important;
        }

        .lds-spinner {
            display: inline-block;
            position: fixed;
            bottom: 40px;
            right: 40px;
            width: 40px;
            height: 40px;
        }

        .lds-spinner div {
            transform-origin: 40px 40px;
            animation: lds-spinner 1.2s linear infinite;
        }

        .lds-spinner div:after {
            content: " ";
            display: block;
            position: absolute;
            top: 10px;
            left: 37px;
            width: 6px;
            height: 16px;
            border-radius: 20%;
            background: rgb(140, 140, 140);
        }

        .lds-spinner div:nth-child(1) {
            transform: rotate(0deg);
            animation-delay: -1.1s;
        }

        .lds-spinner div:nth-child(2) {
            transform: rotate(30deg);
            animation-delay: -1s;
        }

        .lds-spinner div:nth-child(3) {
            transform: rotate(60deg);
            animation-delay: -0.9s;
        }

        .lds-spinner div:nth-child(4) {
            transform: rotate(90deg);
            animation-delay: -0.8s;
        }

        .lds-spinner div:nth-child(5) {
            transform: rotate(120deg);
            animation-delay: -0.7s;
        }

        .lds-spinner div:nth-child(6) {
            transform: rotate(150deg);
            animation-delay: -0.6s;
        }

        .lds-spinner div:nth-child(7) {
            transform: rotate(180deg);
            animation-delay: -0.5s;
        }

        .lds-spinner div:nth-child(8) {
            transform: rotate(210deg);
            animation-delay: -0.4s;
        }

        .lds-spinner div:nth-child(9) {
            transform: rotate(240deg);
            animation-delay: -0.3s;
        }

        .lds-spinner div:nth-child(10) {
            transform: rotate(270deg);
            animation-delay: -0.2s;
        }

        .lds-spinner div:nth-child(11) {
            transform: rotate(300deg);
            animation-delay: -0.1s;
        }

        .lds-spinner div:nth-child(12) {
            transform: rotate(330deg);
            animation-delay: 0s;
        }

        @keyframes lds-spinner {
            0% {
                opacity: 1;
            }

            100% {
                opacity: 0;
            }
        }
    </style>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
    @livewireScripts
    @stack('scripts')
</head>

<body class="antialiased" :class="{ 'dark': darkMode === true }">
    <nav class="flex items-center justify-between flex-wrap p-2 mb-1 w-full z-10 top-0 bg-gray-200 dark:bg-gray-800"
        @keydown.escape="isOpen = false" :class="{ 'shadow-lg': isOpen }">

        <div class="flex items-center flex-shrink-0 text-white mr-6">
            <a class="text-black dark:text-white no-underline hover:text-gray hover:no-underline"
                href="{{ route('home') }}">
                <span class="text-xl pl-2"><i class="em em-grinning"></i> JoSAA Analysis</span>
            </a>
        </div>

        <button @click="isOpen = !isOpen" type="button"
            class="block 2xl:hidden px-2 text-gray-800 dark:text-gray-200 focus:outline-none"
            :class="{ 'transition transform-180': isOpen }">
            <svg class="h-6 w-6 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path x-show="isOpen" fill-rule="evenodd" clip-rule="evenodd"
                    d="M18.278 16.864a1 1 0 0 1-1.414 1.414l-4.829-4.828-4.828 4.828a1 1 0 0 1-1.414-1.414l4.828-4.829-4.828-4.828a1 1 0 0 1 1.414-1.414l4.829 4.828 4.828-4.828a1 1 0 1 1 1.414 1.414l-4.828 4.829 4.828 4.828z" />
                <path x-show="!isOpen" fill-rule="evenodd"
                    d="M4 5h16a1 1 0 0 1 0 2H4a1 1 0 1 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2zm0 6h16a1 1 0 0 1 0 2H4a1 1 0 0 1 0-2z" />
            </svg>
        </button>

        <div transition.opacity class="w-full flex-grow 2xl:flex 2xl:items-center 2xl:w-auto shadow-xs"
            :class="{ 'block': isOpen, 'hidden': !isOpen }" {{-- @click.away="isOpen = false" --}} x-show.transition="true">
            <ul class="pt-3 2xl:pt-0 list-reset 2xl:flex justify-end flex-1 items-center">
                <li class="mr-3">
                    <a class="inline-flex items-center gap-2 text-gray-600 dark:text-gray-200 no-underline hover:text-gray-500 hover:text-underline py-2 px-2"
                        href="{{ route('search-by-program') }}" @click="isOpen = false"><svg
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                        Search by program
                    </a>
                </li>
                <li class="mr-3">
                    <a class="inline-flex items-center gap-2 text-gray-600 dark:text-gray-200 no-underline hover:text-gray-500 hover:text-underline py-2 px-2"
                        href="{{ route('search-by-institute') }}" @click="isOpen = false"><svg
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                        Search by institute
                    </a>
                </li>
                <li class="mr-3">
                    <a class="inline-flex items-center gap-2 text-gray-600 dark:text-gray-200 no-underline hover:text-gray-500 hover:text-underline py-2 px-2"
                        href={{ route('round-trends') }} @click="isOpen = false"><svg xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                        </svg>
                        Round trends
                    </a>
                </li>
                <li class="mr-3">
                    <a class="inline-flex items-center gap-2 text-gray-600 dark:text-gray-200 no-underline hover:text-gray-500 hover:text-underline py-2 px-2"
                        href={{ route('program-trends') }} @click="isOpen = false"><svg
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                        </svg>
                        Program trends
                    </a>
                </li>
                <li class="mr-3">
                    <a class="inline-flex items-center gap-2 text-gray-600 dark:text-gray-200 no-underline hover:text-gray-500 hover:text-underline py-2 px-2"
                        href={{ route('institute-trends') }} @click="isOpen = false"><svg
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                        </svg>
                        Institute trends
                    </a>
                </li>
                <li class="mr-3">
                    <a class="inline-flex items-center gap-2 text-gray-600 dark:text-gray-200 no-underline hover:text-gray-500 hover:text-underline py-2 px-2"
                        href={{ route('field-trends') }} @click="isOpen = false"><svg
                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 18L9 11.25l4.306 4.307a11.95 11.95 0 015.814-5.519l2.74-1.22m0 0l-5.94-2.28m5.94 2.28l-2.28 5.941" />
                        </svg>
                        Field trends
                    </a>
                </li>
                <li class="mr-3">
                    <div class="flex justify-start align-baseline space-x-2 py-4 px-2">
                        <span class="text-sm text-gray-800 dark:text-gray-500">Light</span>
                        <label for="toggle"
                            class="flex items-center h-5 p-1 duration-300 ease-in-out bg-gray-300 rounded-full cursor-pointer w-9 dark:bg-gray-600">
                            <div
                                class="w-4 h-4 duration-300 ease-in-out transform bg-white rounded-full shadow-md toggle-dot dark:translate-x-3">
                            </div>
                        </label>
                        <span class="text-sm text-gray-400 dark:text-white">Dark</span>
                        <input id="toggle" type="checkbox" class="hidden" :value="darkMode"
                            @change="darkMode = !darkMode" />
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    <div x-cloak class="container m-auto">
        <div class="container flex-1">
            @yield('content')
        </div>
    </div>

    <div x-cloak class="footer text-center p-2">
        Made with <svg class="inline h-5 w-5 pb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"
            xmlns="http://www.w3.org/2000/svg">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z">
            </path>
        </svg> by &nbsp;<a href="https://captainirs.dev"
            class="text-blue-500 hover:text-blue-600 underline">@@CaptainIRS</a>
    </div>

    @livewire('notifications')
</body>

</html>
