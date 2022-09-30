@extends('layouts.app')

@section('meta')
    <meta name="description"
        content="Find cut-offs in a range over 10 years, filtered by course in the JoSAA seat allocation process.">
    <meta name="keywords"
        content="josaa, josaa analysis, josaa cut-offs, josaa closing rank, josaa counselling, josaa counselling analysis, josaa counselling tool, engineering, engineering aspirants, closing rank trends, cutoff trends, search cutoffs, cutoffs data, cutoff range, cutoff predict, cutoff for course, indian colleges, josaa closing rank in round, iits, nits, iiits">

    <meta property="og:title" content="Filter by Program - JoSAA Analysis">
    <meta property="og:description"
        content="Find cut-offs in a range over 10 years, filtered by course in the JoSAA seat allocation process.">
    <meta property="og:url" content="{{ route('search-by-program') }}">
    <meta property="og:type" content="website">
    <meta property="twitter:card" content="summary">
    <meta property="twitter:title" content="Filter by Program - JoSAA Analysis">
    <meta property="twitter:url" content="{{ route('search-by-program') }}">
    <meta property="twitter:site" content="@@JoSAA_Analysis">
    <meta property="twitter:creator" content="@@CaptainIRS">
    <meta property="twitter:description"
        content="Find cut-offs in a range over 10 years, filtered by course in the JoSAA seat allocation process.">
    <meta property="twitter:image" content="{{ asset('favicon.png') }}">

    <title>Filter by Program | {{ config('app.name') }}</title>
@endsection

@section('content')
    <h1 class="text-3xl font-bold m-4 print:hidden">Filter by Program</h1>

    <h2 class="text-lg m-4 print:hidden">
        Filter by program allows you to filter the cut-off data with the selected programs and further narrow down with your
        choice of institutes.
    </h2>
    <livewire:search-by-program />
@endsection
