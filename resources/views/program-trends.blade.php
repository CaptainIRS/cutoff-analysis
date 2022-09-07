@extends('layouts.app')

@section('meta')
    <meta name="description"
        content="Compare the cut-offs of various institues offering a particular course over 10 years in the JoSAA seat allocation process.">
    <meta name="keywords"
        content="josaa, josaa analysis, josaa cut-offs, josaa closing rank, josaa counselling, josaa counselling analysis, josaa counselling tool, engineering, engineering aspirants, program trends, indian colleges, indian institutes, course popularity, iits, nits, iiits">

    <meta property="og:title" content="Program Trends - JoSAA Analysis">
    <meta property="og:description"
        content="Compare the cut-offs of various institues offering a particular course over 10 years in the JoSAA seat allocation process.">
    <meta property="og:url" content="{{ route('program-trends') }}">

    <title>Program Trends | {{ config('app.name') }}</title>
@endsection

@section('content')
    <h1 class="text-3xl font-bold m-4">Program Trends</h1>

    <h2 class="text-lg m-4">
        Program trends highlight the trends of various institutes offering a particular program over the years. This helps
        understand the popularity and perception of institutes offering the program, and thus helps understand the
        demand for a particular institute offering the program during the counselling process.
    </h2>
    <livewire:program-trends />
@endsection
