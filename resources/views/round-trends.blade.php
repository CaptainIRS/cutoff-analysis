@extends('layouts.app')

@section('meta')
    <meta name="description"
        content="JoSAA Analysis is a tool that helps you decide your choices for JoSAA counselling. Round trends highlight the general trend of closing ranks throughout the rounds of the counselling process. This helps understand the likely range of changes to the closing ranks throught the counselling process.">
    <meta name="keywords"
        content="josaa, josaa analysis, josaa counselling, josaa counselling analysis, josaa counselling tool, engineering, engineering aspirants, round trends, indian colleges, josaa closing rank in round, iits, nits, iiits">

    <meta property="og:title" content="Round Trends - JoSAA Analysis">
    <meta property="og:description"
        content="JoSAA Analysis is a tool that helps you decide your choices for JoSAA counselling. Round trends highlight the general trend of closing ranks throughout the rounds of the counselling process. This helps understand the likely range of changes to the closing ranks throught the counselling process.">
    <meta property="og:url" content="{{ route('round-trends') }}">

    <title>Round Trends | {{ config('app.name') }}</title>
@endsection

@section('content')
    <div class="text-3xl font-bold m-4">Round Trends</div>

    <div class="text-lg m-4">
        Round trends highlight the general trend of closing ranks throughout the rounds of the counselling process. This
        helps understand the likely range of changes to the closing ranks throught the counselling process.
    </div>
    <livewire:round-trends />
@endsection
