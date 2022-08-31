<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ 'darkMode': false, 'isOpen': false }" x-init="darkMode = JSON.parse(localStorage.getItem('darkMode'));
$watch('darkMode', value => localStorage.setItem('darkMode', JSON.stringify(value)))"
    :style="{ colorScheme: darkMode && 'dark' }">

<head>
    <meta charset="utf-8">

    <meta name="application-name" content="{{ config('app.name') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1">

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

        <div class="w-full flex-grow 2xl:flex 2xl:items-center 2xl:w-auto"
            :class="{ 'block shadow-3xl': isOpen, 'hidden': !isOpen }" {{-- @click.away="isOpen = false" --}} x-show.transition="true">
            <ul class="pt-3 2xl:pt-0 list-reset 2xl:flex justify-end flex-1 items-center">
                <li class="mr-3">
                    <a class="inline-block text-gray-600 dark:text-gray-200 no-underline hover:text-gray-500 hover:text-underline py-2 px-4"
                        href="{{ route('search-by-program') }}" @click="isOpen = false">Search by program
                    </a>
                </li>
                <li class="mr-3">
                    <a class="inline-block text-gray-600 dark:text-gray-200 no-underline hover:text-gray-500 hover:text-underline py-2 px-4"
                        href="{{ route('search-by-institute') }}" @click="isOpen = false">Search by institute
                    </a>
                </li>
                <li class="mr-3">
                    <a class="inline-block text-gray-600 dark:text-gray-200 no-underline hover:text-gray-500 hover:text-underline py-2 px-4"
                        href="#" @click="isOpen = false">Search institutes
                    </a>
                </li>
                <li class="mr-3">
                    <a class="inline-block text-gray-600 dark:text-gray-200 no-underline hover:text-gray-500 hover:text-underline py-2 px-4"
                        href="#" @click="isOpen = false">Search courses
                    </a>
                </li>
                <li class="mr-3">
                    <a class="inline-block text-gray-600 dark:text-gray-200 no-underline hover:text-gray-500 hover:text-underline py-2 px-4"
                        href="#" @click="isOpen = false">Rank trends
                    </a>
                </li>
                <li class="mr-3">
                    <a class="inline-block text-gray-600 dark:text-gray-200 no-underline hover:text-gray-500 hover:text-underline py-2 px-4"
                        href="#" @click="isOpen = false">Round trends
                    </a>
                </li>
                <li class="mr-3">
                    <a class="inline-block text-gray-600 dark:text-gray-200 no-underline hover:text-gray-500 hover:text-underline py-2 px-4"
                        href="#" @click="isOpen = false">Program trends
                    </a>
                </li>
                <li class="mr-3">
                    <a class="inline-block text-gray-600 dark:text-gray-200 no-underline hover:text-gray-500 hover:text-underline py-2 px-4"
                        href="#" @click="isOpen = false">Institute trends
                    </a>
                </li>
                <li class="mr-3">
                    <div class="flex justify-start align-baseline space-x-2 py-4 px-4">
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
    <div class="container m-auto">
        <div class="container flex-1">
            @yield('content')
        </div>
    </div>

    @livewire('notifications')
</body>

</html>
