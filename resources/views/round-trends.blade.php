@extends('layouts.app')

@push('title', 'Round Trends |')

@section('meta')
    <meta name="description"
        content="Compare the cut-offs of a course in various rounds over 10 years in the JoSAA seat allocation process.">
    <meta name="keywords"
        content="josaa, josaa analysis, josaa cut-offs, josaa closing rank, josaa counselling, josaa counselling analysis, josaa counselling tool, engineering, engineering aspirants, round trends, indian colleges, josaa closing rank in round, iits, nits, iiits">

    <meta property="og:title" content="Round Trends - JoSAA Analysis">
    <meta property="og:description"
        content="Compare the cut-offs of a course in various rounds over 10 years in the JoSAA seat allocation process.">
    <meta property="og:url" content="{{ route('round-trends') }}">
    <meta property="og:type" content="website">
    <meta property="twitter:card" content="summary">
    <meta property="twitter:title" content="Round Trends - JoSAA Analysis">
    <meta property="twitter:url" content="{{ route('round-trends') }}">
    <meta property="twitter:site" content="@@JoSAA_Analysis">
    <meta property="twitter:creator" content="@@CaptainIRS">
    <meta property="twitter:description"
        content="Compare the cut-offs of a course in various rounds over 10 years in the JoSAA seat allocation process.">
    <meta property="twitter:image" content="{{ asset('favicon.png') }}">
@endsection

@section('content')
    <h1 class="text-3xl font-bold m-4 print:hidden">Round Trends</h1>

    <p class="text-lg m-4 print:hidden">
        Compare the cut-offs of a course in various rounds over 10 years in the JoSAA seat allocation process.
        This helps understand the likely range of changes to the closing ranks throught the counselling process.
    </p>
    <livewire:round-trends />
@endsection
