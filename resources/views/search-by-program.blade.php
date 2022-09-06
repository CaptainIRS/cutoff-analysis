@extends('layouts.app')

@section('meta')
    <meta name="description"
        content="JoSAA Analysis is a tool that helps you decide your choices for JoSAA counselling. Filter by program allows you to filter the cut-off data with the selected programs and further narrow down with your choice of institutes.">
    <meta name="keywords"
        content="josaa, josaa analysis, josaa counselling, josaa counselling analysis, josaa counselling tool, engineering, engineering aspirants, closing rank trends, cutoff trends, search cutoffs, cutoffs data, cutoff range, cutoff predict, cutoff for course, indian colleges, josaa closing rank in round, iits, nits, iiits">

    <meta property="og:title" content="Filter by Institute - JoSAA Analysis">
    <meta property="og:description"
        content="JoSAA Analysis is a tool that helps you decide your choices for JoSAA counselling. Filter by program allows you to filter the cut-off data with the selected programs and further narrow down with your choice of institutes.">
    <meta property="og:url" content="{{ route('round-trends') }}">

    <title>Filter by Program | {{ config('app.name') }}</title>
@endsection

@section('content')
    <div class="text-3xl font-bold m-4">Filter by Program</div>

    <div class="text-lg m-4">
        Filter by program allows you to filter the cut-off data with the selected programs and further narrow down with your
        choice of institutes.
    </div>
    <livewire:search-by-program />
@endsection
