@props(['rank', 'branches', 'hide_controls'])
@extends('layouts.app')

@section('meta')
    <meta name="description"
        content="Compare the cut-offs for courses in a particular branch of engineering over 10 years in the JoSAA seat allocation process.">
    <meta name="keywords"
        content="josaa, josaa analysis, josaa cut-offs, josaa closing rank, josaa counselling, josaa counselling analysis, josaa counselling tool, engineering, engineering aspirants, branch trends, indian institutes, course popularity, iits, nits, iiits">
@endsection

@section('content')
    <livewire:branch-trends-proxy :rank_type="$rank" :branches="$branches" :hide_controls="$hide_controls" />
@endsection
