@extends('layouts.app')

@section('meta')
    <meta name="description"
        content="Compare the cut-offs of various courses offered by an institute over 10 years in the JoSAA seat allocation process.">
    <meta name="keywords"
        content="josaa, josaa analysis, josaa cut-offs, josaa closing rank, josaa counselling, josaa counselling analysis, josaa counselling tool, engineering, engineering aspirants, institute trends, indian colleges, indian institutes, institute popularity, iits, nits, iiits">

    <meta property="og:title" content="Institute Trends - JoSAA Analysis">
    <meta property="og:description"
        content="Compare the cut-offs of various courses offered by an institute over 10 years in the JoSAA seat allocation process.">
    <meta property="og:url" content="{{ route('institute-trends') }}">

    <title>Institute Trends | {{ config('app.name') }}</title>
@endsection

@section('content')
    <h1 class="text-3xl font-bold m-4">Institute Trends</h1>

    <h2 class="text-lg m-4">
        Institute trends highlight the trends of various programs offered by a particular institute over the years. This
        helps understand the popularity and perception of programs offered by the institute, and thus helps understand the
        demand for a particular program in the institute during the counselling process.
    </h2>

    <livewire:institute-trends />
@endsection
