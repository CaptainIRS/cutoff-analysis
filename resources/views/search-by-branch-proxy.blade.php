@props(['rank', 'branches', 'hide_controls'])
@extends('layouts.app')

@section('meta')
    <meta name="description"
        content="Find cut-offs in a range over 10 years, filtered by course in the JoSAA seat allocation process.">
    <meta name="keywords"
        content="josaa, josaa analysis, josaa cut-offs, josaa closing rank, josaa counselling, josaa counselling analysis, josaa counselling tool, engineering, engineering aspirants, closing rank trends, cutoff trends, search cutoffs, cutoffs data, cutoff range, cutoff predict, cutoff for course, indian colleges, josaa closing rank in round, iits, nits, iiits">
@endsection

@section('content')
    <livewire:search-by-branch-proxy :rank_type="$rank" :branches="$branches" :hide_controls="$hide_controls" />
@endsection
