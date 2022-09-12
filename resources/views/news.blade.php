@extends('layouts.app')

@section('meta')
    <meta name="description"
        content="News and updates about the JoSAA Analysis tool, that helps you decide your choices for JoSAA counselling based on 10 years of cut-off data">
    <meta name="keywords"
        content="josaa, josaa analysis, josaa cut-offs, josaa closing rank, josaa counselling, josaa counselling analysis, josaa counselling tool, engineering, engineering aspirants, iits, nits, iiits">

    <meta property="og:title" content="News &amp; Updates - JoSAA Analysis">
    <meta property="og:description"
        content="News and updates about the JoSAA Analysis tool, that helps you decide your choices for JoSAA counselling based on 10 years of cut-off data.">
    <meta property="og:url" content="{{ route('home') }}">
    <meta property="og:type" content="website">
    <meta property="twitter:card" content="summary">
    <meta property="twitter:title" content="News &amp; Updates - JoSAA Analysis">
    <meta property="twitter:url" content="{{ route('home') }}">
    <meta property="twitter:site" content="@@JoSAA_Analysis">
    <meta property="twitter:creator" content="@@CaptainIRS">
    <meta property="twitter:description"
        content="News and updates about the JoSAA Analysis tool, that helps you decide your choices for JoSAA counselling based on 10 years of cut-off data.">
    <meta property="twitter:image" content="{{ asset('favicon.png') }}">

    <title>News &amp; Updates | {{ config('app.name') }}</title>
@endsection

@section('content')
    <main class="container p-4">
        <h1 class="text-3xl font-bold m-4">News &amp; Updates</h1>

        <h2 class="text-lg m-4">
            News and updates about the JoSAA Analysis tool, that helps you decide your choices for JoSAA counselling based
            on 10 years of cut-off data.
        </h2>

        <!-- card ui -->
        <ol class="list-none bg-white dark:bg-gray-800 shadow-md overflow-hidden rounded-lg flex flex-col">
            <li class="m-4">
                <h3 class="text-lg font-bold">Using the JoSAA Analysis tool</h3>
                <p class="text-md">
                    How to use the JoSAA Analysis tool - a web application that helps you decide your choices for JoSAA
                    counselling based on 10 years of cut-off data.
                </p>
                <p class="text-md">
                    <a href="{{ route('news.using-the-josaa-analysis-tool') }}" class="text-blue-500">Read more...</a>
                </p>
            </li>
        </ol>
    @endsection
