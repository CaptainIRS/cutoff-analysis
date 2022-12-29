@props(['rank', 'institute', 'course', 'program', 'hide_controls'])
@extends('layouts.app')

@section('meta')
    <meta name="description"
        content="Compare the cut-offs of a course in various rounds over 10 years in the JoSAA seat allocation process.">
    <meta name="keywords"
        content="josaa, josaa analysis, josaa cut-offs, josaa closing rank, josaa counselling, josaa counselling analysis, josaa counselling tool, engineering, engineering aspirants, round trends, indian colleges, josaa closing rank in round, iits, nits, iiits">
@endsection

@section('content')
    <livewire:round-trends-proxy :rank_type="$rank" :institute="$institute" :course="$course" :program="$program"
        :hide_controls="$hide_controls" />
@endsection
