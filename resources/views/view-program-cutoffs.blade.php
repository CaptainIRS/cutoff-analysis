@props(['rank', 'institutes', 'courses', 'programs', 'hide_controls', 'sort_column'])
@extends('layouts.app')

@section('meta')
    <meta name="description"
        content="Find cut-offs in a range over 10 years, filtered by institute in the JoSAA seat allocation process.">
    <meta name="keywords"
        content="josaa, josaa analysis, josaa cut-offs, josaa closing rank, josaa counselling, josaa counselling analysis, josaa counselling tool, engineering, engineering aspirants, closing rank trends, cutoff trends, search cutoffs, cutoffs data, cutoff range, cutoff predict, indian colleges, josaa closing rank in round, iits, nits, iiits">
@endsection

@section('content')
    <livewire:search-by-institute-proxy :rank_type="$rank" :institutes="$institutes" :courses="$courses" :programs="$programs"
        :tableSortColumn="$sort_column" :hide_controls="$hide_controls" />
@endsection
