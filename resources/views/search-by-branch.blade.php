@extends('layouts.app')

@push('title', 'View Branch-wise Cut-offs of IITs, NITs, IIITs, GFTIs')

@section('meta')
    <meta name="description"
        content="Find cut-offs in a range over 10 years, filtered by course in the JoSAA seat allocation process.">
    <meta name="keywords"
        content="josaa, josaa analysis, josaa cut-offs, josaa closing rank, josaa counselling, josaa counselling analysis, josaa counselling tool, engineering, engineering aspirants, closing rank trends, cutoff trends, search cutoffs, cutoffs data, cutoff range, cutoff predict, cutoff for course, indian colleges, josaa closing rank in round, iits, nits, iiits">

    <meta property="og:title" content="View Branch-wise Cut-offs - JoSAA Analysis">
    <meta property="og:description"
        content="Find cut-offs in a range over 10 years, filtered by course in the JoSAA seat allocation process.">
    <meta property="og:url" content="{{ route('search-by-branch') }}">
    <meta property="og:type" content="website">
    <meta property="twitter:card" content="summary">
    <meta property="twitter:title" content="View Branch-wise Cut-offs - JoSAA Analysis">
    <meta property="twitter:url" content="{{ route('search-by-branch') }}">
    <meta property="twitter:site" content="@@JoSAA_Analysis">
    <meta property="twitter:creator" content="@@CaptainIRS">
    <meta property="twitter:description"
        content="Find cut-offs in a range over 10 years, filtered by course in the JoSAA seat allocation process.">
    <meta property="twitter:image" content="{{ asset('favicon.png') }}">
@endsection

@section('content')
    <h1 class="text-3xl font-bold m-4 print:hidden">View Branch-wise Cut-offs</h1>

    <p class="text-lg m-4 print:hidden">
        Filter by branch allows you to filter the cut-off data with the selected branch and further narrow down with your
        choice of institutes.
    </p>
    <livewire:search-by-branch />
@endsection
