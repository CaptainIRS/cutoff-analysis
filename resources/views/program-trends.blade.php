@extends('layouts.app')

@section('meta')
    <meta name="description"
        content="JoSAA Analysis is a tool that helps you decide your choices for JoSAA counselling. Program trends highlight the trends of various institutes offering a particular program over the years. This helps understand the popularity and perception of institutes offering the program, and thus helps understand the competition for a particular institute offering the program during JoSAA counselling.">
    <meta name="keywords"
        content="josaa, josaa analysis, josaa counselling, josaa counselling analysis, josaa counselling tool, engineering, engineering aspirants, program trends, indian colleges, indian institutes, course popularity, iits, nits, iiits">

    <meta property="og:title" content="Program Trends - JoSAA Analysis">
    <meta property="og:description"
        content="JoSAA Analysis is a tool that helps you decide your choices for JoSAA counselling. Program trends highlight the trends of various institutes offering a particular program over the years. This helps understand the popularity and perception of institutes offering the program, and thus helps understand the competition for a particular institute offering the program during JoSAA counselling.">
    <meta property="og:url" content="{{ route('program-trends') }}">

    <title>Program Trends | {{ config('app.name') }}</title>
@endsection

@section('content')
    <div class="text-3xl font-bold m-4">Program Trends</div>

    <div class="text-lg m-4">
        Program trends highlight the trends of various institutes offering a particular program over the years. This helps
        understand the popularity and perception of institutes offering the program, and thus helps understand the
        competition for a particular institute offering the program during JoSAA counselling.
    </div>
    <livewire:program-trends />
@endsection
