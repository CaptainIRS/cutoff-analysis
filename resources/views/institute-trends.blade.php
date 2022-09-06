@extends('layouts.app')

@section('meta')
    <meta name="description"
        content="JoSAA Analysis is a tool that helps you decide your choices for JoSAA counselling. Institute trends highlight the trends of various programs offered by a particular institute over the years. This helps understand the popularity and perception of programs offered by the institute, and thus helps understand the competition for a particular program in the institute during JoSAA counselling.">
    <meta name="keywords"
        content="josaa, josaa analysis, josaa counselling, josaa counselling analysis, josaa counselling tool, engineering, engineering aspirants, institute trends, indian colleges, indian institutes, institute popularity, iits, nits, iiits">

    <meta property="og:title" content="Institute Trends - JoSAA Analysis">
    <meta property="og:description"
        content="JoSAA Analysis is a tool that helps you decide your choices for JoSAA counselling. Institute trends highlight the trends of various programs offered by a particular institute over the years. This helps understand the popularity and perception of programs offered by the institute, and thus helps understand the competition for a particular program in the institute during JoSAA counselling.">
    <meta property="og:url" content="{{ route('institute-trends') }}">

    <title>Institute Trends | {{ config('app.name') }}</title>
@endsection

@section('content')
    <div class="text-3xl font-bold m-4">Institute Trends</div>

    <div class="text-lg m-4">
        Institute trends highlight the trends of various programs offered by a particular institute over the years. This
        helps understand the popularity and perception of programs offered by the institute, and thus helps understand the
        competition for a particular program in the institute during JoSAA counselling.
    </div>

    <livewire:institute-trends />
@endsection
