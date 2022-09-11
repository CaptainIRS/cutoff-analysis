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

        .lds-spinner {
            display: inline-block;
            position: fixed;
            bottom: 40px;
            right: 40px;
            width: 40px;
            height: 40px;
        }

        .lds-spinner.overlay-centered {
            top: 50%;
            left: 50%;
            transform: translate(-40px, -40px);
            position: absolute;
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

        [x-cloak].overlay {
            opacity: 100 !important;
        }

        .nav,
        .content {
            visibility: visible;
            transition: visibility 0s;
            transition-delay: 1s;
        }

        [x-cloak].nav,
        [x-cloak].content {
            visibility: hidden;
        }

        .body {
            overflow: hidden;
        }

        .overlay {
            z-index: 2000;
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            opacity: 0;
            background-color: var(--overlay-bg);
            pointer-events: none;
            transition: opacity 0.5s ease;
            transition-delay: 1s;
        }
    </style>
    <script>
        if (localStorage.getItem('darkMode') === 'true') {
            document.documentElement.style.setProperty('--overlay-bg', 'black');
            document.documentElement.style.setProperty('--color-scheme', 'dark');
        } else {
            document.documentElement.style.setProperty('--overlay-bg', 'white');
            document.documentElement.style.setProperty('--color-scheme', 'light');
        }
    </script>

    @livewireStyles
    @livewireScripts

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

<body class="body antialiased flex flex-col h-full" :class="{ 'dark': darkMode === true }">
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
    <nav x-cloak
        class="nav fixed flex items-center justify-between flex-wrap w-full z-10 top-0 bg-gray-200 dark:bg-gray-800 shadow-md h-14"
        @click.away="
        if (window.innerWidth < 1280) {
            isOpen = false;
        }
        "
        :class="{ 'xl:h-14 h-auto': isOpen }" @keydown.escape="isOpen = false">

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
    <div style="min-height: 3.5rem"></div>
    <div x-cloak class="content overflow-y-auto">
        <div class="container m-auto flex-grow">
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
            <br>
            Share via<span id="#share" class="pl-1"></span>
            <script>
                const content = 'Check out the JoSAA Analysis tool: ' + document.querySelector('meta[name="description"]')
                    .getAttribute('content');
                document.getElementById('#share').innerHTML = '<a href="https://www.facebook.com/sharer/sharer.php?u=' +
                    encodeURIComponent(document.URL) +
                    '"target="_blank"title="Facebook"style="display:inline-block;vertical-align:middle;padding:0 0.2em;height:2em;"><svg style="display:inline;fill:#8c8c8c;height:1em;" viewBox="0 -256 864 1664"><path transform="matrix(1,0,0,-1,-95,1280)" d="M 959,1524 V 1260 H 802 q -86,0 -116,-36 -30,-36 -30,-108 V 927 H 949 L 910,631 H 656 V -128 H 350 V 631 H 95 v 296 h 255 v 218 q 0,186 104,288.5 104,102.5 277,102.5 147,0 228,-12 z" /></svg></a> <a href="https://twitter.com/share?url=' +
                    encodeURIComponent(document.URL) + '&text=' + encodeURIComponent(content) +
                    '"target="_blank"title="Twitter"style="display:inline-block;vertical-align:middle;padding:0 0.2em;height:1.8em;"><svg style="display:inline;fill:#8c8c8c;height:0.8em;" viewBox="0 -256 1576 1280"><path transform="matrix(1,0,0,-1,-44,1024)" d="m 1620,1128 q -67,-98 -162,-167 1,-14 1,-42 0,-130 -38,-259.5 Q 1383,530 1305.5,411 1228,292 1121,200.5 1014,109 863,54.5 712,0 540,0 269,0 44,145 q 35,-4 78,-4 225,0 401,138 -105,2 -188,64.5 -83,62.5 -114,159.5 33,-5 61,-5 43,0 85,11 Q 255,532 181.5,620.5 108,709 108,826 v 4 q 68,-38 146,-41 -66,44 -105,115 -39,71 -39,154 0,88 44,163 Q 275,1072 448.5,982.5 622,893 820,883 q -8,38 -8,74 0,134 94.5,228.5 94.5,94.5 228.5,94.5 140,0 236,-102 109,21 205,78 -37,-115 -142,-178 93,10 186,50 z" /></svg></a> <a href="https://www.reddit.com/submit?url=' +
                    encodeURIComponent(document.URL) + '&title=' + encodeURIComponent(content) +
                    '"target="_blank"title="Reddit"style="display:inline-block;vertical-align:middle;padding:0 0.2em;height:2em;"><svg style="display:inline;fill:#8c8c8c;height:1em;" viewBox="0 -256 1792 1692"><path transform="matrix(1,0,0,-1,0,1280)" d="m 1792,690 q 0,-58 -29,-105.5 -30,-47.5 -80,-72.5 12,-46 12,-96 0,-155 -106,-287 Q 1482,-3 1298,-79.5 1114,-156 898,-156 682,-156 498.5,-79.5 315,-3 208.5,129 102,261 102,416 q 0,47 11,94 Q 62,535 31,583.5 0,632 0,690 q 0,82 58,140.5 58,58.5 141,58.5 85,0 145,-63 218,152 515,162 l 116,521 q 3,13 15,21 12,8 26,5 l 369,-81 q 18,37 54,60 36,22 79,22 62,0 106,-43 44,-44 44,-106 0,-62 -44,-106 -44,-44 -106,-44 -62,0 -105,44 -44,43 -44,105 l -334,74 -104,-472 q 300,-9 519,-160 58,61 143,61 83,0 141,-58.5 58,-58.5 58,-140.5 z M 418,491 q 0,-62 43.5,-106 43.5,-44 105.5,-44 62,0 106,44 44,44 44,106 0,62 -44,105.5 Q 629,640 567,640 506,640 462,596 418,552 418,491 z m 810,-355 q 11,11 11,26 0,15 -11,26 -10,10 -25,10 -15,0 -26,-10 -41,-42 -121,-62 -80,-20 -160,-20 -80,0 -160,20 -80,20 -121,62 -11,10 -26,10 -15,0 -25,-10 Q 553,178 553,162.5 553,147 564,136 607,93 682.5,68 758,43 805,38.5 852,34 896,34 q 44,0 91,4.5 47,4.5 123,29.5 75,25 118,68 z m -3,205 q 62,0 106,44 43,44 43,106 0,61 -44,105 -44,44 -105,44 -62,0 -106,-43.5 -44,-43.5 -44,-105.5 0,-62 44,-106 44,-44 106,-44 z" /></svg></a> <a href="whatsapp://send?text=' +
                    encodeURIComponent(`${content}: ${document.URL}`) +
                    '"title="WhatsApp"style="display:inline-block;vertical-align:middle;padding:0 0.2em;height:1.9em;"><svg style="display:inline;fill:#8c8c8c;height:0.9em;" viewBox="0 -256 1536 1548"><path transform="matrix(1,0,0,-1,0,1158)" d="m 985,562 q 13,0 98,-44 84,-44 89,-53 2,-5 2,-15 0,-33 -17,-76 -16,-39 -71,-65.5 -55,-26.5 -102,-26.5 -57,0 -190,62 -98,45 -170,118 -72,73 -148,185 -72,107 -71,194 v 8 q 3,91 74,158 24,22 52,22 6,0 18,-1 12,-2 19,-2 19,0 26.5,-6 7.5,-7 15.5,-28 8,-20 33,-88 25,-68 25,-75 0,-21 -34.5,-57.5 Q 599,735 599,725 q 0,-7 5,-15 34,-73 102,-137 56,-53 151,-101 12,-7 22,-7 15,0 54,48.5 39,48.5 52,48.5 z M 782,32 q 127,0 244,50 116,50 200,134 84,84 134,200.5 50,116.5 50,243.5 0,127 -50,243.5 -50,116.5 -134,200.5 -84,84 -200,134 -117,50 -244,50 -127,0 -243.5,-50 Q 422,1188 338,1104 254,1020 204,903.5 154,787 154,660 154,457 274,292 L 195,59 437,136 Q 595,32 782,32 z m 0,1382 q 153,0 293,-60 139,-60 240,-161 101,-101 161,-240.5 Q 1536,813 1536,660 1536,507 1476,367.5 1416,228 1315,127 1214,26 1075,-34 935,-94 782,-94 587,-94 417,0 L 0,-134 136,271 Q 28,449 28,660 q 0,153 60,292.5 60,139.5 161,240.5 101,101 240.5,161 139.5,60 292.5,60 z" /></svg></a> ' +
                    (/mobile|android|blackberry/i.test(navigator.userAgent) ? '<a href="https://telegram.me/share/url?url=' +
                        encodeURIComponent(document.URL) + '&text=' + encodeURIComponent(content) +
                        '"target="_blank"title="Telegram"style="display:inline-block;vertical-align:middle;width:2em;height:2em;"><svg style="display:inline;fill:#8c8c8c;height:0.6em;" viewBox="0 -256 1150 817.4"><path d="m 824.4,511.7 147,-693 c 6,-29.3 3,-50.3 -10,-63 -13,-12.7 -31,-15 -52,-7 L 45.45,81.65 c -19.3,7.3 -32.5,15.7 -39.504,25.05 -7,9.3 -7.8,18.2 -2.5,26.5 5.3,8.3 16.004,14.8 32.004,19.5 l 220.95,69 513,-323 c 14,-9.3 25,-11.3 32,-6 5,3.3 3,8.25 -4,14.95 l -415,375.05 0,0 0,0 -16,228 c 15.3,0 30.3,-7 45,-22 l 108,-104 224,165 c 43,24 70,11 81,-38 z" /></svg></a> ' :
                        '') + '<a href="https://www.linkedin.com/shareArticle?mini=true&url=' + encodeURIComponent(document.URL) +
                    '&title=' + encodeURIComponent(content) +
                    '"target="_blank"title="LinkedIn"style="display:inline-block;vertical-align:middle;padding:0 0.2em;height:2em;"><svg style="display:inline;fill:#8c8c8c;height:1em;" viewBox="0 -256 1536 1468"><path transform="matrix(1,0,0,-1,0,1132)" d="M 349,911 V -80 H 19 v 991 h 330 z m 21,306 q 1,-73 -50.5,-122 Q 268,1046 184,1046 h -2 q -82,0 -132,49 -50,49 -50,122 0,74 51.5,123 51.5,48 134.5,48 83,0 133,-48 50,-49 51,-123 z M 1536,488 V -80 h -329 v 530 q 0,105 -40,164.5 Q 1126,674 1040,674 977,674 934.5,639.5 892,605 871,554 860,524 860,473 V -80 H 531 q 2,399 2,647 0,248 -1,296 l -1,48 H 860 V 767 h -2 q 20,32 41,56 21,24 56.5,52 35.5,28 87.5,43.5 51,15.5 114,15.5 171,0 275,-113.5 Q 1536,707 1536,488 z" /></svg></a>';
            </script>
        </div>
    </div>
</body>

@vite(['resources/css/app.css', 'resources/js/app.js'])
@stack('scripts')

</html>
