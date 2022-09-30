<!doctype html>
<html ⚡>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <link rel="preload" as="script" href="https://cdn.ampproject.org/v0.js">
    <link rel="icon" href="{{ asset('favicon.svg') }}" sizes="any" type="image/svg+xml">
    <script async src="https://cdn.ampproject.org/v0.js"></script>
    <script async custom-element="amp-analytics" src="https://cdn.ampproject.org/v0/amp-analytics-0.1.js"></script>
    <script async custom-element="amp-sidebar" src="https://cdn.ampproject.org/v0/amp-sidebar-1.0.js"></script>
    <style amp-custom>
        html {
            font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
        }

        .sample-sidebar li,
        nav[toolbar] li {
            margin: 1rem;
            list-style: none;
        }

        amp-sidebar ul,
        nav[toolbar] ul {
            display: block;
            padding-left: 0;
            margin-top: 0;
            padding-top: 2rem;
        }

        nav[toolbar] {
            position: sticky;
            top: 3rem;
        }

        header {
            background-color: rgb(229 231 235 / 1);
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 3em;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .hamburger {
            width: 3rem;
            display: flex;
            justify-content: center;
            text-align: center;
            flex-direction: column;
            cursor: pointer;
            font-size: 2em;
        }

        .title {
            padding: 1rem;
            margin: 0;
            font-size: 1.5em;
        }

        li>a {
            text-decoration: none;
            color: black;
        }

        a:hover {
            text-decoration: underline;
        }

        .desktop-sidebar {
            background-color: rgb(229 231 235 / 1);
            min-width: 20ch;
        }

        .sidebar-close {
            margin: 1rem;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 50 50' width='50' height='50' overflow='visible' stroke='black' stroke-width='2' %3E%3Cline x1='0' y1='0' x2='50' y2='50' /%3E%3Cline x1='50' y1='0' x2='0' y2='50' /%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-size: contain;
            cursor: pointer;
            width: 1rem;
            height: 1rem;
        }

        article {
            flex-grow: 1;
            padding: 2rem;
        }

        @media (min-width: 784px) {
            .hamburger {
                display: none;
            }

            main {
                display: flex;
                flex-direction: row;
            }

            aside {
                width: 300px;
            }
        }

        @media (prefers-color-scheme: dark) {
            :root {
                color-scheme: dark;
            }

            html {
                background-color: black;
                color: white;
            }

            .desktop-sidebar,
            header {
                background-color: rgb(31 41 55 / 1);
            }

            li>a,
            a {
                color: white;
            }

            .hamburger {
                color: white;
            }

            .sidebar-close {
                background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 50 50' width='50' height='50' overflow='visible' stroke='white' stroke-width='2' %3E%3Cline x1='0' y1='0' x2='50' y2='50' /%3E%3Cline x1='50' y1='0' x2='0' y2='50' /%3E%3C/svg%3E");
            }
        }

        amp-img {
            max-width: 700px;
        }
    </style>
    <style amp-boilerplate>
        body {
            -webkit-animation: -amp-start 8s steps(1, end) 0s 1 normal both;
            -moz-animation: -amp-start 8s steps(1, end) 0s 1 normal both;
            -ms-animation: -amp-start 8s steps(1, end) 0s 1 normal both;
            animation: -amp-start 8s steps(1, end) 0s 1 normal both
        }

        @-webkit-keyframes -amp-start {
            from {
                visibility: hidden
            }

            to {
                visibility: visible
            }
        }

        @-moz-keyframes -amp-start {
            from {
                visibility: hidden
            }

            to {
                visibility: visible
            }
        }

        @-ms-keyframes -amp-start {
            from {
                visibility: hidden
            }

            to {
                visibility: visible
            }
        }

        @-o-keyframes -amp-start {
            from {
                visibility: hidden
            }

            to {
                visibility: visible
            }
        }

        @keyframes -amp-start {
            from {
                visibility: hidden
            }

            to {
                visibility: visible
            }
        }
    </style>
    <noscript>
        <style amp-boilerplate>
            body {
                -webkit-animation: none;
                -moz-animation: none;
                -ms-animation: none;
                animation: none
            }
        </style>
    </noscript>

    @yield('meta')
</head>

<body>
    <header>
        <h1 class="title">JoSAA Analysis</h1>
        <div role="button" tabindex="1" class="hamburger" on="tap:sidebar-desktop.toggle"
            aria-label="Click to open sidebar">
            ≡
        </div>
    </header>
    <amp-sidebar id="sidebar-desktop" class="desktop-sidebar" layout="nodisplay" side="left">
        <div class="sidebar-close" role="button" tabindex="2" on="tap:sidebar-desktop.close"></div>
        <nav toolbar="(min-width: 784px)" toolbar-target="target-element-desktop">
            <ul>
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ route('news') }}">News</a></li>
                <li><a href="{{ route('search-by-institute') }}">Filter by Institute</a></li>
                <li><a href="{{ route('search-by-branch') }}">Filter by Branch</a></li>
                <li><a href="{{ route('branch-trends') }}">Branch Trends</a></li>
                <li><a href="{{ route('institute-trends') }}">Institute Trends</a></li>
                <li><a href="{{ route('round-trends') }}">Round Trends</a></li>
            </ul>
        </nav>
    </amp-sidebar>
    <main>
        <aside id="target-element-desktop" class="desktop-sidebar">
        </aside>
        @yield('content')
    </main>
    <amp-analytics type="gtag" data-credentials="include">
        <script type="application/json">
            {
              "vars" : {
                "gtag_id": "{{ config('app.ga_ua') }}",
                "config" : {
                  "{{ config('app.ga_ua') }}": {
                    "groups": "default"
                  }
                }
              }
            }
        </script>
    </amp-analytics>
</body>

</html>
