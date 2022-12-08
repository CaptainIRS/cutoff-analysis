@extends('layouts.app')

@section('meta')
    <meta name="description"
        content="Compare the cut-offs of various courses offered by an institute over 10 years in the JoSAA seat allocation process.">
    <meta name="keywords"
        content="josaa, josaa analysis, josaa cut-offs, josaa closing rank, josaa counselling, josaa counselling analysis, josaa counselling tool, engineering, engineering aspirants, institute trends, indian colleges, indian institutes, institute popularity, iits, nits, iiits">

    <meta property="og:title" content="Analyse Institute-wise Cut-off Trends - JoSAA Analysis">
    <meta property="og:description"
        content="Compare the cut-offs of various courses offered by an institute over 10 years in the JoSAA seat allocation process.">
    <meta property="og:url" content="{{ route('institute-trends') }}">
    <meta property="og:type" content="website">
    <meta property="twitter:card" content="summary">
    <meta property="twitter:title" content="Analyse Institute-wise Cut-off Trends - JoSAA Analysis">
    <meta property="twitter:url" content="{{ route('institute-trends') }}">
    <meta property="twitter:site" content="@@JoSAA_Analysis">
    <meta property="twitter:creator" content="@@CaptainIRS">
    <meta property="twitter:description"
        content="Compare the cut-offs of various courses offered by an institute over 10 years in the JoSAA seat allocation process.">
    <meta property="twitter:image" content="{{ asset('favicon.png') }}">
@endsection

@section('content')
    <h1 class="text-3xl font-bold m-4 print:hidden">Analyse Institute-wise Cut-off Trends</h1>

    <p class="text-lg m-4 print:hidden">
        Compare the cut-offs of various courses offered by an institute over 10 years in the JoSAA seat allocation process.
        <br>
        This helps understand the popularity and perception of programs offered by the institute, and thus helps understand
        the demand for a particular program in the institute during the counselling process.
    </p>

    <livewire:institute-trends />
@endsection
