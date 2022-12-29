@props(['rank', 'institutes', 'hide_controls'])
@extends('layouts.app')

@section('meta')
    <meta name="description"
        content="Compare the cut-offs of various courses offered by an institute over 10 years in the JoSAA seat allocation process.">
    <meta name="keywords"
        content="josaa, josaa analysis, josaa cut-offs, josaa closing rank, josaa counselling, josaa counselling analysis, josaa counselling tool, engineering, engineering aspirants, institute trends, indian colleges, indian institutes, institute popularity, iits, nits, iiits">
@endsection

@section('content')
    <livewire:institute-trends-proxy :rank_type="$rank" :institutes="$institutes" :hide_controls="$hide_controls" />
@endsection
