@extends('layouts.app')

@section('meta')
    <meta name="description"
        content="JoSAA Analysis is a tool that helps you decide your choices for JoSAA counselling. Field trends highlight the trends of courses in a particular field over the years. This helps understand the popularity and perception of a field among engineering aspirants, and thus helps understand the competition for a particular field during JoSAA counselling.">
    <meta name="keywords"
        content="josaa, josaa analysis, josaa counselling, josaa counselling analysis, josaa counselling tool, engineering, engineering aspirants, field trends, indian institutes, course popularity, iits, nits, iiits">

    <meta property="og:title" content="Field Trends - JoSAA Analysis">
    <meta property="og:description"
        content="JoSAA Analysis is a tool that helps you decide your choices for JoSAA counselling. Field trends highlight the trends of courses in a particular field over the years. This helps understand the popularity and perception of a field among engineering aspirants, and thus helps understand the competition for a particular field during JoSAA counselling.">
    <meta property="og:url" content="{{ route('field-trends') }}">

    <title>Field Trends | {{ config('app.name') }}</title>
@endsection

@section('content')
    <div class="text-3xl font-bold m-4">Field Trends</div>

    <div class="text-lg m-4">
        Field trends highlight the trends of courses in a particular field over the years. This helps understand the
        popularity and perception of a field among engineering aspirants, and thus helps understand the competition for a
        particular field during JoSAA counselling.
    </div>
    <livewire:field-trends />
@endsection
