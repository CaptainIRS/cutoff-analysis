@extends('layouts.app')

@section('meta')
    <meta name="description"
        content="JoSAA Analysis is a tool that helps you decide your choices for JoSAA counselling. Filter by institute allows you to filter the cut-off data with the selected institutes and further narrow down with your choice of programs.">
    <meta name="keywords"
        content="josaa, josaa analysis, josaa counselling, josaa counselling analysis, josaa counselling tool, engineering, engineering aspirants, closing rank trends, cutoff trends, search cutoffs, cutoffs data, cutoff range, cutoff predict, indian colleges, josaa closing rank in round, iits, nits, iiits">

    <meta property="og:title" content="Filter by Institute - JoSAA Analysis">
    <meta property="og:description"
        content="JoSAA Analysis is a tool that helps you decide your choices for JoSAA counselling. Filter by institute allows you to filter the cut-off data with the selected institutes and further narrow down with your choice of programs.">
    <meta property="og:url" content="{{ route('round-trends') }}">

    <title>Filter by Institute | {{ config('app.name') }}</title>
@endsection

@section('content')
    <div class="text-3xl font-bold m-4">Filter by Institute</div>

    <div class="text-lg m-4">
        Filter by institute allows you to filter the cut-off data with the selected institutes and further narrow down with
        your choice of programs.
    </div>
    <livewire:search-by-institute />
@endsection
